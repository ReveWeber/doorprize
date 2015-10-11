<?php
$dsn = 'mysql:host=localhost;dbname=doorprize';
$db = new PDO($dsn, 'username', 'password');
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);