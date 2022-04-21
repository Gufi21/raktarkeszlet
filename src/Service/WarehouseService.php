<?php

namespace Warehouse\Service;

use Warehouse\Model\Product;
use Warehouse\Model\Stock;
use Warehouse\Model\Warehouse;

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
     */
    public function createWarehouse(string $name, string $address, int $capacity): void
    {
        $warehouse = new Warehouse();
        $warehouse->setName($name);
        $warehouse->setAddress($address);
        $warehouse->setCapacity($capacity);

        $this->warehouses[] = $warehouse;
    }

    /**
     * Raktárkészlet létrehozása
     *
     * Egy termék egy raktárban lévő készlete.
     *
     * @param Product $product Termék
     * @param int $piece Darabszám
     * @return Stock
     */
    public function createStock(Product $product, int $piece): Stock
    {
        $stock = new Stock();
        $stock->setProduct($product);
        $stock->setPiece($piece);
        return $stock;
    }

    /**
     * A raktárak tartalmának kiírása.
     */
    public function printWarehouses(): void
    {
        foreach ($this->warehouses as $warehouse) {
            echo $warehouse->getName() . " (" . $warehouse->getAddress() . ")<br>";
            foreach ($warehouse->getStocks() as $stock) {
                echo $stock->getProduct()->getName() . " (Cikkszám: " . $stock->getProduct()->getItemNumber() . ") x" . $stock->getPiece() . "<br>";
            }
            echo "<br><br>";
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
                $stocks[$ind] = $this->createStock($product, $useCapacity);
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
     * @param string $productName Termék neve
     * @param int $piece Darabszám
     * @return Stock
     * @throws WarehouseException Nincs raktáron.
     */
    public function takeProducts(string $productName, int $piece): Stock
    {
        /**  @var Stock[] $stocks */
        $stocks = [];
        $needPiece = $piece;
        $warehouseCount = count($this->warehouses);

        for ($ind = 0; $needPiece > 0 && $ind < $warehouseCount; $ind++) {
            $stock = $this->warehouses[$ind]->takeStock($productName, $needPiece);
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

        return $this->createStock($stocks[0]->getProduct(), $piece);
    }
}
