<?php
declare(strict_types = 1);

$p1 = __DIR__ . '/../vendor/autoload.php';
$p2 = __DIR__ . '/../../../autoload.php';
file_exists($p1) ? (require $p1) : (require $p2);
