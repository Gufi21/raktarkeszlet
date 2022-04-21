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
