<?php

namespace yurisi;

use pocketmine\Server;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use pocketmine\event\Listener;

use yurisi\command\QuestionCommand;

class main extends PluginBase implements Listener{

   public $plugin= "質問" ;

   public $id;

   public function onEnable() {
	$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
	$this->getServer()->getCommandMap()->register("question", new QuestionCommand($this));
	$this->getLogger()->info("{$this->plugin}を起動しました。");
	$this->id=mt_rand(0,99999);
	if(!file_exists($this->getDataFolder())){
	mkdir($this->getDataFolder(), 0777);
	}
	$this->question = new Config($this->getDataFolder() . "question.yml", Config::YAML ,array(
		"question"=>"このサーバーは面白いですか？",
		"anser"=>"はい,いいえ,それほどでもない,そうでもないですね,微妙",
	));
	$this->anser = new Config($this->getDataFolder() . "anser.yml", Config::YAML , array());
   }
   public function onDisable() {
		$this->getLogger()->info("{$this->plugin}を正常に終了しました。");
   }
}