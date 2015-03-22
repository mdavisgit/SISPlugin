<?php

namespace SydneyPlugin;

require_once 'Main.php';

use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\Server;
use pocketmine\event\player\PlayerInteractEvent;
use SydneyPlugin;

class BroadcastPluginTask extends PluginTask {
	/**
	 * private $api;
	 * public function __construct(ServerAPI $api, $server = false){
	 * $this->api = $api;
	 * }
	 */
	public function onRun($currentTick) {
		global $playersOnline;
//		$date = date ( 'Y-m-d H:i:s' );
//		Server::getInstance ()->broadcastMessage ( "Current Time: " . time () . "  -  " . $date );
		
		// foreach($players as $x => $x_value) {
		// echo "Key=" . $x . ", Value=" . $x_value;
		// echo "<br>";
		// }
		
//		$length = count ( $playersOnline );
//		Server::getInstance ()->broadcastMessage ( "Players Online: " . $length );
		
		//			Server::getInstance ()->broadcastMessage ( "Player: " . $wPlayerName );
		//			Server::getInstance ()->broadcastMessage ( "  joined " . $wPlayerJoinTime );
		//			Server::getInstance ()->broadcastMessage ( "  and has been online " . $wOnlineTime . " seconds." );
		//			Server::getInstance ()->broadcastMessage ( "  Please take a break" );
	
	/**
	 * for($i = 0; $i < $length; $i ++) {
	 * $wValue = $playersOnline [$i];
	 * if (($x_pos = strpos ( $wValue, '~' )) !== FALSE) {
	 * $wPlayerName = substr ( $wValue, 0, $x_pos );
	 * $wPlayerJoinTime = substr ( $wValue, $x_pos + 1 );
	 * $wOnlineTime = time () - $wPlayerJoinTime;
	 * }
	 *
	 * if (($wOnlineTime >= 5) && ($wOnlineTime <= 240)) {
	 * Server::getInstance ()->broadcastMessage ( "Player: " . $wPlayerName );
	 * Server::getInstance ()->broadcastMessage ( " joined " . $wPlayerJoinTime );
	 * Server::getInstance ()->broadcastMessage ( " and has been online " . $wOnlineTime . " seconds." );
	 * Server::getInstance ()->broadcastMessage ( " Please take a break" );
	 * }
	 * }
	 */
	
	/**
	 * $this->testmessage("BroadcastPluginTask - Before foreach");
	 * $length = count($playersOnline);
	 * $this->testmessage("Length: " .
	 * $length);
	 *
	 * for ($i = 0; $i < $length; $i++) {
	 * $this->testmessage("i: " . $i);
	 * $this->testmessage("Index: " . $i . " is " . $playersOnline[$i]);
	 * }
	 */
	}
}
