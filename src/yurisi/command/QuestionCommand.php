<?php

namespace yurisi\command;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

use yurisi\main;

class QuestionCommand extends Command{

	private $main;

	public function __construct(main $main){
		$this->main = $main;
		parent::__construct("question", "質問プラグインです", "/question [rate]");
	}

	public function execute(CommandSender $sender, string $label, array $args){
		if (isset($args[0])) {
			if ($args[0] == "rate") {
				foreach ($this->main->anser->getAll() as $key => $ansr) {
					$anser[]=$ansr;
				}
				$stat = array_count_values($anser);
				foreach($stat as $anserr=>$count){
					$sender->sendMessage("[{$this->main->plugin}]" . $anserr . "は" . floor($count/count($this->main->anser->getAll())*100) . "%");
				}
			} else {
				$sender->sendMessage("[{$this->main->plugin}] /question [rate]");
			}
		} else {
			$sender->sendMessage("[{$this->main->plugin}] /question [rate]");
		}
	}
}