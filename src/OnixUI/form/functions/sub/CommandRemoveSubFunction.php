<?php
declare(strict_types=1);

namespace OnixUI\form\functions\sub;

use OnixUI\OnixUIPlugin;
use OnixUtils\OnixUtils;
use pocketmine\form\Form;
use pocketmine\player\Player;

class CommandRemoveSubFunction implements Form{

	/** @var Player */
	private Player $player;

	public function __construct(Player $player){
		$this->player = $player;
	}

	public function jsonSerialize() : array{
		$buttons = [];
		foreach(OnixUIPlugin::$db[$this->player->getName()] ?? [] as $command){
			$buttons[] = ["text" => "§d" . $command . "§f 명령어"];
		}
		return [
			"type" => "form",
			"title" => "§lCommandManager - Master",
			"content" => "§l제거하고 싶은 명령어를 클릭해주세요.",
			"buttons" => $buttons
		];
	}

	public function handleResponse(Player $player, $data) : void{
		if($data !== null){
			$commands = [];

			foreach(OnixUIPlugin::$db[$player->getName()] ?? [] as $command){
				$commands[] = $command;
			}

			if(isset($commands[$data])){
				$command = $commands[$data];
				unset($commands[$data]);
				$commands = array_values($commands);
				OnixUIPlugin::$db[$player->getName()] = $commands;
				OnixUtils::message($player, "§d" . $command . "§f 명령어를 제거했습니다.");
			}
		}
	}
}