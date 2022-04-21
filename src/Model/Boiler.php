<?php

namespace Warehouse\Model;

class Boiler extends Product
{
    /**
     * Űrtartalom
     *
     * Az érték literben értendő.
     *
     * @var int
     */
    private int $cubicCapacity;

    /**
     * Teljesítmény
     *
     * Az érték wattban (W) értendő.
     *
     * @var int
     */
    private int $performance;

    /**
     * Boiler termék objektum létrehozása
     *
     * @param string $itemNumber Cikkszám
     * @param string $name Termék neve
     * @param int $price Ár
     * @param Brand $brand Márka
     * @param int $cubicCapacity Űrtartalom
     * @param int $performance Teljesítmény
     * @return Boiler
     */
    public static function create(string $itemNumber, string $name, int $price, Brand $brand, int $cubicCapacity, int $performance): Boiler
    {
        $boiler = new Boiler();
        $boiler->setItemNumber($itemNumber);
        $boiler->setName($name);
        $boiler->setPrice($price);
        $boiler->setBrand($brand);
        $boiler->setCubicCapacity($cubicCapacity);
        $boiler->setPerformance($performance);
        return $boiler;
    }

    /** @return int */
    public function getCubicCapacity(): int
    {
        return $this->cubicCapacity;
    }

    /** @param int $cubicCapacity */
    public function setCubicCapacity(int $cubicCapacity): void
    {
        $this->cubicCapacity = $cubicCapacity;
    }

    /** @return int */
    public function getPerformance(): int
    {
        return $this->performance;
    }

    /** @param int $performance */
    public function setPerformance(int $performance): void
    {
        $this->performance = $performance;
    }
}
