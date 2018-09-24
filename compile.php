<?php

$outfile = "icecalc";
$shebang = "#!/usr/bin/env php";
$includeDir = [
	"assets",
	"bootstrap",
	"config",
	"src",
];
$stubDir = "stubs";
$tmpDir = __DIR__."/tmp";
$baseDir = "icecalc";

$uniq = sha1(microtime(true).time());
$compileDir = $tmpDir."/compile_{$uniq}";
if (!is_dir($compileDir)) {
	printf("Creating compile dir...");
	mkdir($compileDir);
}
define("FILE_BUF", 4096);
$cmdFile = fopen($compileDir."/install.php", "w+");
flock($cmdFile, LOCK_EX);
fwrite($cmdFile, "$shebang\n<?php \$baseDir=__DIR__.\"/{$baseDir}\";\n");
fwrite($cmdFile, 
	"\$a=\"file_put_contents\";\$b=\"shell_exec\";\ndefine(\"_FX\",LOCK_EX|FILE_APPEND);\n".
	"if(trim(\$b(\"whoami\"))!==\"root\"){echo \"You need to run this script as root!\n\";exit(1);}"
);
foreach ($includeDir as $v) {
	$dir = explode("\n", trim(shell_exec("find {$v}")));
	foreach ($dir as $v) {
		if (is_dir($v)) {
			fwrite($cmdFile,
				"echo \$b(\"mkdir -pv \\\"{\$baseDir}/{$v}\\\"\");\n"
			);
		} else {
			fwrite($cmdFile, "echo \"Writing {\$baseDir}/{$v}...\\n\";\n");
			$vhandle = fopen($v, "r+");
			fwrite($cmdFile, 
				"\$a(\$f=\"{\$baseDir}/{$v}\",\"".
				escapeBin(fread($vhandle, FILE_BUF))."\");\n"
			);
			while (!feof($vhandle)) {
				fwrite($cmdFile, 
					"\$a(\$f,\"".
					escapeBin(fread($vhandle, FILE_BUF))."\",_FX);\n"
				);
			}
			fclose($vhandle);
		}
	}
}

foreach (["run.php", "main.php", "gtk.so"] as $v) {
	fwrite($cmdFile, "echo \"Writing {\$baseDir}/{$v}...\\n\";\n");
	$vhandle = fopen($v, "r+");
	fwrite($cmdFile, 
		"\$a(\$f=\"{\$baseDir}/{$v}\",\"".
		escapeBin(fread($vhandle, FILE_BUF))."\");\n"
	);
	while (!feof($vhandle)) {
		fwrite($cmdFile, 
			"\$a(\$f,\"".
			escapeBin(fread($vhandle, FILE_BUF))."\",_FX);\n"
		);
	}
	fclose($vhandle);
}

$v = "libphpx.so";
fwrite($cmdFile, "echo \"Writing /usr/local/lib/{$v}...\\n\";\n");
$vhandle = fopen($v, "r+");
fwrite($cmdFile, 
	"\$a(\$f=\"/usr/local/lib/{$v}\",\"".
	escapeBin(fread($vhandle, FILE_BUF))."\");\n"
);
while (!feof($vhandle)) {
	fwrite($cmdFile, 
		"\$a(\$f,\"".
		escapeBin(fread($vhandle, FILE_BUF))."\",_FX);\n"
	);
}
fclose($vhandle);
print "\n";
fwrite($cmdFile, 
	"echo \$b(\"ln -svf {\$f} /usr/lib\");\n".
	"echo \"Do you want to run {$outfile}? (y/n) \";\n".
	"\$h=fopen(\"php://stdin\",\"r+\");".
	"\$ans=trim(fread(\$h,5));".
	"if(\$ans[0]=='y'||\$ans[0]=='Y')echo \$b(\"nohup \".PHP_BINARY.\" {\$baseDir}/run.php >> /dev/null 2>&1 &\");"
);
fclose($cmdFile);
rename($compileDir."/install.php", "{$tmpDir}/install.php");
shell_exec("chmod +x {$tmpDir}/install.php");
print shell_exec("rm -rfv {$compileDir}");

function escapeBin($str)
{
	return str_replace(
		["\\", "\"", "\$", "\n"],
		["\\\\", "\\\"", "\\\$", "\\n"],
		$str
	);
}
