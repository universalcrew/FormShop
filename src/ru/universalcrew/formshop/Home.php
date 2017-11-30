<?php

namespace ru\universalcrew\formshop;

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

use jojoe77777\FormAPI\FormAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\plugin\PluginBase;
use ru\universalcrew\formshop\commands\ShopCommand;
use ru\universalcrew\formshop\utils\Forms;
use ru\universalcrew\formshop\utils\Pay;
use ru\universalcrew\formshop\utils\Provider;

class Home extends PluginBase
{
    /**
     * @var EconomyAPI
     */
    private $economyapi;

    /**
     * @var FormAPI
     */
    private $formapi;

    /**
     * @var Provider
     */
    private $provider;

    /**
     * @var Pay
     */
    private $pay;

    /**
     * @var Forms
     */
    private $forms;

    function onEnable()
    {
        $this->getLogger()->info($this->getDescription()->getName() . " включен.");
        $this->loadPlugins();
        $this->loadClass();
        $this->initCommands();
    }

    private function loadClass()
    {
        $this->provider = new Provider($this);
        $this->pay = new Pay($this);
        $this->forms = new Forms($this);
    }

    private function loadPlugins()
    {
        if ($this->getServer()->getPluginManager()->getPlugin("EconomyAPI") === null ||
            $this->getServer()->getPluginManager()->getPlugin("FormAPI") === null) {
            $this->getLogger()->critical('Дополнительные плагины не установлены. FormShop выключается...');
            $this->getServer()->getPluginManager()->disablePlugin($this);
        } else {
            $this->economyapi = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
            $this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        }
    }

    private function initCommands()
    {
        $list = [
            new ShopCommand($this)
        ];
        foreach ($list as $class) $this->getServer()->getCommandMap()->register($this->getDescription()->getName(), $class);
    }

    /**
     * @return Home
     */
    function getHome()
    {
        return $this;
    }

    /**
     * @return EconomyAPI
     */
    function getEconomy()
    {
        return $this->economyapi;
    }

    /**
     * @return FormAPI
     */
    function getForm()
    {
        return $this->formapi;
    }

    /**
     * @return Provider
     */
    function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return Pay
     */
    function getPay()
    {
        return $this->pay;
    }

    /**
     * @return Forms
     */
    function getForms()
    {
        return $this->forms;
    }

    function onDisable()
    {
        $this->getLogger()->info($this->getDescription()->getName() . " выключен.");
    }

}