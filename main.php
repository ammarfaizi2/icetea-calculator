<?php

require __DIR__."/config/init.php";
require __DIR__."/bootstrap/init.php";

$st = new IceTea;
$st->build();
$st->run();
