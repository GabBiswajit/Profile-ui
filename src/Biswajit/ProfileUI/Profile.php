<?php

namespace Biswajit\ProfileUI;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;
use jojoe77777\FormAPI\SimpleForm;
use davidglitch04\libEco\libEco;

class Profile extends PluginBase {

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "profile") {
            if (!$sender instanceof Player) {
                $sender->sendMessage(TF::RED . "Use this command in-game");
                return false;
            }
            $this->openProfileForm($sender);
        }
        return true;
    }

    public function openProfileForm(Player $player): void {
        $form = new SimpleForm(function (Player $player, ?int $data): void {
            if ($data !== null) {
                // NOOP
            }
        });

        libEco::myMoney($player,
            function(float $money) use ($player, $form): void {
                $name = $player->getName();
                $rank = $this->getRank($player);
                $expLevel = $player->getXpManager()->getXpLevel();
                $date = date("d/m/Y H:i:s");
                $ping = $this->getPing($player);
                $firstPlayedDate = $this->getFormattedFirstPlayedDate($player);
                $coords = $this->getCoords($player);

                $form->setTitle(TF::BOLD . TF::RED . "YOUR PROFILE");
                $content = "This is your profile on the server:\n\n" .
                TF::GRAY . $date . "\n" .
                TF::WHITE . "Name : " . TF::GREEN . $name . "\n" .
                TF::WHITE . "Rank : " . TF::GREEN . $rank . "\n" .
                TF::WHITE . "Money : " . TF::GREEN . $money . "\n" .
                TF::WHITE . "Ping : " . TF::GREEN . $ping . "\n" .
                TF::WHITE . "Experience : " . TF::GREEN . $expLevel . "\n" .
                TF::WHITE . $coords . "\n" .
                TF::WHITE . "First Join : " . TF::GREEN . $firstPlayedDate;

                $form->setContent($content);
                $form->addButton(TF::BOLD . TF::RED . "EXIT", 0, "textures/blocks/barrier");
                $form->sendToPlayer($player);
            });
    }

    private function getRank(Player $player): ?string {
        $purePerms = $this->getPlugin("PurePerms");
        $ranksystem = $this->getPlugin("RankSystem");

        if ($purePerms !== null) {
            $group = $purePerms->getUserDataMgr()->getGroup($player);
            return $group !== null ? $group->getName() : null;
        } elseif ($ranksystem !== null) {
            $session = $ranksystem->getSessionManager()->get($player);
            $rankNames = [];

            foreach ($session->getRanks() as $rank) {
                $rankNames[] = $rank->getName();
            }

            return implode(', ', $rankNames);
        }

        return null;
    }

    private function getPlugin(string $name) {
        return $this->getServer()->getPluginManager()->getPlugin($name);
    }

    private function getFormattedFirstPlayedDate(Player $player): string {
        $timestamp = (int)($player->getFirstPlayed() / 1000);
        $formattedDate = date("F j, Y H:i:s", $timestamp);
        return $formattedDate . " WIB";
    }

    private function getCoords(Player $player): string {
        $x = (int) $player->getPosition()->getX();
        $y = (int) $player->getPosition()->getY();
        $z = (int) $player->getPosition()->getZ();

        $position = TF::WHITE . "Position : " . TF::GREEN . $x . " " . $y . " " . $z . TF::RESET;

        return $position;
    }

    private function getPing(Player $player): ?int {
        $ping = $player->getNetworkSession()->getPing();
        return $ping;
    }

    private function getWorldName(Player $player) {
        $worldName = $player->getWorld()->getProvider()->getWorldData()->getName();
        return $worldName;
    }
}
