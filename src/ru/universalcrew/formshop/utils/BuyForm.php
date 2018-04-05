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

class BuyForm
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

    function mainBuyForm() : void
    {
        $money = $this->getHome()->getEconomy()->myMoney($this->player);
        $form = $this->getHome()->getForm()->createSimpleForm(function (Player $player, $data) {
            if ($data === null ) return;
            $this->itemsForm($data, $player);
        });
        $title = $this->getHome()->getProvider()->getMessage("buy.mainform.title");
        $form->setTitle($title);
        $text = $this->getHome()->getProvider()->getMessage("buy.mainform.content");
        $text = str_replace("%money%", $money, $text);
        $form->setContent($text);
        $categories = $this->getHome()->getProvider()->getShopsCategories();
        foreach ($categories as $index => $name) {
            $text = $this->getHome()->getProvider()->getMessage("buy.mainform.button");
            $text = str_replace("%category_name%", $name, $text);
            $image = $this->getHome()->getProvider()->getImageCategory($index);
            $form->addButton($text, SimpleForm::IMAGE_TYPE_URL, $image);
        }
        $form->sendToPlayer($this->player);
    }

    private function itemsForm(int $category, Player $player) : void
    {
        if ($this->getHome()->getProvider()->isCategoryItems($category)) {
            if ($player instanceof Player) {
                $money = $this->getHome()->getEconomy()->myMoney($player);
                $form = $this->getHome()->getForm()->createSimpleForm(function (Player $player, $data) use ($category) {
                    if (!($data === null )) {
                        if (count($this->getHome()->getProvider()->getCategoryItems($category)) <= $data) $this->mainBuyForm();
                        else $this->selectCountItem($category, $data, $player);
                    }
                });
                $title = $this->getHome()->getProvider()->getMessage("buy.itemsform.title");
                $form->setTitle($title);
                $text = $this->getHome()->getProvider()->getMessage("buy.itemsform.content");
                $text = str_replace("%money%", $money, $text);
                $form->setContent($text);
                $items = $this->getHome()->getProvider()->getCategoryItems($category);
                foreach ($items as $item) {
                    list($id, $damage, $price, $itemname, $image) = explode("-", $item);
                    $string = $this->getHome()->getProvider()->getMessage("buy.itemsform.button");
                    $string = str_replace("%item_name%", $itemname, $string);
                    $string = str_replace("%id%", $id, $string);
                    $string = str_replace("%damage%", $damage, $string);
                    $string = str_replace("%price%", $price, $string);
                    $form->addButton($string, SimpleForm::IMAGE_TYPE_URL, $image);
                }
                $form->addButton($this->getHome()->getProvider()->getMessage("mainformreturn"));
                $form->sendToPlayer($player);
            }
        } else {
            $player->sendTip($this->getHome()->getProvider()->getMessage("buy.itemsform.no_content"));
            $this->mainBuyForm();
        }
    }

    /**
     * @param string $category
     * @param int $index
     * @param Player $player
     */
    private function selectCountItem(string $category, int $index, Player $player) : void
    {
        if ($player instanceof Player) {
            $money = $this->getHome()->getEconomy()->myMoney($player);
            $string_item = $this->getHome()->getProvider()->getStringItem($category, $index);
            list($id, $damage, $price, $itemname) = explode("-", $string_item);
            $string = $this->getHome()->getProvider()->getMessage("buy.selectcountform.title");
            $string = str_replace("%item_name%", $itemname, $string);
            $string = str_replace("%id%", $id, $string);
            $string = str_replace("%damage%", $damage, $string);
            $string = str_replace("%price%", $price, $string);
            $string = str_replace("%money%", $money, $string);
            $form = $this->getHome()->getForm()->createCustomForm(function (Player $player, $data) use ($string_item) {
                if (!($data === null )) {
                    $this->buyForm($player, $data, $string_item);
                }
            });
            $form->setTitle($string);
            $form->addSlider($this->getHome()->getProvider()->getMessage("buy.selectcountform.slider_name"), 1, 64, 1, 1);
            $form->sendToPlayer($player);
        }
    }

    /**
     * @param Player $player
     * @param array $data
     * @param string $string_item
     */
    private function buyForm(Player $player, array $data, string $string_item) : void
    {
        if ($player instanceof Player) {
            $money = $this->getHome()->getEconomy()->myMoney($player);
            list($id, $damage, $price, $itemname) = explode("-", $string_item);
            $count = $data[0];
            $fullprice = $count * $price;
            $item = new Item($id, $damage);
            $string = $this->getHome()->getProvider()->getMessage("buy.buyform.content");
            $string = str_replace("%item_name%", $itemname, $string);
            $string = str_replace("%id%", $id, $string);
            $string = str_replace("%damage%", $damage, $string);
            $string = str_replace("%price%", $price, $string);
            $string = str_replace("%money%", $money, $string);
            $string = str_replace("%count%", $count, $string);
            $string = str_replace("%fullprice%", $fullprice, $string);
            $form = $this->getHome()->getForm()->createSimpleForm(function (Player $player, $data) use ($money, $fullprice, $item, $count, $itemname) {
                if (!($data === null )) {
                    if ($fullprice > $money) $this->mainBuyForm();
                    else {
                        switch ($data) {
                            case 0:
                                $this->getHome()->getPay()->pay($player, $fullprice, $item, $count, $itemname);
                                break;
                            case 1:
                                $this->mainBuyForm();
                                break;
                        }
                    }
                }

            });
            $form->setTitle($this->getHome()->getProvider()->getMessage("buy.buyform.title"));
            if ($fullprice > $money) {
                $text = $this->getHome()->getProvider()->getMessage("buy.buyform.no_money");
                $text = str_replace("%fullprice%", $fullprice, $text);
                $form->setContent($text);
            } else {
                $form->setContent($string);
                $form->addButton($this->getHome()->getProvider()->getMessage("buy.buyform.button"));
            }
            $form->addButton($this->getHome()->getProvider()->getMessage("mainformreturn"));
            $form->sendToPlayer($player);
        }
    }

    /**
     * @return Home
     */
    function getHome() : Home
    {
        return $this->home;
    }

}