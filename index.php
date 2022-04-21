<?php

require 'vendor/autoload.php';

use Warehouse\Model\LawnMowerType;
use Warehouse\Model\QualityCategory;
use Warehouse\Service\ProductService;
use Warehouse\Service\WarehouseService;

$productService = new ProductService();
$warehouseService = new WarehouseService();

$warehouseService->printWarehouses();

$warehouseService->createWarehouse("Első raktár", "Miskolc, Valami utca 2.", 100);
$warehouseService->createWarehouse("Második raktár", "Mezőkövesd, Valami út 3.", 30);
$warehouseService->createWarehouse("Harmadik raktár", "Harsány, Valami utca 5.", 10);

$warehouseService->printWarehouses();

$warehouseService->addProducts(
    $productService->createLawnMower(
        "12345678ABC", "Riwall PRO RPM 4234 P", 69990,
        $productService->createBrand("Riwall", QualityCategory::MEDIUM),
        LawnMowerType::PETROL, 400, 20000, 45
    ),
    80
);
$warehouseService->addProducts(
    $productService->createLawnMower(
        "12345678ABD", "Makita DLM460PG2", 224390,
        $productService->createBrand("Makita", QualityCategory::HIGH),
        LawnMowerType::BATTERY, 460, 26100, 60
    ),
    30
);
$warehouseService->addProducts(
    $productService->createBoiler(
        "12435678EFG", "Ariston Fais C 80l V/2 EU2 (3201281) Bojler", 38500,
        $productService->createBrand("Ariston", QualityCategory::LOW),
        80, 1200
    ),
    20
);

$warehouseService->printWarehouses();

$warehouseService->takeProducts("Makita DLM460PG2", 25);

$warehouseService->printWarehouses();
