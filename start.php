<?php
require_once __DIR__ . '/src/TerrariZ/Server.php';
require_once __DIR__ . '/src/TerrariZ/Internal/Packet.php';
require_once __DIR__ . '/src/TerrariZ/Player.php';

use TerrariZ\Server;

$server = new Server();
$server->run();


?>
