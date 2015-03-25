<?php

/**
 * @author Michael J Davis
 *
 */

namespace SISPlugin;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Stone;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\nbt\tag\String;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerMoveEvent;

// Global Variables
$playersOnline = array ();
$playersMsgSentStage1 = array ();
$playersMsgSentStage2 = array ();
$stage1MsgSent;
$stage2MsgSent;

// Global Constants
define ( "STAGE1_MIN", 12 );
define ( "STAGE2_MIN", 60 );
define ( "STAGE3_MIN", 120 );
define ( "STAGE1_MESSAGE", "You have been playing for too long. \n Take a break and eat a a cookie." );
define ( "STAGE2_MESSAGE", "You have been playing for too long. \n You will be kicked in ONE Minute." );

class Main extends PluginBase implements Listener {
	/*
	 * (non-PHPdoc)
	 * @see \pocketmine\plugin\PluginBase::onLoad()
	 */
	public function onLoad() {
		$this->getLogger ()->info ( "onLoad() has been called!" );
	}
	

	/*
	 * (non-PHPdoc)
	 * @see \pocketmine\plugin\PluginBase::onEnable()
	 */
	public function onEnable() {
		$this->getLogger ()->info ( "onEnable() has been called!!!" );		
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
		// The line above is required to use additional events
		// Also must include "use pocketmine\event\player\<EventNameToUse>"
		// You can get supported event names here: http://docs.pocketmine.net/d3/daf/namespacepocketmine_1_1event.html
		// expand the events on the left column to see the choices. Ex. "use pocketmine\event\player\PlayerJoinEvent"
	}
	

	/*
	 * (non-PHPdoc)
	 * @see \pocketmine\plugin\PluginBase::onDisable()
	 */
	public function onDisable() {
		$this->getLogger ()->info ( "onDisable() has been called!" );
	}
	
	
	/**
	 *
	 * @param PlayerKickEvent $PKE
	 *        	- Function to run code when "kicking" a player
	 */
	public function onKick(PlayerKickEvent $PKE) {
		$p = $PKE->getPlayer ();
		$name = $p->getDisplayName ();
		$this->messageToAll ( $name . " was kicked" );
	}
	
	/* (non-PHPdoc)
	 * @see \pocketmine\plugin\PluginBase::onCommand()
	 */
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		global $playersOnline;
		switch ($command->getName ()) {
			case "hi" :
				$sender->sendMessage ( "Hello " . $sender->getName () . "!!!");
				$sender->sendMessage ( "Boo Ya" );				
				return true;
			case "tst" :
				$sender->sendMessage ( "Test works" );
				return true;
			case "sis" :
				$sender->sendMessage ( " :-)\n  :-)\n   Hello Strothoff School!\n     :-)\n      :-)" );
				return true;			
			default :
				return false;
		}
	}
	
	
	/**
	 * @param PlayerJoinEvent $PJE
	 */
	public function onJoin(PlayerJoinEvent $PJE) {
		// Format for function is "public function <anyName>(<EventNameFromDocumentation> <anyVariableName>) {
		global $playersOnline;
		global $playersMsgSentStage1;
		global $playersMsgSentStage2;
		$this->messageToAll ( "onJoin!" );
		$player = $PJE->getPlayer ();
		$name = $player->getDisplayName ();
		$this->getServer ()->broadcastMessage ( "Howdy! " . $name . " joined the game!!");
		
		// Add the new player and their join time to the $playersOnline Array 
		
		$playersOnline [$name] = time ();
		$playersMsgSentStage1 [$name] = null;
		$playersMsgSentStage2 [$name] = null;
	}
	
	
	/**
	 * @param PlayerQuitEvent $PQE
	 */
	public function onQuit(PlayerQuitEvent $PQE) {
		global $playersOnline;
		$player = $PQE->getPlayer ();
		$name = $player->getDisplayName ();
		unset ( $playersOnline [$name] );
		$this->messageToAll ( "Bye Bye " . $name );
	}
	
		
	
	/**
	 * @param PlayerRespawnEvent $event
	 */
	public function onSpawn(PlayerRespawnEvent $event) {
		Server::getInstance ()->broadcastMessage ( $event->getPlayer ()->getDisplayName () . " has just spawned!" );
	}
	
	
	/**
	 * @param PlayerInteractEvent $PIE
	 * This is run whenever the player interacts with something i.e. digs a hole, etc.
	 */
	public function onTouch(PlayerInteractEvent $PIE) {
		global $playersOnline;
		$p = $PIE->getPlayer ();
		$name = $p->getDisplayName ();
		$this->checkPlayerOnlineTime ( $p, $name );
	}
	
	
	/**
	 *
	 * @param PlayerMoveEvent $PME     
	 * This is run whenever the player moves   	
	 */
	public function onMove(PlayerMoveEvent $PME) {
		global $playersOnline;
//		global $playersMsgSentStage1;
//		global $playersMsgSentStage2;
		$p = $PME->getPlayer ();
		$name = $p->getDisplayName ();
		$this->checkPlayerOnlineTime ( $p, $name );	
	}
	
	
	/**
	 *
	 * @param
	 *        	p - Handle to the Event to get Player ID
	 * @param
	 *        	name - The Display name of the Plaer being checked
	 *        This fuction checks to see how long a player has been online.  If they have been online longer than the set time(s)
	 *          a message will be sent reminding them to take a break.
	 */
	public function checkPlayerOnlineTime($p, $name) {
		global $playersOnline;
		global $stage1MsgSent;
		global $stage2MsgSent;
		global $playersMsgSentStage1;
		global $playersMsgSentStage2;
		$timeOnline = time () - $playersOnline [$name];
				
		if (($timeOnline >= STAGE1_MIN) and ($timeOnline <= STAGE2_MIN - 1) and ($playersMsgSentStage1 [$name] != "sent")) {
			$p->sendMessage (STAGE1_MESSAGE);
			$playersMsgSentStage1 [$name] = "sent";
		} elseif (($timeOnline >= STAGE2_MIN) and ($timeOnline <= STAGE3_MIN - 1) and ($playersMsgSentStage2 [$name] != "sent")) {			
			$p->sendMessage (STAGE2_MESSAGE);
			$playersMsgSentStage2 [$name] = "sent";
		} elseif (($timeOnline > STAGE3_MIN)) {
			
			$p->kick ( "Player was kicked for not taking a break!" );
		}
	}
	
	
	/**
	 *
	 * @param $m -
	 *        	Message text to be sent to all
	 */
	public function messageToAll($m) {
		Server::getInstance ()->broadcastMessage ( $m );
	}
}