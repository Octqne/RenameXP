<?php

namespace Zqko\RepairRenameUI;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\item\Armor;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as T;

use jojoe77777\FormAPI\{SimpleForm, CustomForm};
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener {
   
    public function onEnable(){
    	$this->getServer()->getPluginManager()->registerEvents($this, $this);
  }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
    	if($sender instanceof Player){
        switch($command->getName()){
            case "rename":
                $this->rruiform($sender);
        }
        return true;
    }
    return false;
 }
public function rruiform(Player $sender){
    $form = new SimpleForm(function(Player $sender, ?int $data){
             if(!isset($data)) return;
			switch($data){
		
                        case 0:
                            $this->renamemenu($sender);
                            break;
                        case 1:
                            break;
      }
    });
    $form->setTitle("§a§lRename");
	$form->setContent("§bCustomize your item.");
    $form->addButton("§a§lRENAME");
    $form->addButton("§cEXIT");
    $form->sendToPlayer($sender);
 }
public function renamemenu(Player $sender){
    $form = new SimpleForm(function(Player $sender, ?int $data){
             if(!isset($data)) return;
			switch($data){
		
                        case 0:
                            $this->renamexp($sender);
                            break;
                        case 1:
                            $this->renamemoney($sender);
                            break;
                        case 2:
                            break;
      }
    });
    $form->setTitle(T::BOLD . T::GREEN . "§a§lRename");
    $form->addButton(T::YELLOW . "§aUSE XP");
    $form->addButton(T::AQUA . "§aUSE MONEY");
    $form->addButton(T::RED . "§cExit");
    $form->sendToPlayer($sender);
 }

public function renamemoney(Player $sender){
	    $f = new CustomForm(function(Player $sender, ?array $data){
             if(!isset($data)) return;
		 $item = $sender->getInventory()->getItemInHand();
		  if($item->getId() == 0) {
                    $sender->sendMessage(T::RED . "Hold item in hand!");
                    return;
                }
          $economy = EconomyAPI::getInstance();
          $mymoney = $economy->myMoney($sender);
          $rename = $this->getConfig()->get("price-rename");
          if($mymoney >= $rename){
	      $economy->reduceMoney($sender, $rename);
                $item->setCustomName("§r§f" . T::colorize($data[1]) . "§r§f~");
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage(T::GREEN . "Successfully changed item name to §r$data[1]");
                }else{
             $sender->sendMessage(T::RED . "You don't have enough money!");
             }
	    });
	   
          $economy = EconomyAPI::getInstance();
          $mymoney = $economy->myMoney($sender);
          $rename = $this->getConfig()->get("price-rename");
	  $f->setTitle(T::BOLD . T::YELLOW . "§a§lRename");
	  $f->addLabel("§6Rename cost: §a$500,00\n§6Your money: §a" . number_format($mymoney));
          $f->addInput(T::GREEN . "Type Custom Name:", "Name...");
	  $f->sendToPlayer($sender);
   }

public function renamexp(Player $sender){
	    $f = new CustomForm(function(Player $sender, ?array $data){
             if(!isset($data)) return;
                 $item = $sender->getInventory()->getItemInHand();
		  if($item->getId() == 0) {
                    $sender->sendMessage(T::RED . "Hold item in hand!");
                    return;
                }
          $xp = $this->getConfig()->get("xp-rename");
          $pxp = $sender->getXpLevel();
          if($pxp >= $xp){
	      $sender->subtractXpLevels($xp);
                $item->setCustomName("§r§f" . T::colorize($data[1]) . "§r§f~");
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage(T::GREEN . "Successfully changed item name to §r$data[1]");
                }else{
             $sender->sendMessage(T::RED . "You don't have enough EXP!");
             }
	    });
	   
          $xp = $this->getConfig()->get("xp-rename");
		  $pxp = $sender->getCurrentTotalXp();
	  $f->setTitle(T::BOLD . T::YELLOW . "§a§lRename");
	  $f->addLabel("§6Rename cost: §a5,000 XP\n§6Your EXP:§a " . number_format($pxp));
          $f->addInput(T::GREEN . "Type Custom Name:", "Name...");
	  $f->sendToPlayer($sender);
   }
}
