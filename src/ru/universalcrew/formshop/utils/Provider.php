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

use pocketmine\utils\Config;
use ru\universalcrew\formshop\Home;

class Provider
{
    /**
     * @var Config
     */
    public $buy;

    /**
     * @var Config
     */
    private $messages;

    /**
     * @var Home
     */
    private $home;

    /**
     * @var Config
     */
    private $sell;

    /**
     * Provider constructor.
     * @param Home $home
     */
    function __construct(Home $home)
    {
        $this->home = $home;
        if (!is_file($this->getHome()->getDataFolder() . 'buy.yml') ||
            !is_file($this->getHome()->getDataFolder() . 'sell.yml') ||
            !is_file($this->getHome()->getDataFolder() . 'messages.yml')) {
            @mkdir($this->getHome()->getDataFolder());
            $this->getHome()->saveResource('buy.yml');
            $this->getHome()->saveResource('sell.yml');
            $this->getHome()->saveResource('messages.yml');
        }
        $this->buy = new Config($this->getHome()->getDataFolder() . 'buy.yml', Config::YAML, []);
        $this->buy->reload();
        $this->sell = new Config($this->getHome()->getDataFolder() . 'sell.yml', Config::YAML, []);
        $this->sell->reload();
        $this->messages = new Config($this->getHome()->getDataFolder() . 'messages.yml', Config::YAML, []);
        $this->messages->reload();
    }

    /**
     * @param string $message
     * @return string
     */
    function getMessage(string $message)
    {
        return $this->messages->get($message);
    }

    /**
     * @return Config
     */
    function getBuy() : Config
    {
        return $this->buy;
    }

    /**
     * @return Config
     */
    function getSell() : Config
    {
        return $this->sell;
    }

    /**
     * @return array
     */
    function getShops() : array
    {
        return $this->getBuy()->getAll();
    }

    /**
     * @param string $name
     * @return array
     */
    function getCategory(string $name) : array
    {
        return $this->getShops()[$name];
    }

    /**
     * @param int $index
     * @return array
     */
    function getCategoryIndex(int $index) : array
    {
        $category = array_keys($this->getShops())[$index];
        return $this->getShops()[$category];
    }

    /**
     * @return array
     */
    function getShopsCategories() : array
    {
        $all = $this->getShops();
        $categories = [];
        foreach ($all as $category => $items) $categories[$category] = $items["name"];
        return $categories;
    }

    /**
     * @param string $category
     * @return string
     */
    function getImageCategory(string $category) : string
    {
        return $this->getCategory($category)["image"];
    }

    /**
     * @param int $category
     * @return bool
     */
    function isCategoryItems(int $category) : bool
    {
        return isset($this->getCategoryIndex($category)["items"]);
    }

    /**
     * @param int $category
     * @return array
     */
    function getCategoryItems(int $category) : array
    {
        return $this->getCategoryIndex($category)["items"];
    }

    /**
     * @param string $category
     * @param int $index
     * @return string
     */
    function getStringItem(string $category, int $index) : string
    {
        return $this->getCategoryItems($category)[$index];
    }

    /**
     * @return array
     */
    function getSellItems() : array
    {
        return $this->getSell()->getAll()["items"];
    }

    /**
     * @param int $index
     * @return string
     */
    function getSellItem(int $index) : string
    {
        return $this->getSell()->getAll()["items"][$index];
    }

    /**
     * @return Home
     */
    function getHome() : Home
    {
        return $this->home;
    }
}