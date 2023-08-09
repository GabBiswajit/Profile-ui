<?php

namespace Biswajit\ProfileUI;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use jojoe77777\FormAPI\SimpleForm;
use davidglitch04\libEco\libEco;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Profile extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
    switch($cmd->getName()){
      case "profile":
        if($sender instanceof Player){
          $this->openMyForm($sender);
        }else{
        	
          $sender->sendMessage("§cUse this command in game");
        }
      break;
    }
    return true;
  } 

    public function openMyForm($sender){
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;

            if($result === null){
                return true;
            }

            switch($result){
                case 0:              

                break;

             }

        });
        $player = $sender;
        $name = $player->getName();
        $rank = $this->getServer()->getPluginManager()->getPlugin("PurePerms")->getUserDataMgr()->getGroup($player)->getName();
        $eco = libEco::myMoney($player, static function(float $money) : void {
	    var_dump($money);
         });
        $date = date("d/m/Y H:i:s");
        $expLevel = $player->getXpLevel();
        $ping = $player->getNetworkSession()->getPing();
        $world = $player->getWorld()->getProvider()->getWorldData()->getName();
        $x = $player->getPosition()->getX();
        $y = $player->getPosition()->getY();
        $z = $player->getPosition()->getZ();
        
        $form->setTitle("§l§cYOUR PROFILE");
        $form->setContent("§r§9This Is Your Profile On This Server:\n\n§7".$date."\n§fName : §a".$name."\n§fRank : §a".$rank."\n§fMoney : §a".$balance."\n§fPing : §a".$ping."\n§fExperience : §a".$expLevel."\n§fPosition : §a".$x." ".$y." ".$z."\n§fFirst Join : §a".date("F, j Y H:i:s", (int)($sender->getFirstPlayed() / 1000))." WIB");
        $form->addButton("§l§cEXIT",0, "textures/blocks/barrier");
        $form->sendToPlayer($sender);

        return $form;

    }

}
