<?php

namespace Warehouse\Model;

class Brand
{
    /**
     * Márkanév
     *
     * @var string
     */
	private string $name;

    /**
     * Minőségkategória
     *
     * @var int
     */
    private int $qualityCategory;

    /**
     * Márka objektum létrehozása
     *
     * @param string $name Márkanév
     * @param int $qualityCategory Minőségkategória
     * @return Brand
     */
    public static function create(string $name, int $qualityCategory): Brand
    {
        $brand = new Brand();
        $brand->setName($name);
        $brand->setQualityCategory($qualityCategory);
        return $brand;
    }

    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }

    /** @param string $name */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /** @return int */
    public function getQualityCategory(): int
    {
        return $this->qualityCategory;
    }

    /** @param int $qualityCategory */
    public function setQualityCategory(int $qualityCategory): void
    {
        $this->qualityCategory = $qualityCategory;
    }
}
