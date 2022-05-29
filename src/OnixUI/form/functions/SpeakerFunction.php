<?php
declare(strict_types=1);

namespace OnixUI\form\functions;

use onebone\economyapi\EconomyAPI;
use OnixUtils\OnixUtils;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\Server;

class SpeakerFunction implements Form{

	public function jsonSerialize() : array{
		return [
			"type" => "custom_form",
			"title" => "§l확성기 시스템",
			"content" => [
				[
					"type" => "input",
					"text" => "§l확성기에 쓸 말을 적어주세요."
				]
			]
		];
	}

	public function handleResponse(Player $player, $data) : void{
		if(trim($data[0] ?? "") !== ""){
			if(mb_strlen($data[0], "UTF-8") <= 50){
				if(EconomyAPI::getInstance()->reduceMoney($player, 1000) === EconomyAPI::RET_SUCCESS){
					Server::getInstance()->broadcastMessage("§d<§f확성기§d> §f" . $player->getName() . " > §l" . $data[0]);
				}else{
					OnixUtils::message($player, "돈이 부족합니다.");
				}
			}else{
				OnixUtils::message($player, "확성기 글자 수는 50자 미만이어야 합니다.");
			}
		}else{
			OnixUtils::message($player, "메시지를 입력해주세요.");
		}
	}
}