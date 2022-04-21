<?php

namespace Warehouse\Model;

abstract class Product
{
    /**
     * Cikkszám
     *
     * @var string
     */
    protected string $itemNumber;

    /**
     * Megnevezés
     *
     * @var string
     */
    protected string $name;

    /**
     * Ár
     *
     * @var int
     */
    protected int $price;

    /**
     * Márka
     *
     * @var Brand
     */
    protected Brand $brand;

    /** @return string */
    public function getItemNumber(): string
    {
        return $this->itemNumber;
    }

    /** @param string $itemNumber */
    public function setItemNumber(string $itemNumber): void
    {
        $this->itemNumber = $itemNumber;
    }

    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }

    /** @param string $name */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /** @return int */
    public function getPrice(): int
    {
        return $this->price;
    }

    /** @param int $price */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /** @return Brand */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /** @param Brand $brand */
    public function setBrand(Brand $brand): void
    {
        $this->brand = $brand;
    }
}
