<?php
namespace MN\System;

/**
 * Merge into a directory path with an arbitrary amount of folders
 *
 * @param array ...$dirs
 * @return string
 */
function dirMerge(...$dirs) {
	$dirs = array_filter($dirs, function($v) {
		return !is_null($v);
	});
	
	return implode(DS, array_map(function($key, $dir) {
			$dir = preg_replace('%(?<!:)[\\/]+%sim', DS, $dir);
			return ($key == 0 ? rtrim($dir, DS) : trim($dir, DS));
		}, array_keys($dirs), $dirs)).DS;
}

/**
 * Same as dirMerge(), except excludes the final directory separator
 *
 * @param array ...$dirs
 * @return string
 */
function pathMerge(...$dirs) {
	return rtrim(dirMerge(...$dirs), DS);
}

function getBearerToken($auth) {
	if (!empty($auth)) {
		if (preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
			return $matches[1];
		}
	}
	return null;
}

function core() {
	return Core::getInstance();
}

function db() {
	return core()->getDb();
}

function toCamelCase($string) {
	return ucwords(strtolower($string));
}

function genUUID()  {
	$data = random_bytes(16);
	
	assert(strlen($data) == 16);
	
	$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
	$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
	
	return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}