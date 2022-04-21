<?php

namespace Warehouse\Tests\Service;

use PHPUnit\Framework\TestCase;
use Warehouse\Model\Boiler;
use Warehouse\Model\Brand;
use Warehouse\Model\LawnMower;
use Warehouse\Model\LawnMowerType;
use Warehouse\Model\Product;
use Warehouse\Model\QualityCategory;
use Warehouse\Model\Stock;
use Warehouse\Model\Warehouse;
use Warehouse\Service\Exception\WarehouseException;
use Warehouse\Service\WarehouseService;
use Warehouse\Service\WarehouseServiceInterface;

final class WarehouseServiceTest extends TestCase
{
    /** @var WarehouseServiceInterface */
    private WarehouseServiceInterface $warehouseService;

    protected function setUp(): void
    {
        $this->warehouseService = new WarehouseService();
    }

    /**
     * Egy raktár létrehozás tesztelése
     */
    public function testCreateOneWarehouse(): void
    {
        $warehouse = $this->warehouseService->createWarehouse("Első raktár", "Miskolc, Valami utca 2.", 100);
        $this->assertInstanceOf(Warehouse::class, $warehouse);
        $this->assertEquals("Első raktár", $warehouse->getName());
        $this->assertEquals("Miskolc, Valami utca 2.", $warehouse->getAddress());
        $this->assertEquals(100, $warehouse->getCapacity());

        $this->assertEquals(100, $this->warehouseService->getSumCapacity());
        $this->assertEquals(100, $this->warehouseService->getFreeCapacity());
    }

    private function createWarehouses(): void
    {
        $this->warehouseService->createWarehouse("Első raktár", "Miskolc, Valami utca 2.", 100);
        $this->warehouseService->createWarehouse("Második raktár", "Mezőkövesd, Valami út 3.", 30);
        $this->warehouseService->createWarehouse("Harmadik raktár", "Harsány, Valami utca 5.", 20);
    }

    /** @return Product */
    private function createProduct1(): Product
    {
        $brand = Brand::create("Teszt Név", QualityCategory::HIGH);
        return Boiler::create("12345678ABC", "Boiler EC2", 40000, $brand, 45, 1500);
    }

    /** @return Product */
    private function createProduct2(): Product
    {
        $brand = Brand::create("Teszt Név 2", QualityCategory::LOWEST);
        return LawnMower::create("12345678ADF", "Fűnyíró T2000", 21000, $brand, LawnMowerType::PETROL, 460, 22000, 40);
    }

    /** @return Product */
    private function createProduct3(): Product
    {
        $brand = Brand::create("Teszt Név 3", QualityCategory::MEDIUM);
        return LawnMower::create("12345678AHI", "Fűnyíró T3000", 52000, $brand, LawnMowerType::ELECTRONIC, 460, 22000, 40);
    }

    /**
     * Olyan termék kivétele a raktárból, amely nincs benne.
     */
    public function testTakeProductsNotExistProduct(): void
    {
        $this->createWarehouses();
        $this->assertEquals(150, $this->warehouseService->getFreeCapacity());

        $this->expectException(WarehouseException::class);
        $this->expectExceptionCode(WarehouseException::OUT_OF_STOCK);
        $this->warehouseService->takeProducts("12345678ADF", 10);
        $this->assertEquals(150, $this->warehouseService->getFreeCapacity());
    }

    /**
     * Termék raktárba helyezése
     */
    public function testAddProductsSimple(): void
    {
        $this->createWarehouses();
        $this->assertEquals(150, $this->warehouseService->getFreeCapacity());

        try {
            $this->warehouseService->addProducts($this->createProduct1(), 20);
        } catch (WarehouseException $ex) {
            $this->fail();
        }

        $this->assertEquals(130, $this->warehouseService->getFreeCapacity());
    }

    /**
     * Termék raktárba helyezése, több raktárba szétosztás
     */
    public function testAddProductsMoreWarehouse(): void
    {
        $this->createWarehouses();
        $this->assertEquals(150, $this->warehouseService->getFreeCapacity());

        try {
            $this->warehouseService->addProducts($this->createProduct1(), 50);
            $this->warehouseService->addProducts($this->createProduct2(), 80);
        } catch (WarehouseException $ex) {
            $this->fail();
        }

        $this->assertEquals(20, $this->warehouseService->getFreeCapacity());
    }

    /**
     * Kiíratás tesztelése
     */
    public function testPrintWarehouses(): void
    {
        $this->createWarehouses();
        $this->assertEquals(150, $this->warehouseService->getFreeCapacity());

        try {
            $this->warehouseService->addProducts($this->createProduct3(), 20);
            $this->warehouseService->addProducts($this->createProduct1(), 50);
            $this->warehouseService->addProducts($this->createProduct2(), 45);
            $this->warehouseService->addProducts($this->createProduct1(), 20);
        } catch (WarehouseException $ex) {
            $this->fail();
        }

        $this->assertEquals(15, $this->warehouseService->getFreeCapacity());

        $this->warehouseService->printWarehousesToConsole();
    }

    /**
     * Termék kivétele a raktárból. Az első raktárból teljesíthető.
     */
    public function testTakeProductsSimple(): void
    {
        $this->createWarehouses();
        $this->assertEquals(150, $this->warehouseService->getFreeCapacity());

        try {
            $this->warehouseService->addProducts($this->createProduct1(), 50);
            $this->warehouseService->addProducts($this->createProduct2(), 80);
        } catch (WarehouseException $ex) {
            $this->fail();
        }

        $this->assertEquals(20, $this->warehouseService->getFreeCapacity());

        try {
            $stock = $this->warehouseService->takeProducts("12345678ADF", 30);

            $this->assertInstanceOf(Stock::class, $stock);
            $this->assertEquals("12345678ADF", $stock->getProduct()->getItemNumber());
            $this->assertEquals(30, $stock->getPiece());
        } catch (WarehouseException $ex) {
            $this->fail();
        }

        $this->assertEquals(50, $this->warehouseService->getFreeCapacity());
    }

    /**
     * Termék kivétele a raktárból. Több raktárból teljesíthető.
     */
    public function testTakeProductsFromMoreWarehouse(): void
    {
        $this->createWarehouses();
        $this->assertEquals(150, $this->warehouseService->getFreeCapacity());

        try {
            $this->warehouseService->addProducts($this->createProduct1(), 50);
            $this->warehouseService->addProducts($this->createProduct2(), 80);
        } catch (WarehouseException $ex) {
            $this->fail();
        }

        $this->assertEquals(20, $this->warehouseService->getFreeCapacity());

        try {
            $stock = $this->warehouseService->takeProducts("12345678ADF", 60);

            $this->assertInstanceOf(Stock::class, $stock);
            $this->assertEquals("12345678ADF", $stock->getProduct()->getItemNumber());
            $this->assertEquals(60, $stock->getPiece());
        } catch (WarehouseException $ex) {
            $this->fail();
        }

        $this->assertEquals(80, $this->warehouseService->getFreeCapacity());
    }

    /**
     * Termék raktárba helyezése, de a raktárban nincs elegendő hely.
     */
    public function testAddProductsIfNotEnoughSpace(): void
    {
        $this->createWarehouses();
        $this->assertEquals(150, $this->warehouseService->getFreeCapacity());

        try {
            $this->warehouseService->addProducts($this->createProduct1(), 50);
            $this->warehouseService->addProducts($this->createProduct2(), 80);
        } catch (WarehouseException $ex) {
            $this->fail();
        }

        $this->assertEquals(20, $this->warehouseService->getFreeCapacity());

        $this->expectException(WarehouseException::class);
        $this->expectExceptionCode(WarehouseException::NOT_ENOUGH_SPACE);
        $this->warehouseService->addProducts($this->createProduct2(), 30);
        $this->assertEquals(20, $this->warehouseService->getFreeCapacity());
    }
}
