<?php

namespace Commands;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

use pocketmine\math\Vector3;

use pocketmine\block\Block;
use pocketmine\item\Item;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

use pocketmine\tile\Chest;
use pocketmine\tile\Tile;

use pocketmine\utils\TextFormat as C;

use Envoys\Main;

class Envoy extends PluginCommand{

    public function __construct($name, Main $plugin){
        parent::__construct($name, $plugin);
        $this->setDescription("Spawn Item in chest.");
        $this->setAliases(["envoy"]);
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
        $x1 = $this->getPlugin()->cfg->get("Coordsx");
        $y2 = $this->getPlugin()->cfg->get("Coordsy");
        $z3 = $this->getPlugin()->cfg->get("Coordsz");
        $chestname = $this->getPlugin()->cfg->get("Chest-Name");
        $world = $this->getPlugin()->cfg->get("Level");
        $cfg = $this->getPlugin()->cfg;
        $items = $cfg->get("items");
        $item = $items[array_rand($items)];
        $values = explode(":", $item);
        $level = $this->getPlugin()->getServer()->getLevelByName("$world");
        $level->setBlock(new Vector3($x1, $y2, $z3), new Block(54, 0));
        $nbt = new CompoundTag(" ", [
            new ListTag("Items", []),
            new StringTag("id", Tile::CHEST),
            new StringTag("CustomName", "$chestname"),
            new IntTag("x", $x1),
            new IntTag("y", $y2),
            new IntTag("z", $z3)
        ]);
        $nbt->Items->setTagType(NBT::TAG_Compound);
        $chest = Tile::createTile("Chest", $level, $nbt);
        $level->addTile($chest);
        $inventory = $chest->getInventory();
        $inventory->addItem(Item::get($values[0], $values[1], $values[2])->setCustomName($values[3]));
        $spawnedmsg = $this->getPlugin()->cfg->get("Spawned-Message");
        $spawnedmsg = str_replace("{x}", $x1, $spawnedmsg);
        $spawnedmsg = str_replace("{y}", $y2, $spawnedmsg);
        $spawnedmsg = str_replace("{z}", $z3, $spawnedmsg);
        $this->getPlugin()->getServer()->broadcastMessage("$spawnedmsg");
        return true;
    }
}