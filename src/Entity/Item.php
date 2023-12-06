<?php

namespace Grokhotov\Boxberry\Entity;

class Item
{
    /**
     * ID товара
     * @var string
     */
    private string|int $id = 1;

    /**
     * Наименование товара
     * @var string
     */
    private ?string $name = null;

    /**
     * Единица измерения
     * @var string
     */
    private string $unit = 'шт';

    /**
     * Ставка НДС в %
     * @var int
     */
    private int $vat = 20;

    /**
     * Стоимость
     * @var int
     */
    private int $amount = 0;

    /**
     * Количество
     * @var int
     */
    private int $quantity = 0;

    /**
     * @return string
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return int
     */
    public function getVat(): int
    {
        return $this->vat;
    }

    /**
     * @param int $vat
     */
    public function setVat(int $vat): void
    {
        $this->vat = $vat;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}