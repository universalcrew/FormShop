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


use jojoe77777\FormAPI\SimpleForm;
use pocketmine\item\Item;
use pocketmine\Player;
use ru\universalcrew\formshop\Home;

class SellForm
{
    /**
     * @var Home
     */
    private $home;

    /**
     * @var Player
     */
    private $player;

    /**
     * Forms constructor.
     * @param Home $home
     * @param Player $player
     */
    function __construct(Home $home, Player $player)
    {
        $this->player = $player;
        $this->home = $home;
    }

    function mainSellForm() : void
    {
        $money = $this->getHome()->getEconomy()->myMoney($this->player);
        $form = $this->getHome()->getForm()->createSimpleForm(function (Player $player, $data) {
            if ($data === null ) return;
            $this->sellItemForm($player, $data);
        });
        $title = $this->getHome()->getProvider()->getMessage("sell.mainform.title");
        $form->setTitle($title);
        $text = $this->getHome()->getProvider()->getMessage("sell.mainform.content");
        $text = str_replace("%money%", $money, $text);
        $form->setContent($text);
        $items = $this->getHome()->getProvider()->getSellItems();
        foreach ($items as $item) {
            list($id, $damage, $price, $itemname, $image) = explode("-", $item);
            $string = $this->getHome()->getProvider()->getMessage("sell.mainform.button");
            $string = str_replace("%item_name%", $itemname, $string);
            $string = str_replace("%id%", $id, $string);
            $string = str_replace("%damage%", $damage, $string);
            $string = str_replace("%price%", $price, $string);
            $form->addButton($string, SimpleForm::IMAGE_TYPE_URL, $image);
        }
        $form->sendToPlayer($this->player);
    }

    private function sellItemForm(Player $player, int $index) : void
    {
        $count = 0;
        $item = $this->getHome()->getProvider()->getSellItem($index);
        list($id, $damage, $price, $itemname, $image) = explode("-", $item);
        $item = new Item($id, $damage);
        if (!$player->getInventory()->contains($item)){
            $text = $this->getHome()->getProvider()->getMessage("sell.sellform.noitem");
            $player->sendMessage($text);
            return;
        }
        $inv = $player->getInventory()->getContents();
        foreach ($inv as $value){
            if ($value->getId() == $id && $value->getDamage() == $damage) $count = $value->getCount();
        }
        $form = $this->getHome()->getForm()->createCustomForm(function (Player $player, $data) use ($item, $count, $index) {
            if ($data === null ) return;
            $this->sellForm($player, $data[0], $count, $item, $index);
        });
        $title = $this->getHome()->getProvider()->getMessage("sell.selectsellform.title");
        $form->setTitle($title);
        $text = $this->getHome()->getProvider()->getMessage("sell.selectsellform.slider");
        $form->addSlider($text, 1, $count, 1, 1);
        $form->sendToPlayer($player);
    }

    private function sellForm(Player $player, int $countSell, int $count, Item $item, int $index)
    {
        $money = $this->getHome()->getEconomy()->myMoney($player);
        $stringItem = $this->getHome()->getProvider()->getSellItem($index);
        list($id, $damage, $price, $itemname, $image) = explode("-", $stringItem);
        $fullprice = $countSell * $price;
        $form = $this->getHome()->getForm()->createSimpleForm(function (Player $player, $data) use ($item, $count, $countSell, $id, $damage, $itemname, $fullprice) {
            if ($data === null ) return;
            for ($i = 0; $i < $countSell; $i++) $player->getInventory()->removeItem($item);
            $this->getHome()->getSell()->sell($player, $fullprice, $countSell, $itemname);
        });
        $title = $this->getHome()->getProvider()->getMessage("sell.sellform.title");
        $form->setTitle($title);
        $string = $this->getHome()->getProvider()->getMessage("sell.sellform.content");
        $string = str_replace("%item_name%", $itemname, $string);
        $string = str_replace("%id%", $id, $string);
        $string = str_replace("%damage%", $damage, $string);
        $string = str_replace("%price%", $price, $string);
        $string = str_replace("%money%", $money, $string);
        $string = str_replace("%count%", $countSell, $string);
        $string = str_replace("%fullprice%", $fullprice, $string);
        $form->setContent($string);
        $form->addButton($this->getHome()->getProvider()->getMessage("sell.sellform.button"));
        $form->sendToPlayer($player);
    }

    /**
     * @return Home
     */
    function getHome() : Home
    {
        return $this->home;
    }
}