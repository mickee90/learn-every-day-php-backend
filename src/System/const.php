<?php
define('MN', 'MN');
define('MN_ENV', 'dev');
define('EXT', '.php');
define('DS', DIRECTORY_SEPARATOR);

define('MN_SERVER_PRODUCTION', false);
define('MN_PROJECT_NAME', 'Webbtavlan');

define('MN_HTTPS', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] === 443);
define('MN_PROTOCOL', MN_HTTPS ? 'https' : 'http');
define('MN_HTTP_URL', sprintf('%s://%s', MN_PROTOCOL, $_SERVER['HTTP_HOST']));
define('MN_HTTP_URI', $_SERVER['REQUEST_URI']);
define('MN_HTTP_FULL_URL', MN_HTTP_URL.$_SERVER['REQUEST_URI']);

define('MN_HTTP_METHOD', strtoupper($_SERVER['REQUEST_METHOD']));
define('MN_HTTP_POST', MN_HTTP_METHOD == 'POST');
define('MN_HTTP_GET', MN_HTTP_METHOD == 'GET');

define('MN_DIR_CONTROLLERS', MN_DIR.'Controllers'.DS);
define('MN_DIR_RESOURCES', MN_DIR.'Resources'.DS);
define('MN_DIR_VIEWS', MN_DIR.'Resources/views'.DS);
define('MN_DIR_SYSTEM', MN_DIR.'System'.DS);
define('MN_DIR_MEDIA', MN_DIR.'Media'.DS);
define('MN_DIR_Lib', MN_DIR.'Lib'.DS);
define('MN_DIR_VENDOR', MN_DIR.'vendor'.DS);

define('MN_DB_NAME', 'learn_every_day');
define('MN_DB_TYPE', 'mysql');
define('MN_DB_HOST', 'mysql');
define('MN_DB_USER', 'root');
define('MN_DB_PASS', 'rootpassword');
define('MN_DB_PORT', 3306);