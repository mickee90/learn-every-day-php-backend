#!/bin/bash

function inArray { eval echo \${$1[$2]}; }
function getSetting { echo $(inArray settings $1); }
function getConfigFile {  echo $(inArray config_file $1); }

### Environment declarations ###
declare -A settings=(
    ["mysql_engine"]="mariadb"      # mariadb/mysql
    ["mysql_user"]="root"           #
    ["mysql_pass"]="root"           #
    ["mariadb_version"]="10.0"      # http://ftp.ddg.lth.se/mariadb/repo/
    ["php_version"]="7.1"           # 7/5.x
    ["web_root"]="src"              # web root within your Vagrant project
    ["webmin"]=false                # true/false
    ["phpmyadmin"]=true           # true/false
)

# Add basic tools
declare tools=(
    "build-essential"
    "binutils-doc"
    "git"
    "subversion"
    "tree"
    "htop"
)

# Add repositories
declare repositories=(
    "ppa:nijel/phpmyadmin"
)

if [ "$(getSetting mysql_engine)" = "mariadb" ] ; then
    repositories+=(
        "deb [arch=amd64,i386,ppc64el] http://ftp.ddg.lth.se/mariadb/repo/$(getSetting mariadb_version)/ubuntu trusty main"
    )
fi

declare -A php_ini=(
    ["display_startup_errors"]="On"
    ["display_errors"]="On"
    ["post_max_size"]="64M"
    ["upload_max_filesize"]="64M"
    #["mbstring.func_overload"]=6
)

declare php_pkg=();

# PHP version dependant settings
case $(getSetting php_version) in
    7.1 )
        # Add repository for PHP7.1
        repositories+=("ppa:ondrej/php")

        settings+=(["php_dir"]="/etc/php/7.1")
        php_pkg+=(
            "php7.1"
            "php7.1-mysql"
            "libapache2-mod-php7.1"
            "php7.1-mbstring"
            "php7.1-curl"
            "php7.1-xml"
            "php7.1-zip"
            "php-xdebug"
            "php-memcached"
            "php-imagick"
        )
        ;;

    7 )
        # Add repository for PHP7
        repositories+=("ppa:ondrej/php")
        
        settings+=(["php_dir"]="/etc/php/7.0")
        php_pkg+=(
            "php7.0"
            "php7.0-mysql"
            "libapache2-mod-php7.0"
            "php7.0-mbstring"
            "php7.0-curl"
            "php7.0-xml"
            "php7.0-sybase"
            "php7.0-zip"
            "php7.0-gd"
            "php-xdebug"
            "php-memcached"
            "php-imagick"
        )
        ;;

    5.6 )
        repositories+=("ppa:ondrej/php")
        settings+=(["php_dir"]="/etc/php/5.6")
        php_pkg+=(
            "php5.6"
            "php5.6-mcrypt"
            "php5.6-mbstring"
            "php5.6-curl"
            "php5.6-cli"
            "php5.6-mysql"
            "php5.6-xml"
            "php5.6-zip"
            "php-imagick"
        )
        ;;

    5[0-9.]* )

        settings+=(["php_dir"]="/etc/php5")
        php_pkg+=(
            "php5"
            "php5-curl"
            "php5-mysql"
            "php5-xml"
            "php56-zip"
            "php5-xdebug"
            "php5-sqlite"
            "php5-memcached"
            "php5-imagick"
            "php-gettext"
        )
        ;;
    * )
        ;;
esac

# Shared PHP packages
php_pkg+=(
   "php-pear"
   "memcached"
   "libmemcached-tools" # memcdump --servers=localhost
)

### End of environment declarations ###

declare -A config_file=(
    ["php"]="$(getSetting php_dir)/apache2/php.ini"
    ["xdebug"]="$(getSetting php_dir)/mods-available/xdebug.ini"
    ["memcached"]="$(getSetting php_dir)/mods-available/memcached.ini"
    ["apache"]="/etc/apache2/envvars"
    ["vhost"]="/etc/apache2/sites-available/vagrant_vhost.conf"
    ["mysql"]="/etc/mysql/my.cnf"
)

main() {
    repositories_go
    update_go
    network_go
    tools_go
    apache_go
    mysql_go
    php_go
    
    if [ "$(getSetting webmin)" = true ] ; then
        webmin_go
    fi
    
    if [ "$(getSetting phpmyadmin)" = true ] ; then
        phpmyadmin_go
    fi
    
    autoremove_go
    additional_events
}

repositories_go() {
    apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xcbcb082a1bb943db

    for repository in "${repositories[@]}"
    do
        add-apt-repository -y "$repository"
    done
}

update_go() {
    # Update the server
    apt-get update
    # apt-get -y upgrade
}

autoremove_go() {
    apt-get -y autoremove
}

additional_events() {

    # Enable bash completion for root
    cat << EOF >> /root/.bashrc

if [ -f /etc/bash_completion ] && ! shopt -oq posix; then
    . /etc/bash_completion
fi
EOF
}
network_go() {
    IPADDR=$(/sbin/ifconfig eth0 | awk '/inet / { print $2 }' | sed 's/addr://')
    sed -i "s/^${IPADDR}.*//" /etc/hosts
    echo ${IPADDR} ubuntu.localhost >> /etc/hosts       # Just to quiet down some error messages
}

tools_go() {
    # Install tools
    apt-get -y install ${tools[@]}
}

apache_go() {
    # Install Apache
    apt-get -y install apache2

    sed -i "s/^\(.*\)www-data/\1vagrant/g" $(getConfigFile apache)
    chown -R vagrant:vagrant /var/log/apache2
    
    #usermod -aG vagrant www-data
    #usermod -aG www-data vagrant

    if [ ! -f "${apache_vhost_file}" ]; then
        cat << EOF > $(getConfigFile vhost)
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /vagrant/$(getSetting web_root)
    LogLevel debug

    ErrorLog /var/log/apache2/error.log
    CustomLog /var/log/apache2/access.log combined

    <Directory /vagrant/$(getSetting web_root)>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF
    fi

    a2dissite 000-default
    a2ensite vagrant_vhost

    a2enmod rewrite

    service apache2 reload
    update-rc.d apache2 enable
}

mysql_go() {
    echo "$(getSetting mysql_engine)-server mysql-server/root_password password $(getSetting mysql_pass)" | debconf-set-selections
    echo "$(getSetting mysql_engine)-server mysql-server/root_password_again password $(getSetting mysql_pass)" | debconf-set-selections
        
    apt-get install -y $(getSetting mysql_engine)-client $(getSetting mysql_engine)-server
    
    sed -i "s/bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" $(getConfigFile mysql)

    # Allow root access from any host
    echo "GRANT ALL PRIVILEGES ON *.* TO '$(getSetting mysql_user)'@'%' IDENTIFIED BY '$(getSetting mysql_pass)' WITH GRANT OPTION" | mysql -u $(getSetting mysql_user) --password=$(getSetting mysql_pass)
    echo "GRANT PROXY ON ''@'' TO '$(getSetting mysql_user)'@'%' WITH GRANT OPTION" | mysql -u $(getSetting mysql_user) --password=$(getSetting mysql_pass)

    if [ -d "/vagrant/provision-sql" ]; then
        echo "Executing all SQL files in /vagrant/provision-sql folder ..."
        echo "-------------------------------------"
        for sql_file in /vagrant/provision-sql/*.sql
        do
            echo "EXECUTING $sql_file..."
              time mysql -u $(getSetting mysql_user) --password=$(getSetting mysql_pass) < $sql_file
              echo "FINISHED $sql_file"
              echo ""
        done
    fi

    cat << EOF >> $(getConfigFile mysql)

[mysqld]
max_allowed_packet = 64M
collation-server = utf8mb4_bin
init-connect = 'SET NAMES utf8mb4'
character-set-server = utf8mb4
character-set-client-handshake = FALSE
EOF

    service mysql restart
    update-rc.d apache2 enable
}

php_go() {
    echo "${php_pkg[0]}" > /srv/packages

    apt-get -y install ${php_pkg[@]}

    for name in "${!php_ini[@]}"
    do
        sed -i "s/[;# ]*$name = .*/$name = ${php_ini[$name]}/g" $(getConfigFile php)
    done
    
    sed -i "s/#php_admin_value/php_admin_value/g" $(getConfigFile vhost)
    
    cat /vagrant/config/xdebug.ini > $(getConfigFile xdebug)
    cat /vagrant/config/memcached.ini > $(getConfigFile memcached)
    
    # Install latest version of Composer globally
    if [ ! -f "/usr/local/bin/composer" ]; then
        curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    fi

    # Install PHP Unit 4.8 globally
    if [ ! -f "/usr/local/bin/phpunit" ]; then
        curl -sS -O -L https://phar.phpunit.de/phpunit-old.phar
        chmod +x phpunit-old.phar
        mv phpunit-old.phar /usr/local/bin/phpunit
    fi
    
    service apache2 reload
}


webmin_go() {
    echo "deb http://download.webmin.com/download/repository sarge contrib" >> /etc/apt/sources.list
    
    wget -P /root http://www.webmin.com/jcameron-key.asc
    apt-key add /root/jcameron-key.asc
    
    apt-get update
    apt-get -y install webmin
}

phpmyadmin_go() {

    echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/app-password-confirm password $(getSetting mysql_pass)" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/mysql/admin-pass password $(getSetting mysql_pass)" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/mysql/app-pass password $(getSetting mysql_pass)" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | debconf-set-selections

    echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/app-password-confirm password root" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/mysql/admin-pass password root" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/mysql/app-pass password root" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | debconf-set-selections
    
    apt-get -y install phpmyadmin

    chmod +r /var/lib/phpmyadmin/blowfish_secret.inc.php
}

main

exit 0