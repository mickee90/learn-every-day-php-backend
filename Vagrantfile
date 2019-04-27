# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = "ubuntu/trusty64"

    # Forward ports to Apache and MySQL
    config.vm.network "forwarded_port", guest: 80, host: 8888

    # If using Webmin
    config.vm.network "forwarded_port", guest: 10008, host: 10008

    # Forward MySQL port
    config.vm.network "forwarded_port", guest: 3306, host: 1234

    # Trigger provisioning
    config.vm.provision "shell", path: "bootstrap.sh"

    config.vm.provider :virtualbox do |v|
      v.customize ["modifyvm", :id, "--memory", 2048]
#       v.customize ["guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/ — timesync-set-threshold", 1000]
    end
end

# ftp MsnjtYK5gCsymmFk ftr.cityweb.se
