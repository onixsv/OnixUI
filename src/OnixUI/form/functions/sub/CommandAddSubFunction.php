<?php
declare(strict_types=1);

namespace OnixUI\form\functions\sub;

use OnixUI\OnixUIPlugin;
use OnixUtils\OnixUtils;
use pocketmine\form\Form;
use pocketmine\player\Player;

class CommandAddSubFunction implements Form{

	public function jsonSerialize() : array{
		return [
			"type" => "custom_form",
			"title" => "§lCommandManager - Master",
			"content" => [
				[
					"type" => "input",
					"text" => "§l추가할 명령어를 입력해주세요."
				]
			]
		];
	}

	public function handleResponse(Player $player, $data) : void{
		if(trim($data[0] ?? "") !== ""){
			if(!in_array($data[0], OnixUIPlugin::$db[$player->getName()] ?? [])){
				OnixUIPlugin::$db[$player->getName()][] = $data[0];
				OnixUtils::message($player, "명령어를 추가하였습니다: §d" . $data[0]);
			}else{
				OnixUtils::message($player, "해당 명령어는 이미 등록되어 있습니다.");
			}
		}
	}
}