<?php
namespace MN;

use MN\System;

define('MN_DIR', __DIR__.DIRECTORY_SEPARATOR);

require(MN_DIR.'vendor/autoload.php');

$openapi = \OpenApi\scan(MN_DIR);
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();