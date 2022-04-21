<?php

namespace Warehouse\Service;

use Warehouse\Model\Boiler;
use Warehouse\Model\Brand;
use Warehouse\Model\LawnMower;

class ProductService implements ProductServiceInterface
{
    /**
     * Márka létrehozása
     *
     * @param string $name Márkanév
     * @param int $qualityCategory Minőségkategória
     * @return Brand
     */
    public function createBrand(string $name, int $qualityCategory): Brand
    {
        $brand = new Brand();
        $brand->setName($name);
        $brand->setQualityCategory($qualityCategory);
        return $brand;
    }

    /**
     * Boiler termék létrehozása
     *
     * @param string $itemNumber Cikkszám
     * @param string $name Termék neve
     * @param int $price Ár
     * @param Brand $brand Márka
     * @param int $cubicCapacity Űrtartalom
     * @param int $performance Teljesítmény
     * @return Boiler
     */
    public function createBoiler(string $itemNumber, string $name, int $price, Brand $brand, int $cubicCapacity, int $performance): Boiler
    {
        $boiler = new Boiler();
        $boiler->setItemNumber($itemNumber);
        $boiler->setName($name);
        $boiler->setPrice($price);
        $boiler->setBrand($brand);
        $boiler->setCubicCapacity($cubicCapacity);
        $boiler->setPerformance($performance);
        return $boiler;
    }

    /**
     * Fűnyíró termék létrehozása
     *
     * @param string $itemNumber Cikkszám
     * @param string $name Termék neve
     * @param int $price Ár
     * @param Brand $brand Márka
     * @param int $type Fűnyíró hajtásának típusa
     * @param int $cutWidth Vágószélesség
     * @param int $weight Súly
     * @param int|null $grassCatcherCapacity Fűgyűjtő űrtartalma
     * @return LawnMower
     */
    public function createLawnMower(
        string $itemNumber, string $name, int $price, Brand $brand,
        int $type, int $cutWidth, int $weight, ?int $grassCatcherCapacity = null
    ): LawnMower {
        $lawnMower = new LawnMower();
        $lawnMower->setItemNumber($itemNumber);
        $lawnMower->setName($name);
        $lawnMower->setPrice($price);
        $lawnMower->setBrand($brand);
        $lawnMower->setType($type);
        $lawnMower->setCutWidth($cutWidth);
        $lawnMower->setGrassCatcherCapacity($grassCatcherCapacity);
        $lawnMower->setWeight($weight);
        return $lawnMower;
    }
}
