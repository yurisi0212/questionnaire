<?php

namespace yurisi;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;



use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class EventListener implements Listener{

   private $main;

   public $itiji;

   public function __construct(main $main){
            $this->main = $main;
   }

   public function onJoin(PlayerJoinEvent $event){
	$player=$event->getPlayer();
	$name=$player->getName();
	$anser=$this->main->question->get("anser");
	if(!$this->main->anser->exists($name)){
		$pk = new ModalFormRequestPacket();
		$pk->formId = $this->main->id;
		$formdata["type"] = "custom_form";
		$formdata["title"] = "§d{$this->main->plugin}";
		$formdata["content"][] = array(
			"type" => "label",
			"text" => $this->main->question->get("question")."\n\n",
		);
		$formdata["content"][] = array(
			"type" => "dropdown",
			"text" => "答え",
		);
		$formdata["content"][1]["options"][] ="ここから選択";
		$this->itiji[$name][] = "";
		$ansers=explode(",", $anser);
		foreach ($ansers as $ansr) {
			$this->itiji[$name][] = $ansr;
			$formdata["content"][1]["options"][] = $ansr;
		}
	$pk->formData = json_encode($formdata);
	$player->dataPacket($pk);
	}
   }

   public function onDataPacketReceive(DataPacketReceiveEvent $event){
	$player = $event->getPlayer();
	$name = $player->getName();
	$packet = $event->getPacket();
	$anser=$this->main->question->get("anser");
	if ($packet->getName() == "ModalFormResponsePacket") {
		$data = $packet->formData;
		$result = json_decode($data, true);
		if($packet->formId==$this->main->id){
			if($result[1]==null){
				$pk = new ModalFormRequestPacket();
				$pk->formId = $this->main->id;
				$formdata["type"] = "custom_form";
				$formdata["title"] = "§d{$this->main->plugin}";
				$formdata["content"][] = array(
					"type" => "label",
					"text" => $this->main->question->get("question")."\n\n",
				);
				$formdata["content"][] = array(
					"type" => "dropdown",
					"text" => "答え",
				);
				$ansers=explode(",", $anser);
				$formdata["content"][1]["options"][] ="ここから選択";
				$this->itiji[$name][] = "";
				foreach ($ansers as $ansr) {
					$this->itiji[$name][] = $ansr;
					$formdata["content"][1]["options"][] = $ansr;
				}
				$pk->formData = json_encode($formdata);
				$player->dataPacket($pk);
				return false;
			}
			$player->sendMessage("[{$this->main->plugin}]ご協力ありがとうございました！");
			$anser=$this->itiji[$name][$result[1]];
			$this->main->anser->set($name,$anser);
			$this->main->anser->save();
		}
	}
   }
}