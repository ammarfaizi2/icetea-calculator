<?php

require __DIR__."/config/init.php";
require __DIR__."/bootstrap/init.php";

$st = new IceTeaCalculator;
$st->build();
$st->run();
