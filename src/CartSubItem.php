<?php

namespace LukePOLO\LaraCart;

use LukePOLO\LaraCart\Traits\CartOptionsMagicMethodsTrait;

/**
 * Class CartItemOption
 *
 * @property float price
 * @property array options
 * @property array items
 *
 * @package LukePOLO\LaraCart
 */
class CartSubItem
{
    use CartOptionsMagicMethodsTrait;

    const ITEMS = 'items';

    public $locale;
    public $internationalFormat;

    private $itemHash;

    /**
     * @param $options
     */
    public function __construct($options)
    {
        $this->options['items'] = [];

        foreach ($options as $option => $value) {
            array_set($this->options, $option, $value);
        }

        $this->itemHash = app(LaraCart::HASH, $this->options);
    }

    /**
     * Gets the hash for the item
     *
     * @return mixed
     */
    public function getHash()
    {
        return $this->itemHash;
    }

    /**
     * Gets the formatted price
     *
     * @param bool|true $format
     * @param bool $taxedItemsOnly
     *
     * @return string
     */
    public function price($format = true, $taxedItemsOnly = true)
    {
        $price = $this->price;

        if (isset($this->items)) {
            foreach ($this->items as $item) {
                if ($taxedItemsOnly && !$item->taxable) {
                    continue;
                }
                $price += $item->price(false);
            }
        }

        return LaraCart::formatMoney($price, $this->locale, $this->internationalFormat, $format);
    }
}
