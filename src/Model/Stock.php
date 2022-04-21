<?php

namespace Warehouse\Model;

class Stock
{
    /**
     * Termék
     *
     * @var Product
     */
    private Product $product;

    /**
     * Mennyiség (darabszám)
     *
     * @var int
     */
    private int $piece;

    /** @return Product */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /** @param Product $product */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /** @return int */
    public function getPiece(): int
    {
        return $this->piece;
    }

    /** @param int $piece */
    public function setPiece(int $piece): void
    {
        $this->piece = $piece;
    }

    /**
     * Darabszám növelése
     *
     * @param int $piece
     */
    public function addPiece(int $piece): void
    {
        $this->piece += $piece;
    }

    /**
     * Darabszám csökkentése
     *
     * @param int $piece
     */
    public function takePiece(int $piece): void
    {
        $this->piece -= $piece;
    }
}
