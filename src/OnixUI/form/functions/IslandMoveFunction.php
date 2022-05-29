<?php
declare(strict_types=1);

namespace OnixUI\form\functions;

use alvin0319\Area\area\Area;
use alvin0319\Area\AreaLoader;
use OnixUtils\OnixUtils;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\Server;
use function array_map;

class IslandMoveFunction implements Form{

	/** @var Player */
	private Player $player;

	public function __construct(Player $player){
		$this->player = $player;
	}

	public function jsonSerialize() : array{
		return [
			"type" => "form",
			"title" => "§l섬 이동 시스템",
			"content" => "§lOnixUI - 섬 이동",
			"buttons" => array_map(function(int $id) : array{
				return ["text" => "§d" . $id . "§f번 섬"];
			}, array_map(function(Area $area) : int{
				return $area->getId();
			}, AreaLoader::getInstance()->getAreaManager()->getOwnAreas($this->player, "island", true)))
		];
	}

	public function handleResponse(Player $player, $data) : void{
		if($data !== null){
			$islands = [];
			foreach(AreaLoader::getInstance()->getAreaManager()->getOwnAreas($player->getName(), "island", true) as $area){
				$islands[] = $area->getId();
			}
			if(!isset($islands[$data])){
				OnixUtils::message($player, "해당 번호의 섬이 존재하지 않습니다.");
				return;
			}

			AreaLoader::getInstance()->getAreaManager()->getAreaById($islands[$data], "island")->moveTo($player);
			OnixUtils::message($player, "§d" . $islands[$data] . "§f번 섬으로 이동하였습니다.");
			Server::getInstance()->getLogger()->info("[Logger] {$player->getName()}님 {$islands[$data]}번 섬으로 이동.");
		}
	}
}