<?php
namespace GreenJajot\form;

use pocketmine\player\Player;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\CustomForm;
use onebone\coinapi\CoinAPI;
use pocketmine\world\Position;
use pocketmine\console\ConSoleCommandSender;
use GreenJajot\Marry;
#use string;

class FormManager{
  
  public function setProFile(Player $player){
    $form = new CustomForm(function(Player $player, $data = null){
      if($data === null){
        return false;
      }
      if($data[0] === null){ 
        $player->sendMessage("Bạn chưa Nhập Giới tính ");
        return false;
      }
      if($data[1] === null){
        $player->sendMessage("Ngày Tháng Năm Sinh");
        return false;
      }
      if($data[2] === null){
        $player->sendMessage("Bạn Chưa Nập Sở thích ");
        return false;
      }
      if(!is_numeric($data[1])){
        $player->sendMessage("Bạn chưa Nhập đúng yêu cầu1");
        return false;
      }
      if(is_numeric($data[2])){
        $player->sendMessage("Bạn chưa Nhập đúng yêu cầu3");
        return false;
      }
      $name = $player->getName();
      Marry::getInstance()->profile->set($player->getName(),[
          "name" => $name,
          "sex" => $data[0],
          "birthday" => $data[1],
          "interests" => $data[2],
          "status" => "alone",
          "lover" => ""
        ]);
      Marry::getInstance()->profile->save();
      $player->sendMessage("Bạn tạo xong thông tin cá Nhân ");
    });
    $form->setTitle("Thông Tin Cá Nhân");
    $form->addDropdown("Chọn Giới Tính",["Nam","Nữ","Khác"]);
    $form->addInput("Tuổi ","vd 15");
    $form->addInput("Sở Thích:", "vd: 2 ten");
    $form->sendToPlayer($player);
  }
  public function listProfile(Player $player){
    $form = new SimpleForm(function(Player $player, $data = null){
      if($data === null){
        return false;
      }
      $this->seeProFile($player, $data);
    });
    foreach(Marry::getInstance()->profile->getNested($player->getName()) as $names){
      $var = array(
        "NAME" => $names["name"],
        "AGE" => Marry::getInstance()->profile->getNested("$name.birthday")
         );
      $name = $player->getName();
      if(!$names["sex"] == Marry::getInstance()->getNested("$name.sex") && $names["status"] == "alone"){
 
 
        $form->addButton($this->replace(Marry::getInstance()->Config->getNested('Button'), $var));
      }
      
    }
    $form->setTitlesetTitle("Thông Tin người Khác Giới");
    $form->sendToPlayer($player);
  }
  public function seeProFile(Player $player, int $id):void{
    $array = Marry::getInstance()->profile->getNested($player->getName());
      $var = array(
        "NAME" => $array[$id]['name'],
        "SEX" => $array[$id]['sex'],
        "AGE" => $array[$id]["birthday"],
        "INTERESTS" =>  $array[$id]['interests'],
        "AMOUNT" => Marry::getInstance()->getConfig->get("wedding-agree"),
        );
    $form = new SimpleForm(function(Player $player, $data = null ) use ($var, $array, $id){
      if($data === null){
        $this->listProfile($player);
        return;
      }
      switch ($data) {
        case 0:
          Marry::getInstance()->reQuest($player, $arry[id]['name'],);
          break;
        
        case 1:
          $this->listProfile($player);
          break;
      }
    });
    $form->setTitlr("Xem Thông Tin Cá Nhân");
    $from->setContent(Marry::getInstance()->getConfig->getNested('Content-Profile'), $var);
    $form->addButton("đồng ý");
    $form->addButton("Không");
    $form->sendToPlayer($player);
  }
  public function MarryForm(Player $player){ 
    $form = new SimpleForm(function(Player $player, $data = null){
      if($data === null){ 
        return false;
      }
      switch ($data) {
        case 1:
          $this->sendInvLover($player);
          break;
        case 1:
          $this->teleToLover($player);
          break;
        case 1:
          $this->requestDivorce($player);
      }
    });
    $form->setTitle("§l§e★ §6Menu Kết Hôn§e ★");
    $form->setContent("Người Yêu Của Bạn là: ".Marry::getInstance()->profile->getNested("$player->getName().lover"));
    $form->addButton("xem Kho Đồ Người Yêu");
    $form->addButton("§d§lϟ§6 Dịch Chuyến Tới Người Yêu");
    $form->addButton("Ly Hôn");
    $form->sendToplayer($player);
    
    }
  public function reQuest(Player $player, $nameplayer1){
        
    $player1 = Marry::getInstance()->getServer()-> getPlayerByPrefix($nameplayer1);
    $move = Marry::getInstance()->move[$player1->getName()];
    $array = Marry::getInstance()->profile->get($player->getName());
    $var = array(
      "NAME" => $array["name"],
      "LOVER" => $nameplayer1,
      "INTERESTS" => $array["interests "],

      "DAY" => Marry::getInstance()->getBirthday($player, $data["D"]),
      "MONTH" => Marry::getInstance()->getBirthday($player, $data["M"]),
      "AGE" => $array["birthday"],
      "AMOUNT" => Marry::getInstance()->getConfig->get("wedding-agree"),
      "YEAR" => Marry::getInstance()->getBirthday($player, $data["Y"]),
      
          );
      if($move == true){
        if(EconomyAPI::getInstance()->myMoney() >= Marry::getInstance()->getConfig()->get("wedding-agree")){
          $form = new SimpleForm(function(Player $player, $data = null ) use ($array, $player1, $var){
                      if($data == null){
                        $player->sendMessage(Marry::getInstance()->getConfig()->getNested("message.refuse"));
                        return false;
                      }
                      switch ($data) {
                        case 0:
                          if(Marry::getInstance()->profile->getNested("$player1->getName().lover") == ""){
                            EconomyAPI::getInstance()->reduceMoney($player, Marry::getInstance()->getConfig()->get("wedding-agree"));
                            $player->sendMessage(Marry::getInstance()->getConfig()->getNested("message.lover-yes"), $var);
                            Marry::getInstance()->getServer()->broadcastMessage(
                              Marry::getInstance()->getConfig()->getNested("message.successful "), $var);
                            Marry::getInstance()->profile->setNested("$player->getName().lover", $player1->getName);
                            Marry::getInstance()->profile->setNested("$player1->getName().lover", $player->getName);
                            Marry::getInstance()->profile->setNested("$player->getName.status", "Marry");
                            Marry::getInstance()->profile->setNested("$player1->getName.status", "Marry");
                            Marry::getInstance()->profile->save();
                          }else {
                            $player->sendMessage(Marry::getInstance()->getConfig()->getNested("message.refuse"), $var);
                            $player1->sendMessage(Marry::getInstance()->getConfig->getNested("message.refuse-lover"), $var);
                          }
                          break;
                        case 1:
                          $player->sendMessage(Marry::getInstance()->getConfig()->getNested("message.refuse"), $var);
                            return false;
                          break;
                      }
                    });
                    $form->setTitle("§l§c♥︎§b Yêu Cầu Kết Hôn§c ♥︎");
                    $form->setConten($this->getConfig()->get("Content-ReQuest"), $var);
                    $form->addButton("Đồng Ý");
                    $form->addButton("Không");
                    $form->sendToPlayer($player1);
                  }else{
                    $player->sendMessage(Marry::getConfig()->getNested("message.nomoney"));
                  }
        }
      }
 
      
      
  public function sendInvLover(Player $player){
    $name1 = $player->getName();
    $name2 = Marry::getInstance()->getNested("$name1.lover");
    Marry::getInstance()->pp()->getUserDataMgr->setPermission($player, "invsee.command.invsee");
    $this->getServer()->dispatchCommand(new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage()), $player1."invsee". $name2);
    Marry::getInstance()->pp()->getUserDataMgr->unsetPermission($player, "invsee.command.invsee");
  }
  public function teleToLover(Player $player){
    $namelover = Marry::getInstance()->profile->getNested("$player->getName().lover");
    $player1 = Marry::getInstance()->getServer()-> getPlayerByPrefix($namelover);
    $x = $player1->getPosition()->getX();
    $y = $player1->getPosition()->getY();
    $z = $player1->getPosition()->getZ();
    $world = $player1->getPosition()->getWorld()->getFolderName();
    $worldname = $this->getServer()->getWorldManager()->getWorldByName($world);
    if(Marry::getInstance()->move[$player1->getName()] == true){
      $player->teleport(new Position($x, $y, $z, $nameworld));
      $playr->sendMessage(Marry::getInstance()->getConfig()->getNested("message.Teleport-To-Lover"));
      $playr1->sendMessage(Marry::getInstance()->getConfig()->getNested("message.Lover-Teleport"));
    }else {
      $this->sendMessage(Marry::getInstance()->getConfig->getNested("message.notp"));
    }
  }
  public function requestDivorce(Player $player){
    $array = Marry::getInstance()->profile->get($player->getName());
    $var = array(
      "NAME" => $arrayy["name"],
      "LOVERNAME" => $array["lover"]
      );
    
    $nameplayer1 = Marry::getInstance()->profile->getNested("$playr->getName().lover");
    
    $player1 = Marry::getInstance()->getServer()-> getPlayerByPrefix($nameplayer1);
    
    $move = Marry::getInstance()->move[$player1->getName()];
    if($move == true){
      $form = new SimpleForm(function(Player $player, $data = null) use ($player2, $var){
        if($data === null){
          $this->MarryForm($player);
          $player->sendMessage(Marry::getInstance()->getConfig()->getNested("message.refuse"));
        }
        switch ($data) {
          case 0:
            Marry::getinstance()->profile->setNested("$player->getName.lover", "");
            Marry::getinstance()->profile->setNested("$player1->getName.lover", "");
            Marry::getinstance()->profile->setNested("$player->getName.status", "alone");
            Marry::getinstance()->profile->setNested("$player->getName.status", "alone");
            Marry::getInstance()->getServer()->broadcastMessage(Marry::getInstance()->getConfig()->getNested("message.divorce "), $var);
            break;
          case 1: 
            $player->sendMessage(Marry::getInstance()->getConfig("message.refuse"));
            break;
        }
      });
      $form->setTitle("Menu Ly Hôn");
      $form->setConten(Marry::getInstance()->getConfig()->getNested("Content-divorce"), $var);
      $form->addButton("Đồng Ý");
      $form->addButton("Không");
      $form->sendToPlayer($player1);
      
    }else{
      $player->sendMessage(Marry::getInstance()->getConfig("message.lover-off"), $var);
    }
    
  }
  }
