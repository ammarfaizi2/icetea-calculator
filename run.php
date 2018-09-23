<?php

$ext = __DIR__."/gtk.so";
$flag = "-d \"extension={$ext}\"";
$file = __DIR__."/main.php";
$phpbin = PHP_BINARY;

$descriptor = [
	["pipe", "r+"],
	["file", "php://stdout", "w+"],
	["file", "php://stderr", "w+"]
];

$cwd = getcwd();
unset($_SERVER["argv"]);

$handle = proc_open("{$phpbin} {$flag} \"{$file}\"", $descriptor, $pipes, $cwd, $_SERVER);

if (is_resource($handle)) {
	fclose($pipes[0]);
	proc_close($handle);
}
