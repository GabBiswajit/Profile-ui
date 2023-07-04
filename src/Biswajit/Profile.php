<?php

namespace Biswajit;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use libs\jojoe77777\FormAPI\SimpleForm;

class Profile extends PluginBase {

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

        $p = $sender;
        $name = $p->getName();
        $rank = $this->getServer()->getPluginManager()->getPlugin("PurePerms")->getUserDataMgr()->getGroup($p)->getName();
        $eco = $this->getServer()->getPluginManager()->getPlugin("BedrockEconomy");
        $date = date("d/m/Y H:i:s");
        $money = $eco->balance($p);
        $ping = $p->getNetworkSession()->getPing();
        $world = $p->getWorld()->getProvider()->getWorldData()->getName();
        $x = $p->getPosition()->getX();
        $y = $p->getPosition()->getY();
        $z = $p->getPosition()->getZ();
        
        $form->setTitle("§l§cYOUR PROFILE");
        $form->setContent("§r§9This Is Your Profile On This Server:\n\n§7".$date."\n§fName : §a".$name."\n§fRank : §a".$rank."\n§fMoney : §a".$money."\n§fPing : §a".$ping."\n§fPosition : §a".$x." ".$y." ".$z."\n§fFirst Join : §a".date("F, j Y H:i:s", (int)($sender->getFirstPlayed() / 1000))." WIB");
        $form->addButton("§l§cEXIT",0, "textures/blocks/barrier");
        $form->sendToPlayer($sender);

        return $form;

    }

}
