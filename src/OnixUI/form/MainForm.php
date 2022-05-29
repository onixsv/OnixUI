<?php
declare(strict_types=1);

namespace OnixUI\form;

use alvin0319\GuildAPI\form\hasGuild\GuildMainForm as HasGuildForm;
use alvin0319\GuildAPI\form\noGuild\GuildMainForm as NoGuildForm;
use alvin0319\GuildAPI\GuildAPI;
use alvin0319\Jewelry\form\JewelryMainForm;
use alvin0319\Stat\Stat;
use OnixUI\form\functions\IslandSelectForm;
use OnixUI\form\functions\SpeakerFunction;
use OnixUI\form\functions\UserCommandFunction;
use OnixUI\form\functions\WarpFunction;
use OnixUtils\OnixUtils;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\form\Form;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\Server;
use function implode;

class MainForm implements Form{
	//https://minecraft.gamepedia.com/File:Nether_Star.png
	//https://vignette.wikia.nocookie.net/minecraft/images/a/a9/NetherStarNew.png

	public function jsonSerialize() : array{
		$ops = [];
		foreach(Server::getInstance()->getOnlinePlayers() as $player){
			if($player->hasPermission(DefaultPermissions::ROOT_OPERATOR))
				$ops[] = "§b{$player->getName()}";
		}
		return [
			"type" => "form",
			"title" => "§lOnixUI - Master",
			"content" => "OnixUI - Master\n\n접속중인 관리자 목록:\n" . implode("§d, ", $ops),
			"buttons" => [
				["text" => "세션 종료하기"],
				[
					"text" => "§l* 보석 시스템\n보석 시스템을 확인 합니다.",
					"image" => [
						"type" => "url",
						"data" => "https://vignette.wikia.nocookie.net/minecraft/images/a/a9/NetherStarNew.png"
					]
				],
				[
					"text" => "§l* 크기 시스템\n크기 시스템을 확인 합니다.",
					"image" => [
						"type" => "url",
						"data" => "https://vignette.wikia.nocookie.net/minecraft/images/a/a9/NetherStarNew.png"
					]
				],
				[
					"text" => "§l* 길드 시스템\n길드 시스템을 확인 합니다.",
					"image" => [
						"type" => "url",
						"data" => "https://vignette.wikia.nocookie.net/minecraft/images/a/a9/NetherStarNew.png"
					]
				],
				[
					"text" => "§l* 섬 목록\n섬 목록을 확인 합니다.",
					"image" => [
						"type" => "url",
						"data" => "https://vignette.wikia.nocookie.net/minecraft/images/a/a9/NetherStarNew.png"
					]
				],
				[
					"text" => "§l* 워프 목록\n워프 목록을 확인 합니다.",
					"image" => [
						"type" => "url",
						"data" => "https://vignette.wikia.nocookie.net/minecraft/images/a/a9/NetherStarNew.png"
					]
				],
				[
					"text" => "§l* 효과 받기\n효과를 부여받습니다.",
					"image" => [
						"type" => "url",
						"data" => "https://vignette.wikia.nocookie.net/minecraft/images/a/a9/NetherStarNew.png"
					]
				],
				[
					"text" => "§l* 확성기\n확성기를 사용합니다.",
					"image" => [
						"type" => "url",
						"data" => "https://vignette.wikia.nocookie.net/minecraft/images/a/a9/NetherStarNew.png"
					]
				],
				[
					"text" => "§l* 판매 전체\n판매 전체를 합니다.",
					"image" => [
						"type" => "url",
						"data" => "https://vignette.wikia.nocookie.net/minecraft/images/a/a9/NetherStarNew.png"
					]
				],
				[
					"text" => "§l* 단축 명령어\n내가 지정해둔 단축 명령어를 봅니다.",
					"image" => [
						"type" => "url",
						"data" => "https://vignette.wikia.nocookie.net/minecraft/images/a/a9/NetherStarNew.png"
					]
				],
				[
					"text" => "§l* 스탯\n스탯 창을 엽니다.",
					"image" => [
						"type" => "url",
						"data" => "https://vignette.wikia.nocookie.net/minecraft/images/a/a9/NetherStarNew.png"
					]
				]
			]
		];
	}

	public function handleResponse(Player $player, $data) : void{
		switch((int) $data){
			case 0:
				// 나가기
				break;
			case 1:
				$player->sendForm(new JewelryMainForm($player));
				break;
			case 2:
				//$player->sendForm(new SizeForm($player));
				OnixUtils::message($player, "크기 기능은 아직 사용할 수 없습니다.");
				break;
			case 3:
				if(GuildAPI::getInstance()->hasGuild($player)){
					$player->sendForm(new HasGuildForm($player, GuildAPI::getInstance()->getGuildByPlayer($player)));
				}else{
					$player->sendForm(new NoGuildForm());
				}
				break;
			case 4:
				$player->sendForm(new IslandSelectForm());
				break;
			case 5:
				$player->sendForm(new WarpFunction());
				break;
			case 6:
				$player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 999999, 1, false));
				$player->getEffects()->add(new EffectInstance(VanillaEffects::WATER_BREATHING(), 999999, 3, false));
				OnixUtils::message($player, "효과를 지급받았습니다.");
				break;
			case 7:
				$player->sendForm(new SpeakerFunction());
				break;
			case 8:
				$ev = new PlayerCommandPreprocessEvent($player, "/판매 전체");
				$ev->call();
				if(!$ev->isCancelled()){
					Server::getInstance()->dispatchCommand($player, "판매 전체");
				}
				break;
			case 9:
				$player->sendForm(new UserCommandFunction($player));
				break;
			case 10:
				Stat::getInstance()->sendInv($player);
				break;
		}
	}
}
