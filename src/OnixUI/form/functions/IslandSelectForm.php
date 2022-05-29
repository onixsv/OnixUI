<?php

declare(strict_types=1);

namespace OnixUI\form\functions;

use pocketmine\form\Form;
use pocketmine\player\Player;
use function is_int;

class IslandSelectForm implements Form{

	public function jsonSerialize() : array{
		return [
			"type" => "form",
			"title" => "§lOnixUI - Master",
			"content" => "이동할 섬을 선택해주세요.",
			"buttons" => [
				["text" => "§d섬§f으로 이동하기"],
				["text" => "§d하늘섬§f으로 이동하기"]
			]
		];
	}

	public function handleResponse(Player $player, $data) : void{
		if(!is_int($data)){
			return;
		}
		switch($data){
			case 0:
				$player->sendForm(new IslandMoveFunction($player));
				break;
			case 1:
				$player->sendForm(new SkyIslandMoveFunction($player));
				break;
		}
	}
}