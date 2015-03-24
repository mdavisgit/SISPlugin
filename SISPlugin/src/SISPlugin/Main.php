<?php

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

// $playerOnline is a global variable
$playersOnline = array ();

// Global Constants
define ( "STAGE1_MIN", 12 );
define ( "STAGE1_MAX", 60 );
define ( "STAGE2_MIN", 61 );
define ( "STAGE2_MAX", 120 );
define ( "STAGE3_MIN", 121 );
/**
 * @author Michael
 *
 */
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
	
	/*
	 * (non-PHPdoc)
	 * @see \pocketmine\plugin\PluginBase::onEnable()
	 */
	public function onEnable() {
		$this->getLogger ()->info ( "onEnable() has been called!!!" );
		
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
		// This line above is required to use additional events
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
	 * @param $m -
	 *        	Message text to be sent to all
	 */
	public function messageToAll($m) {
		Server::getInstance ()->broadcastMessage ( $m );
	}
	
	/**
	 *
	 * @param PlayerKickEvent $PKE
	 *        	- Function to run code when "kicking" a player
	 */
	public function onKick(PlayerKickEvent $PKE) {
		$this->messageToAll ( "someone was kicked" );
	}
	
	/* (non-PHPdoc)
	 * @see \pocketmine\plugin\PluginBase::onCommand()
	 */
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		global $playersOnline;
		switch ($command->getName ()) {
			case "hi" :
				$sender->sendMessage ( "Hello " . $sender->getName () . "!!!" . time () );
				$this->messageToAll ( "Boo Ya" );
				$e = "Lola140";
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
	public function playerJoin(PlayerJoinEvent $PJE) {
		// Format for function is "public function <anyName>(<EventNameFromDocumentation> <anyVariableName>) {
		global $playersOnline;
		$this->messageToAll ( "onJoin!" );
		$player = $PJE->getPlayer ();
		$name = $player->getDisplayName ();
		$this->getServer ()->broadcastMessage ( "Howdy " . $name . " [DEFAULT] joined the game!!" . time () );
		
		$playersOnline [$name] = time ();
		$playersOnline [$name] = time ();
		
		// Duplicate the new entry - for testing only
		$nameDup = $name . "xx";
		$playersOnline [$nameDup] = time ();
		// Duplicate the new entry - for testing only
	}
	
	
	/**
	 * @param PlayerQuitEvent $PQE
	 */
	public function onQuit(PlayerQuitEvent $PQE) {
		global $playersOnline;
		$this->messageToAll ( "bye bye" );
		$player = $PQE->getPlayer ();
		$name = $player->getDisplayName ();
		$this->messageToAll ( print_r ( $playersOnline ) );
		unset ( $playersOnline [$name] );
		$this->messageToAll ( print_r ( $playersOnline ) );
	}
	
		
	
	/**
	 * @param PlayerRespawnEvent $event
	 */
	public function onSpawn(PlayerRespawnEvent $event) {
		Server::getInstance ()->broadcastMessage ( $event->getPlayer ()->getDisplayName () . " has just spawned!" );
	}
	
	
	/**
	 * @param PlayerInteractEvent $PIE
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
	 */
	public function onMove(PlayerMoveEvent $PME) {
		global $playersOnline;
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
	 */
	public function checkPlayerOnlineTime($p, $name) {
		global $playersOnline;
		$timeOnline = time () - $playersOnline [$name];
		if (($timeOnline >= STAGE1_MIN) and ($timeOnline <= STAGE1_MAX)) {
			$p->sendMessage ( "You have been playing for " . $timeOnline . " seconds." );
			$p->sendMessage ( "Take a break and eat a cookie" );
		} elseif (($timeOnline >= STAGE2_MIN) and ($timeOnline <= STAGE2_MAX)) {
			
			$p->sendMessage ( "You have been playing for " . $timeOnline . " seconds." );
			$p->sendMessage ( "Take a break and eat a cookie" );
			$p->sendMessage ( "You will be kicked from the server if you do not" );
		} elseif (($timeOnline > STAGE3_MIN)) {
			
			$p->kick ( "Take a break!" );
		}
	}
}