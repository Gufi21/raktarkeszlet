<?php

namespace Warehouse\Service;

use Warehouse\Model\Product;
use Warehouse\Model\Stock;
use Warehouse\Model\Warehouse;
use Warehouse\Service\Exception\WarehouseException;

class WarehouseService implements WarehouseServiceInterface
{
    /**
     * Az alkalmazásban lévő raktárak.
     *
     * @var Warehouse[]
     */
    private array $warehouses = [];

    /**
     * Raktár létrehozása
     *
     * @param string $name A raktár neve.
     * @param string $address A raktár címe.
     * @param int $capacity A raktár kapacitása.
     * @return Warehouse
     */
    public function createWarehouse(string $name, string $address, int $capacity): Warehouse
    {
        $warehouse = Warehouse::create($name, $address, $capacity);
        $this->warehouses[] = $warehouse;
        return $warehouse;
    }

    /**
     * Összkapacitás
     *
     * @return int
     */
    public function getSumCapacity(): int
    {
        $sumCapacity = 0;
        foreach ($this->warehouses as $warehouse) {
            $sumCapacity += $warehouse->getCapacity();
        }
        return $sumCapacity;
    }

    /**
     * Szabad kapacitás
     *
     * @return int
     */
    public function getFreeCapacity(): int
    {
        $freeCapacity = 0;
        foreach ($this->warehouses as $warehouse) {
            $freeCapacity += $warehouse->getFreeCapacity();
        }
        return $freeCapacity;
    }

    /**
     * A raktárak tartalmának kiírása HTML outputra.
     */
    public function printWarehousesToHtml(): void
    {
        echo "<br>";
        foreach ($this->warehouses as $warehouse) {
            echo "<span>" . $warehouse->getName() . " (" . $warehouse->getAddress() . ")</span><br>";
            foreach ($warehouse->getStocks() as $stock) {
                echo "<span>" . $stock->getProduct()->getName() . " (Cikkszám: " . $stock->getProduct()->getItemNumber() . ") x" . $stock->getPiece() . "</span><br>";
            }
            echo "<br><br>";
        }
    }

    /**
     * A raktárak tartalmának kiírása console outputra.
     */
    public function printWarehousesToConsole(): void
    {
        echo "\n";
        foreach ($this->warehouses as $warehouse) {
            echo $warehouse->getName() . " (" . $warehouse->getAddress() . ")\n";
            foreach ($warehouse->getStocks() as $stock) {
                echo $stock->getProduct()->getName() . " (Cikkszám: " . $stock->getProduct()->getItemNumber() . ") x" . $stock->getPiece() . "\n";
            }
            echo "\n\n";
        }
    }

    /**
     * Termék raktárba helyezése.
     *
     * @param Product $product Termék
     * @param int $piece Darabszám
     * @throws WarehouseException Nincs elegendő hely.
     */
    public function addProducts(Product $product, int $piece): void
    {
        /**  @var Stock[] $stocks */
        $stocks = [];
        $needCapacity = $piece;

        $warehouseCount = count($this->warehouses);
        for ($ind = 0; $needCapacity > 0 && $ind < $warehouseCount; $ind++) {
            $freeCapacity = $this->warehouses[$ind]->getFreeCapacity();

            if ($freeCapacity > 0) {
                $useCapacity = min($freeCapacity, $needCapacity);
                $stocks[$ind] = Stock::create($product, $useCapacity);
                $needCapacity -= $useCapacity;
            }
        }

        if ($needCapacity > 0) {
            throw new WarehouseException(WarehouseException::NOT_ENOUGH_SPACE, "Nincs a raktárban elég hely. Szükséges: " . $needCapacity);
        }

        foreach ($stocks as $ind => $stock) {
            $this->warehouses[$ind]->addStock($stock);
        }
    }

    /**
     * Termék kivétele a raktárakból.
     *
     * Visszaadjuk az összevont raktárkészletet.
     *
     * @param string $itemNumber Cikkszám
     * @param int $piece Darabszám
     * @return Stock
     * @throws WarehouseException Nincs raktáron.
     */
    public function takeProducts(string $itemNumber, int $piece): Stock
    {
        /**  @var Stock[] $stocks */
        $stocks = [];
        $needPiece = $piece;
        $warehouseCount = count($this->warehouses);

        for ($ind = 0; $needPiece > 0 && $ind < $warehouseCount; $ind++) {
            $stock = $this->warehouses[$ind]->takeStock($itemNumber, $needPiece);
            if ($stock != null) {
                $needPiece -= $stock->getPiece();
                $stocks[] = $stock;
            }
        }

        if ($needPiece > 0) {
            // ha nem tudtuk kivenni az összes szükséges mennyiséget

            if (count($stocks) > 0) {
                // ha valamennyit ki tudtunk venni, akkor visszatesszük őket
                $this->addProducts($stocks[0]->getProduct(), $piece - $needPiece);
            }

            throw new WarehouseException(WarehouseException::OUT_OF_STOCK, "Nincs raktáron a szükséges mennyiség. Hiányzik: " . $needPiece);
        }

        return Stock::create($stocks[0]->getProduct(), $piece);
    }
}
