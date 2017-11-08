<?php

namespace ru\universalcrew\commands;

/**
 *  _    _       _                          _  ____
 * | |  | |_ __ (_)_    _____ _ ______ __ _| |/ ___\_ _______      __
 * | |  | | '_ \| | \  / / _ \ '_/ __// _' | / /   | '_/ _ \ \    / /
 * | |__| | | | | |\ \/ /  __/ | \__ \ (_) | \ \___| ||  __/\ \/\/ /
 *  \____/|_| |_|_| \__/ \___|_| /___/\__,_|_|\____/_| \___/ \_/\_/
 *
 * @author egr7v8
 * @link   https://t.me/egr7v8
 *
 */

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use ru\universalcrew\Home;

class ShopCommand extends Command implements PluginIdentifiableCommand
{

    private $home;

    public function __construct(Home $home)
    {
        parent::__construct("shop", "Магазин", "shop", []);
        $this->home = $home;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param string[] $args
     *
     * @return mixed
     */
    function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) $this->getHome()->getForms()->mainShopForm($sender);
        return;
    }

    function getPlugin(): Plugin
    {
        return $this->home;
    }

    function getHome(): Home
    {
        return $this->home;
    }
}