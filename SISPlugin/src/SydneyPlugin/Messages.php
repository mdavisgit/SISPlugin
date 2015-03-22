<?php

namespace SydneyPlugin;

use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\Server;

class Messages extends PluginTask {
	public function SendMessageToAll($m) {
		Server::getInstance ()->broadcastMessage ( "SMTA - " . $m );
	}
}