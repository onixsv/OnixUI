<?php
declare(strict_types=1);

namespace OnixUI\form\functions;

use OnixUtils\OnixUtils;
use pocketmine\form\Form;
use pocketmine\player\Player;
use solo\swarp\SWarp;
use solo\swarp\Warp;
use solo\swarp\WarpException;

class WarpFunction implements Form{

	public function jsonSerialize() : array{
		return [
			"type" => "form",
			"title" => "§l워프 시스템",
			"content" => "§lOnixUI - 워프 시스템",
			"buttons" => array_map(function(Warp $warp) : array{
				return ["text" => "§d" . $warp->getName() . "§f 워프"];
			}, array_values(SWarp::getInstance()->getAllWarp()))
		];
	}

	public function handleResponse(Player $player, $data) : void{
		if($data !== null){
			/** @var Warp[] $warps */
			$warps = array_map(function(Warp $warp) : Warp{
				return $warp;
			}, array_values(SWarp::getInstance()->getAllWarp()));

			if(!isset($warps[$data])){
				OnixUtils::message($player, "워프가 존재하지 않습니다.");
				return;
			}

			if(!$player->hasPermission($warps[$data]->getPermission())){
				OnixUtils::message($player, "워프할 권한을 가지고있지 않습니다.");
				return;
			}

			$bool = true;
			try{
				$warps[$data]->warp($player);
			}catch(WarpException $e){
				OnixUtils::message($player, $e->getMessage());
				$bool = false;
			}
			if($bool)
				OnixUtils::message($player, "§d" . $warps[$data]->getName() . "§f 워프로 이동하였습니다.");
		}
	}
}