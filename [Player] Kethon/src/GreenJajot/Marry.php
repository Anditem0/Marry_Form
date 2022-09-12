<?php

namespace GreenJajot;

use pocketmine\Plugin\PluginBase;
use pocketmine\Server;
use pocketmine\player\Player;
use GreenJajot\commands\MarrySubComamnd;
use GreenJajot\form\FromManager;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\utils\Config;

class Marry extends PluginBase{
    public static $instance;
  
    public static function getInstance() : self {
		return self::$instance;
	}
	
	public function onLoad() : void {
		self::$instance = $this;
	}
    public function onEnable():void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("/kethon", new MarrySubCommand($this));
        $this->proflie = new Config($this->getDataFolder() . "profile.yml", Config::YAML);
        $this->marry = new Config($this->getDataFolder() . "marry.yml", Config::YAML);
        $this->saveDefaultConfig();
    }
    public function onJoin(PlayerJoinEvent $ev){
        $player = $ev->getPlayer();
        $name = $player->getName();
        if(!$this->marry->exists($name)){
            $player->sendMessage("Bạn Đã đang thất tình à :> lew lew cái đồ fa");
        }else{
            foreach($this->getOnlinePlayers() as $playeronl){
                foreach(Marry::getInstance()->marry->getNested($name) as $names){
                    $var = array(
                    "NAME" => $names['name'],
                    "LOVER" => $names['lover']
                    "WORLD" => $names['world']
                    );
                if($playeronl == $this->marry->getNested("$name.lover")){
                    $player->sendMessage($this->getConfig()->getNested("message.lover-onl"), $var);
                    $playeronl->sendMessage($this->getConfig()->getNested("message.lover-onl"), $var);
                }
            }
            }
        }   
    }
}