<?php

namespace Warehouse\Tests\Model;

use PHPUnit\Framework\TestCase;
use Warehouse\Model\Boiler;
use Warehouse\Model\Brand;
use Warehouse\Model\LawnMower;
use Warehouse\Model\LawnMowerType;
use Warehouse\Model\QualityCategory;
use Warehouse\Model\Stock;
use Warehouse\Model\Warehouse;

final class WarehouseTest extends TestCase
{
    /** @return Stock */
    private function createStock1(): Stock
    {
        $brand = Brand::create("Teszt Név", QualityCategory::HIGH);
        $product = Boiler::create("12345678ABC", "Boiler EC2", 40000, $brand, 45, 1500);
        return Stock::create($product, 10);
    }

    /** @return Stock */
    private function createStock2(): Stock
    {
        $brand = Brand::create("Teszt Név 2", QualityCategory::LOWEST);
        $product = LawnMower::create("12345678ADF", "Fűnyíró T2000", 21000, $brand, LawnMowerType::PETROL, 460, 22000, 40);
        return Stock::create($product, 15);
    }

    /** @return Stock */
    private function createStock3(): Stock
    {
        $brand = Brand::create("Teszt Név 3", QualityCategory::MEDIUM);
        $product = LawnMower::create("12345678AHI", "Fűnyíró T3000", 52000, $brand, LawnMowerType::ELECTRONIC, 460, 22000, 40);
        return Stock::create($product, 3);
    }

    /** @return Warehouse */
    private function createWarehouse(): Warehouse
    {
        $warehouse = Warehouse::create("Raktár 1", "Valahol, Valami cím", 30);
        $warehouse->setStocks([$this->createStock1(), $this->createStock2()]);
        return $warehouse;
    }

    /**
     * Használt kapacitás számításának tesztelése.
     */
    public function testGetUsedCapacity(): void
    {
        $warehouse = $this->createWarehouse();
        $this->assertEquals(25, $warehouse->getUsedCapacity());
    }

    /**
     * Szabad kapacitás számításának tesztelése.
     */
    public function testGetFreeCapacity(): void
    {
        $warehouse = $this->createWarehouse();
        $this->assertEquals(5, $warehouse->getFreeCapacity());
    }

    /**
     * Új raktárkészlet hozzáadásának tesztelése.
     */
    public function testAddStockAsNewStock(): void
    {
        $warehouse = $this->createWarehouse();
        $this->assertEquals(5, $warehouse->getFreeCapacity());
        $this->assertEquals(25, $warehouse->getUsedCapacity());
        $this->assertCount(2, $warehouse->getStocks());

        $warehouse->addStock($this->createStock3());
        $this->assertEquals(2, $warehouse->getFreeCapacity());
        $this->assertEquals(28, $warehouse->getUsedCapacity());
        $this->assertCount(3, $warehouse->getStocks());
    }

    /**
     * Meglévő raktárkészlet bővítésének tesztelése.
     */
    public function testAddStockWithUpdate(): void
    {
        $stock = $this->createStock2();
        $stock->setPiece(4);

        $warehouse = $this->createWarehouse();
        $this->assertEquals(5, $warehouse->getFreeCapacity());
        $this->assertEquals(25, $warehouse->getUsedCapacity());
        $this->assertCount(2, $warehouse->getStocks());

        $warehouse->addStock($stock);
        $this->assertEquals(1, $warehouse->getFreeCapacity());
        $this->assertEquals(29, $warehouse->getUsedCapacity());
        $this->assertCount(2, $warehouse->getStocks());
    }

    /**
     * Raktárkészlet egy részének kivétele.
     */
    public function testTakeStockPart(): void
    {
        $warehouse = $this->createWarehouse();
        $this->assertEquals(5, $warehouse->getFreeCapacity());
        $this->assertEquals(25, $warehouse->getUsedCapacity());
        $this->assertCount(2, $warehouse->getStocks());

        $stock = $warehouse->takeStock("12345678ADF", 10);
        $this->assertEquals(15, $warehouse->getFreeCapacity());
        $this->assertEquals(15, $warehouse->getUsedCapacity());
        $this->assertCount(2, $warehouse->getStocks());
        $this->assertInstanceOf(Stock::class, $stock);
        $this->assertEquals("12345678ADF", $stock->getProduct()->getItemNumber());
        $this->assertEquals(10, $stock->getPiece());
    }

    /**
     * Teljes raktárkészlet kivétele.
     */
    public function testTakeStockFull(): void
    {
        $warehouse = $this->createWarehouse();
        $this->assertEquals(5, $warehouse->getFreeCapacity());
        $this->assertEquals(25, $warehouse->getUsedCapacity());
        $this->assertCount(2, $warehouse->getStocks());

        $stock = $warehouse->takeStock("12345678ADF", 15);
        $this->assertEquals(20, $warehouse->getFreeCapacity());
        $this->assertEquals(10, $warehouse->getUsedCapacity());
        $this->assertCount(1, $warehouse->getStocks());
        $this->assertInstanceOf(Stock::class, $stock);
        $this->assertEquals("12345678ADF", $stock->getProduct()->getItemNumber());
        $this->assertEquals(15, $stock->getPiece());
    }

    /**
     * Többet szeretnék kivenni, mint amennyi a raktárban van.
     */
    public function testTakeStockMore(): void
    {
        $warehouse = $this->createWarehouse();
        $this->assertEquals(5, $warehouse->getFreeCapacity());
        $this->assertEquals(25, $warehouse->getUsedCapacity());
        $this->assertCount(2, $warehouse->getStocks());

        $stock = $warehouse->takeStock("12345678ADF", 20);
        $this->assertEquals(20, $warehouse->getFreeCapacity());
        $this->assertEquals(10, $warehouse->getUsedCapacity());
        $this->assertCount(1, $warehouse->getStocks());
        $this->assertInstanceOf(Stock::class, $stock);
        $this->assertEquals("12345678ADF", $stock->getProduct()->getItemNumber());
        $this->assertEquals(15, $stock->getPiece());
    }

    /**
     * Raktárban nem tárolt termék kivételi kísérlete.
     */
    public function testTakeStockFailed(): void
    {
        $warehouse = $this->createWarehouse();
        $this->assertEquals(5, $warehouse->getFreeCapacity());
        $this->assertEquals(25, $warehouse->getUsedCapacity());
        $this->assertCount(2, $warehouse->getStocks());

        $stock = $warehouse->takeStock("12345678XYZ", 5);
        $this->assertEquals(5, $warehouse->getFreeCapacity());
        $this->assertEquals(25, $warehouse->getUsedCapacity());
        $this->assertCount(2, $warehouse->getStocks());
        $this->assertNull($stock);
    }
}
