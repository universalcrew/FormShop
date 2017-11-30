<?php

namespace ru\universalcrew\formshop\commands;

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
use ru\universalcrew\formshop\Home;

class ShopCommand extends Command implements PluginIdentifiableCommand
{
    /**
     * @var Home
     */
    private $home;

    /**
     * ShopCommand constructor.
     * @param Home $home
     */
    public function __construct(Home $home)
    {
        parent::__construct("shop", "Магазин", "shop", []);
        $this->setPermission("ru.universalcrew.formshop.shop");
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
        if (!$sender instanceof Player) return false;
        if (!$this->testPermissionSilent($sender)) {
            $sender->sendMessage($this->getHome()->getProvider()->getMessage("no_permission"));
            return false;
        }
        $this->getHome()->getForms()->mainShopForm($sender);
        return true;
    }

    /**
     * @return Plugin
     */
    function getPlugin(): Plugin
    {
        return $this->home;
    }

    /**
     * @return Home
     */
    function getHome(): Home
    {
        return $this->home;
    }
}