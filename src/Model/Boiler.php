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
