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

class Forms
{
    /**
     * @var Home
     */
    private $home;

    /**
     * Forms constructor.
     * @param Home $home
     */
    function __construct(Home $home)
    {
        $this->home = $home;
    }

    /**
     * @param Player $player
     */
    function selectForm(Player $player) : void
    {
        $money = $this->getHome()->getEconomy()->myMoney($player);
        $form = $this->getHome()->getForm()->createSimpleForm(function (Player $player, $data) {
            $buyForm = new BuyForm($this->home, $player);
            $sellForm = new SellForm($this->home, $player);
            if ($data === null) return;
            if ($data === 0) $buyForm->mainBuyForm();
            if ($data === 1) $sellForm->mainSellForm();
        });
        $title = $this->getHome()->getProvider()->getMessage("selectform.title");
        $form->setTitle($title);
        $text = $this->getHome()->getProvider()->getMessage("selectform.content");
        $text = str_replace("%money%", $money, $text);
        $form->setContent($text);
        $textButtonBuy = $this->getHome()->getProvider()->getMessage("selectform.button.buy");
        $image = $this->getHome()->getProvider()->getMessage("selectform.button.buy.image");
        $form->addButton($textButtonBuy, SimpleForm::IMAGE_TYPE_URL, $image);
        $textButtonSell = $this->getHome()->getProvider()->getMessage("selectform.button.sell");
        $image = $this->getHome()->getProvider()->getMessage("selectform.button.sell.image");
        $form->addButton($textButtonSell, SimpleForm::IMAGE_TYPE_URL, $image);
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