nes (22 sloc)  694 Bytes

<?php
namespace GreenJajot\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\player\Player;
use GreenJajot\Marry;

class worldtask extends Task{

    public function plugin(){
        $plugin = Server::getInstance()->getPluginManager()->getPlugin("Marry");
        return $plugin;
    }
    public function onRun():void{
      foreach(Server::getInstance()->getOnlinePlayers() as $player){
        $time = $time++;
    
        if($time == 60){
          NangCap::getInstance()->autoreset->save();
          NangCap::getInstance()->getConfig()->setNested("ResetTime", $time);
        }
      }
      
      
    }
}