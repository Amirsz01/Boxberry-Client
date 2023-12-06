<?php

namespace Grokhotov\Boxberry\Entity;

class Place
{
    /**
     * Вес в граммах (Минимальное значение 5 г, максимальное – 31000 г.)
     * @var int
     */
    private int $weight = 5;

    /**
     * Штрих-код места
     * @var string
     */
    private string $barcode = '';

    /**
     * Длина
     * @var float
     */
    private float $x;

    /**
     * Ширина
     * @var float
     */
    private float $y;

    /**
     * Высота
     * @var float
     */
    private float $z;

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getBarcode(): string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): static
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * @return float
     */
    public function getX(): float
    {
        return $this->x;
    }

    public function setX(float $x): static
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function setY(mixed $y): static
    {
        $this->y = $y;

        return $this;
    }

    public function getZ(): float
    {
        return $this->z;
    }

    public function setZ(float $z): static
    {
        $this->z = $z;

        return $this;
    }
}
