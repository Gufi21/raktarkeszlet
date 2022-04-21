<?php

namespace Warehouse\Service;

use Warehouse\Model\Product;
use Warehouse\Model\Stock;
use Warehouse\Model\Warehouse;
use Warehouse\Service\Exception\WarehouseException;

interface WarehouseServiceInterface
{
    /**
     * Raktár létrehozása
     *
     * @param string $name A raktár neve.
     * @param string $address A raktár címe.
     * @param int $capacity A raktár kapacitása.
     * @return Warehouse
     */
    public function createWarehouse(string $name, string $address, int $capacity): Warehouse;

    /**
     * Összkapacitás
     *
     * @return int
     */
    public function getSumCapacity(): int;

    /**
     * Szabad kapacitás
     *
     * @return int
     */
    public function getFreeCapacity(): int;

    /**
     * A raktárak tartalmának kiírása HTML outputra.
     */
    public function printWarehousesToHtml(): void;

    /**
     * A raktárak tartalmának kiírása console outputra.
     */
    public function printWarehousesToConsole(): void;

    /**
     * Termék raktárba helyezése.
     *
     * @param Product $product Termék
     * @param int $piece Darabszám
     * @throws WarehouseException Nincs elegendő hely.
     */
    public function addProducts(Product $product, int $piece): void;

    /**
     * Termék kivétele a raktárakból.
     *
     * @param string $itemNumber Cikkszám
     * @param int $piece Darabszám
     * @return Stock
     * @throws WarehouseException Nincs raktáron.
     */
    public function takeProducts(string $itemNumber, int $piece): Stock;
}
