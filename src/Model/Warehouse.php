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

    /**
     * Raktár objektum létrehozása
     *
     * @param string $name Megnevezés
     * @param string $address Cím
     * @param int $capacity Kapacitás
     * @return Warehouse
     */
    public static function create(string $name, string $address, int $capacity): Warehouse
    {
        $warehouse = new Warehouse();
        $warehouse->setName($name);
        $warehouse->setAddress($address);
        $warehouse->setCapacity($capacity);
        return $warehouse;
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
            if ($this->stocks[$i]->getProduct()->getItemNumber() === $stock->getProduct()->getItemNumber()) {
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
     * @param string $itemNumber Cikkszám
     * @param int $piece Darabszám
     * @return Stock|null
     */
    public function takeStock(string $itemNumber, int $piece): ?Stock
    {
        /** @var Stock|null $stock */
        $stock = null;
        $stockCount = count($this->stocks);

        for ($i = 0; $stock == null && $i < $stockCount; $i++) {
            if ($this->stocks[$i]->getProduct()->getItemNumber() === $itemNumber) {
                if ($this->stocks[$i]->getPiece() > $piece) {
                    // ha több van raktáron, mint amennyi nekünk kell
                    $stock = Stock::create($this->stocks[$i]->getProduct(), $piece);

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
