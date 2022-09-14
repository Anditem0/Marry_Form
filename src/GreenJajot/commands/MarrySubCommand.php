<?php

namespace GreenJajot\commands;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\{ Command, CommandSender};
use GreenJajot\Marry;
use GreenJajot\form\FormManager;

class MarrySubCommand extends Command implements PluginOwned{
  
  private Marry $plugin;
  
  public function __construct(Marry $plugin){
    
		$this->plugin = $plugin;
		parent::__construct("kethon", "Mở Giao Điện Kho Cá Nhân", null, ["marry", "cuoinhau", "layvo", "laychong"]);
	}
	public function execute(CommandSender $sender, string $label, array $args){
		if($sender instanceof Player){
      $name = $sender->getName();
      $form = new FormManager($this->getOwningPlugin());
		#
		if(Marry::getInstance()->checkprofile($sender) == null){
        	$form->setProFile($sender);
		}elseif($this->plugin->profile->getNested("$name.lover") == ""){
		    $form->listProfile($sender);
		}else{
		    $form->MarryForm($sender);
		  }
		}
	}
	public function getOwningPlugin() : Marry {
		return $this->plugin;
	} 
}
