<?php

namespace ru\universalcrew\formshop\utils;

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

use pocketmine\item\Item;
use pocketmine\Player;
use ru\universalcrew\formshop\Home;

class Sell
{
    /**
     * @var Home
     */
    private $home;

    /**
     * Sell constructor.
     * @param Home $home
     */
    function __construct(Home $home)
    {
        $this->home = $home;
    }

    /**
     * @param Player $player
     * @param int $fullprice
     * @param int $count
     * @param string $itemname
     */
    public function sell(Player $player, int $fullprice, int $count, string $itemname) : void
    {
        $this->getHome()->getEconomy()->addMoney($player, $fullprice);
        $text = $this->getHome()->getProvider()->getMessage("sell");
        $text = str_replace("%item_name%", $itemname, $text);
        $text = str_replace("%count%", $count, $text);
        $player->sendMessage($text);
    }

    /**
     * @return Home
     */
    function getHome() : Home
    {
        return $this->home;
    }

}