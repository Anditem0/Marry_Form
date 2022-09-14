<?php

namespace GreenJajot;

use pocketmine\Plugin\PluginBase;
use pocketmine\Server;
use pocketmine\player\Player;
use GreenJajot\commands\MarrySubCommand;
use GreenJajot\form\FromManager;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;

use pocketmine\utils\Config;


class Marry extends PluginBase implements Listener{
    public static $instance;
    public $move = [];
  
    public static function getInstance() : self {
		return self::$instance;
	}
	
	public function onLoad() : void {
		self::$instance = $this;
	}
    public function onEnable():void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("/kethon", new MarrySubCommand($this));
        $this->profile = new Config($this->getDataFolder() . "profile.yml", Config::YAML);

        $this->pp = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
        $this->saveDefaultConfig();
    }
    public function onMove(PlayerMoveEvent $ev){
      $player = $ev->getPlayer();
      $name = $player->getName();
      $this ->move[$name] = true;
      if($ev->isCancelled()){
        $this ->move[$name] = false;
      }
    }
    public function onJoin(PlayerJoinEvent $ev){
        $player = $ev->getPlayer();
        $name = $player->getName();
        if(!$this->profile->exists($name) == ""){
          foreach($thi->getOnlinePlayers() as $playeronl){
            $world = $playeronl->getPosition()->getWorld()->getFolderName();
            if($this->profile->exists($playeronl->getName())){
              $this->profile->setNested("$name.world", $world);
              $this->profile->save();
            }
            
            foreach(Marry::getInstance()->profile->getNested($name) as $names){
              $var = array(
                "NAME" => $names['name'],
                "LOVERNAME" => $names['lover'],
                "WORLD" => $names('world')
                    );
              if($playeronl->getName() == $this->porfile->getNested("$name.lover")){
                $player->sendMessage($this->getConfig()->getNested("message.lover-onl"), $var);
                $playeronl->sendMessage($this->getConfig()->getNested("message.player-onl"), $var);
              }else{
                $player->sendMessage($this->getConfig()->getNested("message.lover-off"), $var);
              }
             }
            }
        }   
    }
    public function checkprofile(Player $player):?string{
      $profile1 = null;
      $profile1 = $this->profile->exists($player->getName());
      return $profile1;
    }

    public function getBirthday($player){
      $name = $player->getName();
      $Birthday = $this->profile->getNested("$name.birthday");
      $data = explode(" ", $Birthday);
      return ["D" => $data[0],
               "M" => $data[1],
               "Y" => $data[2]
               ];
    }
}
