<?php

if (!defined("INIT")) {
	define("INIT", 1);

	function iceteaInternalAutoloader(string $class): void
	{
		$class = str_replace("\\", "/", $class);
		if (file_exists($f = BASEPATH."/src/".$class.".php")) {
			require $f;
		}
	}

	spl_autoload_register("iceteaInternalAutoloader");
}
