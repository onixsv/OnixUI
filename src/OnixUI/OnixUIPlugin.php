<?php
declare(strict_types=1);

namespace OnixUI;

use OnixUI\event\UIOpenEvent;
use OnixUI\form\MainForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\ItemIds;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class OnixUIPlugin extends PluginBase implements Listener{

	/** @var Config */
	public static Config $config;

	public static array $db = [];

	protected function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		self::$config = new Config($this->getDataFolder() . "Data.yml", Config::YAML);
		self::$db = self::$config->getAll();
	}

	protected function onDisable() : void{
		self::$config->setAll(self::$db);
		self::$config->save();
	}

	public function handlePlayerItemUse(PlayerItemUseEvent $event){
		$player = $event->getPlayer();
		$item = $event->getItem();

		if($item->getId() === ItemIds::CLOCK){
			$ev = new UIOpenEvent($player);
			$ev->call();
			if(!$ev->isCancelled()){
				$player->sendForm(new MainForm());
			}
		}
	}

	/**
	 * @param PlayerInteractEvent $event
	 *
	 * @handleCancelled true
	 */
	public function handlePlayerInteract(PlayerInteractEvent $event) : void{
		$player = $event->getPlayer();
		$item = $event->getItem();

		if($item->getId() === ItemIds::CLOCK){
			$ev = new UIOpenEvent($player);
			$ev->call();
			if(!$ev->isCancelled()){
				$player->sendForm(new MainForm());
			}
		}
	}

	public function handleJoin(PlayerJoinEvent $event){
		if(!isset(self::$db[$event->getPlayer()->getName()])){
			self::$db[$event->getPlayer()->getName()] = [];
		}
	}
}