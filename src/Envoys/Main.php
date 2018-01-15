<?php

namespace Envoys;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;
use pocketmine\utils\Textformat as C;

use Commands\Envoy;

class Main extends PluginBase{
    
    public function onEnable(){
        $this->getServer()->getCommandMap()->register("Envoy", new Envoy("Envoy", $this));
        $this->onConfig();
        $this->getLogger()->info(C::GREEN . "Enabled.");
    }

    public function onDisable(){
        $this->getLogger()->info(C::RED . "Disabled.");
    }

    public function onConfig(){
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
   }
}