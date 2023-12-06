<?php

namespace Grokhotov\Boxberry\Entity;

class TariffInfo
{

    private ?float $price = null;

    private ?float $price_base = null;

    private ?float $price_service = null;

    private ?string $delivery_period = null;

    public function __construct(?array $response = null)
    {
        if ($response) {
            $this->setPrice($response['price']);
            $this->setPriceBase($response['price_base']);
            $this->setPriceService($response['price_service']);
            $this->setDeliveryPeriod($response['delivery_period']);
        }
    }


    public function getPrice(): ?float
    {
        return $this->price;
    }


    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }


    public function getPriceBase(): ?float
    {
        return $this->price_base;
    }

    public function setPriceBase(?float $price_base): static
    {
        $this->price_base = $price_base;

        return $this;
    }


    public function getPriceService(): ?float
    {
        return $this->price_service;
    }

    public function setPriceService(?float $price_service): static
    {
        $this->price_service = $price_service;

        return $this;
    }

    public function getDeliveryPeriod(): ?string
    {
        return $this->delivery_period;
    }

    public function setDeliveryPeriod(?string $delivery_period): static
    {
        $this->delivery_period = $delivery_period;

        return $this;
    }


}