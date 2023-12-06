<?php

namespace Grokhotov\Boxberry\Entity;

class RussianPostParams
{
    // Типы отправлений (PT_*)
    const PT_POSILKA = 0;
    const PT_COURIER_ONLINE = 2;
    const PT_POSILKA_ONLINE = 3;
    const PT_POSILKA_ONE_CLASS = 5;

    // Типы упаковки
    const PACKAGE_IM_SMALLER_160 = 0;
    const PACKAGE_IM_MORE_160 = 1;
    const PACKAGE_BB_SMALLER_160 = 2;
    const PACKAGE_BB_MORE_160 = 3;

    /**
     * Тип отправления
     * @var int
     */
    private ?int $type = null;

    /**
     * Хрупкая посылка
     * @var bool
     */
    private bool $fragile = false;

    /**
     * Строгий тип
     * @var bool
     */
    private bool $strong = false;

    /**
     * Оптимизация тарифа
     * @var bool
     */
    private bool $optimize = false;

    /**
     * Тип упаковки
     * @var int
     */
    private ?int $packing_type = null;

    /**
     * Длина в см
     * @var null
     */
    private $length = null;

    /**
     * Ширина в см
     * @var null
     */
    private $width = null;

    /**
     * Высота в см
     * @var null
     */
    private $height = null;

    /**
     * Строгая упаковка
     * @var bool
     */
    private bool $packing_strict = false;

    /**
     * @return int
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isFragile(): bool
    {
        return $this->fragile;
    }

    /**
     * @param bool $fragile
     */
    public function setFragile(bool $fragile): void
    {
        $this->fragile = $fragile;
    }

    /**
     * @return bool
     */
    public function isStrong(): bool
    {
        return $this->strong;
    }

    /**
     * @param bool $strong
     */
    public function setStrong(bool $strong): void
    {
        $this->strong = $strong;
    }

    /**
     * @return bool
     */
    public function isOptimize(): bool
    {
        return $this->optimize;
    }

    /**
     * @param bool $optimize
     */
    public function setOptimize(bool $optimize): void
    {
        $this->optimize = $optimize;
    }

    /**
     * @return int
     */
    public function getPackingType(): ?int
    {
        return $this->packing_type;
    }

    /**
     * @param int $packing_type
     */
    public function setPackingType(int $packing_type): void
    {
        $this->packing_type = $packing_type;
    }

    /**
     * @return bool
     */
    public function isPackingStrict(): bool
    {
        return $this->packing_strict;
    }

    /**
     * @param bool $packing_strict
     */
    public function setPackingStrict(bool $packing_strict): void
    {
        $this->packing_strict = $packing_strict;
    }

    /**
     * @return null
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param null $length
     */
    public function setLength($length): void
    {
        $this->length = $length;
    }

    /**
     * @return null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param null $width
     */
    public function setWidth($width): void
    {
        $this->width = $width;
    }

    /**
     * @return null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param null $height
     */
    public function setHeight($height): void
    {
        $this->height = $height;
    }
}