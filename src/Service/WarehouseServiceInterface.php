<?php

namespace Warehouse\Service;

use Warehouse\Model\Product;
use Warehouse\Model\Stock;

interface WarehouseServiceInterface
{
    /**
     * Raktár létrehozása
     *
     * @param string $name A raktár neve.
     * @param string $address A raktár címe.
     * @param int $capacity A raktár kapacitása.
     */
    public function createWarehouse(string $name, string $address, int $capacity): void;

    /**
     * Raktárkészlet létrehozása
     *
     * Egy termék egy raktárban lévő készlete.
     *
     * @param Product $product Termék
     * @param int $piece Darabszám
     * @return Stock
     */
    public function createStock(Product $product, int $piece): Stock;

    /**
     * A raktárak tartalmának kiírása.
     */
    public function printWarehouses(): void;

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
     * @param string $productName Termék neve
     * @param int $piece Darabszám
     * @return Stock
     * @throws WarehouseException Nincs raktáron.
     */
    public function takeProducts(string $productName, int $piece): Stock;
}
