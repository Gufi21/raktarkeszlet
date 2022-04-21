<?php

namespace Warehouse\Model;

class LawnMower extends Product
{
    /**
     * Fűnyíró típusa
     *
     * @var int
     */
    private int $type;

    /**
     * Vágószélesség
     *
     * Az érték milliméterben értendő.
     *
     * @var int
     */
    private int $cutWidth;

    /**
     * Fűgyűjtő űrtartalma
     *
     * Az érték literben értendő. Van olyan fűnyíró, aminek nincs begyűjtő része.
     *
     * @var int|null
     */
    private ?int $grassCatcherCapacity;

    /**
     * Súly
     *
     * Az érték grammban értendő.
     *
     * @var int
     */
    private int $weight;

    /**
     * Fűnyíró termék objektum létrehozása
     *
     * @param string $itemNumber Cikkszám
     * @param string $name Termék neve
     * @param int $price Ár
     * @param Brand $brand Márka
     * @param int $type Fűnyíró típusa
     * @param int $cutWidth Vágószélesség
     * @param int $weight Súly
     * @param int|null $grassCatcherCapacity Fűgyűjtő űrtartalma
     * @return LawnMower
     */
    public static function create(
        string $itemNumber, string $name, int $price, Brand $brand,
        int $type, int $cutWidth, int $weight, ?int $grassCatcherCapacity
    ): LawnMower {
        $lawnMower = new LawnMower();
        $lawnMower->setItemNumber($itemNumber);
        $lawnMower->setName($name);
        $lawnMower->setPrice($price);
        $lawnMower->setBrand($brand);
        $lawnMower->setType($type);
        $lawnMower->setCutWidth($cutWidth);
        $lawnMower->setWeight($weight);
        $lawnMower->setGrassCatcherCapacity($grassCatcherCapacity);
        return $lawnMower;
    }

    /** @return int */
    public function getType(): int
    {
        return $this->type;
    }

    /** @param int $type */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /** @return int */
    public function getCutWidth(): int
    {
        return $this->cutWidth;
    }

    /** @param int $cutWidth */
    public function setCutWidth(int $cutWidth): void
    {
        $this->cutWidth = $cutWidth;
    }

    /** @return int|null */
    public function getGrassCatcherCapacity(): ?int
    {
        return $this->grassCatcherCapacity;
    }

    /** @param int|null $grassCatcherCapacity */
    public function setGrassCatcherCapacity(?int $grassCatcherCapacity): void
    {
        $this->grassCatcherCapacity = $grassCatcherCapacity;
    }

    /** @return int */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /** @param int $weight */
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }
}
