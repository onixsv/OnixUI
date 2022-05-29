<?php
declare(strict_types=1);

namespace OnixUI\form\functions;

use OnixUI\form\functions\sub\CommandAddSubFunction;
use OnixUI\form\functions\sub\CommandRemoveSubFunction;
use OnixUI\OnixUIPlugin;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\Server;

class UserCommandFunction implements Form{

	/** @var Player */
	private Player $player;

	protected array $buttons = [];

	public function __construct(Player $player){
		$this->player = $player;
	}

	public function jsonSerialize() : array{
		$buttons = [];

		foreach(OnixUIPlugin::$db[$this->player->getName()] ?? [] as $command){
			$buttons[] = ["text" => "§d" . $command . "§f 명령어"];
		}

		$buttons[] = ["text" => "§l* 명령어 추가하기"];
		$buttons[] = ["text" => "§l* 명령어 제거하기"];
		$this->buttons = $buttons;

		return [
			"type" => "form",
			"title" => "§lCommandManager - Master",
			"content" => "사용하실 명령어를 클릭해주세요.",
			"buttons" => $buttons
		];
	}

	public function handleResponse(Player $player, $data) : void{
		if($data !== null){
			$commands = OnixUIPlugin::$db[$player->getName()] ?? [];

			if($data === count($this->buttons) - 2){
				$player->sendForm(new CommandAddSubFunction());
			}elseif($data === count($this->buttons) - 1){
				$player->sendForm(new CommandRemoveSubFunction($player));
			}else{
				$ev = new PlayerCommandPreprocessEvent($player, (substr($commands[$data], 0, 1) !== "/" ? "/" . $commands[$data] : $commands[$data]));
				$ev->call();

				if(!$ev->isCancelled()){
					Server::getInstance()->dispatchCommand($player, (substr($commands[$data], 0, 1) !== "/" ? $commands[$data] : substr($commands[$data], 1)));
				}
			}
		}
	}
}