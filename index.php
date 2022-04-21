<?php

require 'vendor/autoload.php';

use Warehouse\Model\Boiler;
use Warehouse\Model\Brand;
use Warehouse\Model\LawnMower;
use Warehouse\Model\LawnMowerType;
use Warehouse\Model\QualityCategory;
use Warehouse\Service\Exception\WarehouseException;
use Warehouse\Service\WarehouseService;

$warehouseService = new WarehouseService();

$warehouseService->printWarehousesToHtml();

$warehouseService->createWarehouse("Első raktár", "Miskolc, Valami utca 2.", 100);
$warehouseService->createWarehouse("Második raktár", "Mezőkövesd, Valami út 3.", 30);
$warehouseService->createWarehouse("Harmadik raktár", "Harsány, Valami utca 5.", 10);

$warehouseService->printWarehousesToHtml();

try {
    $warehouseService->addProducts(
        LawnMower::create(
            "12345678ABC", "Riwall PRO RPM 4234 P", 69990,
            Brand::create("Riwall", QualityCategory::MEDIUM),
            LawnMowerType::PETROL, 400, 20000, 45
        ),
        80
    );
    $warehouseService->addProducts(
        LawnMower::create(
            "12345678ABD", "Makita DLM460PG2", 224390,
            Brand::create("Makita", QualityCategory::HIGH),
            LawnMowerType::BATTERY, 460, 26100, 60
        ),
        30
    );
    $warehouseService->addProducts(
        Boiler::create(
            "12435678EFG", "Ariston Fais C 80l V/2 EU2 (3201281) Bojler", 38500,
            Brand::create("Ariston", QualityCategory::LOW),
            80, 1200
        ),
        20
    );
} catch (WarehouseException $ex) {
    echo $ex->getMessage() . "<br>";
}

$warehouseService->printWarehousesToHtml();

try {
    $warehouseService->takeProducts("12345678ABD", 25);
} catch (WarehouseException $ex) {
    echo $ex->getMessage() . "<br>";
}

$warehouseService->printWarehousesToHtml();
