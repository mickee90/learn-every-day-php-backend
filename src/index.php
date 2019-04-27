<?php
namespace MN;

use MN\System;

define('MN_DIR', __DIR__.DIRECTORY_SEPARATOR);

require_once('System/Core.php');

$core = System\Core::getInstance();
$core->run();