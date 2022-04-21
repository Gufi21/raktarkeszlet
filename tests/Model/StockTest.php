<?php

namespace Warehouse\Tests\Model;

use PHPUnit\Framework\TestCase;
use Warehouse\Model\Boiler;
use Warehouse\Model\Brand;
use Warehouse\Model\Product;
use Warehouse\Model\QualityCategory;
use Warehouse\Model\Stock;

final class StockTest extends TestCase
{
    /** @return Product */
    private function createProduct(): Product
    {
        $brand = Brand::create("Teszt Név", QualityCategory::HIGH);
        return Boiler::create("12345678ABC", "Boiler EC2", 40000, $brand, 45, 1500);
    }

    /**
     * Darabszám növelés tesztelése
     */
    public function testAddPiece(): void
    {
        $stock = Stock::create($this->createProduct(), 3);

        $this->assertEquals(3, $stock->getPiece());

        $stock->addPiece(5);
        $this->assertEquals(8, $stock->getPiece());
    }

    /**
     * Darabszám csökkentés tesztelése
     */
    public function testTakePiece(): void
    {
        $stock = Stock::create($this->createProduct(), 7);

        $this->assertEquals(7, $stock->getPiece());

        $stock->takePiece(3);
        $this->assertEquals(4, $stock->getPiece());
    }
}
