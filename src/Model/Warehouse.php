<?php

namespace Warehouse\Model;

class Warehouse
{
    /**
     * Megnevezés
     *
     * @var string
     */
    private string $name;

    /**
     * Cím
     *
     * @var string
     */
    private string $address;

    /**
     * Kapacitás
     *
     * Az érték termék darabszámban értendő.
     *
     * @var int
     */
    private int $capacity;

    /** @var Stock[] */
    private array $stocks = [];

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

    /** @return string */
    public function getAddress(): string
    {
        return $this->address;
    }

    /** @param string $address */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /** @return int */
    public function getCapacity(): int
    {
        return $this->capacity;
    }

    /** @param int $capacity */
    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }

    /** @return Stock[] */
    public function getStocks(): array
    {
        return $this->stocks;
    }

    /** @var Stock[] $stocks */
    public function setStocks(array $stocks): void
    {
        $this->stocks = $stocks;
    }

    /**
     * Raktár használt kapacitása.
     *
     * @return int
     */
    public function getUsedCapacity(): int
    {
        $used = 0;
        foreach ($this->stocks as $stock) {
            $used += $stock->getPiece();
        }
        return $used;
    }

    /**
     * Raktár szabad kapacitása.
     *
     * @return int
     */
    public function getFreeCapacity(): int
    {
        return $this->capacity - $this->getUsedCapacity();
    }

    /**
     * Raktárkészlet hozzáadása
     *
     * Ha a raktárban már van az adott termékből készlet, akkor a kettőt összevonjuk.
     *
     * @param Stock $stock
     */
    public function addStock(Stock $stock): void
    {
        $stockCount = count($this->stocks);
        $updated = false;

        for ($i = 0; !$updated && $i < $stockCount; $i++) {
            if ($this->stocks[$i]->getProduct()->getName() === $stock->getProduct()->getName()) {
                $this->stocks[$i]->addPiece($stock->getPiece());
                $updated = true;
            }
        }

        if (!$updated) {
            $this->stocks[] = $stock;
        }
    }

    /**
     * Raktárkészlet kivétele
     *
     * Amennyiben nincs a raktárban a keresett termékből, akkor nem adunk vissza semmit.
     *
     * @param string $productName Termék neve
     * @param int $piece Darabszám
     * @return Stock|null
     */
    public function takeStock(string $productName, int $piece): ?Stock
    {
        /** @var Stock|null $stock */
        $stock = null;
        $stockCount = count($this->stocks);

        for ($i = 0; $stock == null && $i < $stockCount; $i++) {
            if ($this->stocks[$i]->getProduct()->getName() === $productName) {
                if ($this->stocks[$i]->getPiece() > $piece) {
                    // ha több van raktáron, mint amennyi nekünk kell
                    $stock = new Stock();
                    $stock->setProduct($this->stocks[$i]->getProduct());
                    $stock->setPiece($piece);

                    $this->stocks[$i]->takePiece($piece);
                } else {
                    // ha az összesen ki kell vennünk a raktárból
                    $stock = $this->stocks[$i];

                    unset($this->stocks[$i]);
                    $this->stocks = array_values($this->stocks);
                }
            }
        }

        return $stock;
    }
}
