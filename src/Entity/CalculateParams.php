<?php

namespace Grokhotov\Boxberry\Entity;

class CalculateParams
{
    /**
     * Вес заказа в граммах
     */
    private ?int $weight = null;

    /**
     * Код ПВЗ
     */
    private ?int $pvz = null;

    /**
     * Стоимость заказа
     */
    private ?float $amount = null;

    /**
     * Сумма к оплате
     */
    private ?float $paymentAmount = null;

    /**
     * Стоимость доставки
     */
    private ?float $deliveryAmount = null;

    /**
     * Код пункта  приема посылок
     */
    private ?int $targetStart = null;

    /**
     * Высота коробки в сантиметрах
     */
    private ?float $height = null;

    /**
     * Ширина коробки в сантиметрах
     */
    private ?float $width = null;

    /**
     * Глубина коробки в сантиметрах
     */
    private ?float $depth = null;

    /**
     * Почтовый индекс города для курьерской доставки
     */
    private ?int $zip = null;

    /**
     * Параметр добавляется к DeliveryCosts, позволяет получать расчеты с учетом наценок установленных в ЛК - Настройки средств интеграции (sucrh=1)
     */
    private bool $suchr = false;

    public function asArr(): array
    {
        $params['weight'] = $this->weight;
        $params['ordersum'] = $this->amount;
        $params['deliverysum'] = $this->deliveryAmount;
        $params['paysum'] = $this->paymentAmount;
        $params['targetstart'] = $this->targetStart;
        $params['height'] = $this->height;
        $params['width'] = $this->width;
        $params['depth'] = $this->depth;
        if ($this->suchr) {
            $params['sucrh'] = $this->suchr;
        }

        if (!empty($this->pvz)) {
            $params['target'] = $this->getPvz();
        } else {
            $params['zip'] = $this->getZip();
        }

        return $params;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): CalculateParams
    {
        $this->weight = $weight;

        return $this;
    }

    public function getPvz(): ?int
    {
        return $this->pvz;
    }

    public function setPvz($pvz): CalculateParams
    {
        $this->pvz = $pvz;

        return $this;
    }


    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): CalculateParams
    {
        $this->amount = $amount;
        return $this;
    }

    public function getPaymentAmount(): ?float
    {
        return $this->paymentAmount;
    }

    public function setPaymentAmount(?float $paymentAmount): CalculateParams
    {
        $this->paymentAmount = $paymentAmount;

        return $this;
    }

    public function getDeliveryAmount(): ?float
    {
        return $this->deliveryAmount;
    }

    public function setDeliveryAmount($deliveryAmount): CalculateParams
    {
        $this->deliveryAmount = $deliveryAmount;
        return $this;
    }


    public function getTargetStart(): ?int
    {
        return $this->targetStart;
    }


    public function setTargetStart(int $targetStart): CalculateParams
    {
        $this->targetStart = $targetStart;
        return $this;
    }


    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): CalculateParams
    {
        $this->height = $height;
        return $this;
    }


    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): CalculateParams
    {
        $this->width = $width;
        return $this;
    }


    public function getDepth(): ?float
    {
        return $this->depth;
    }

    public function setDepth(float $depth): CalculateParams
    {
        $this->depth = $depth;
        return $this;
    }


    public function getZip(): ?int
    {
        return $this->zip;
    }

    public function setZip(int $zip): CalculateParams
    {
        $this->zip = $zip;
        return $this;
    }


    public function isSuchr(): bool
    {
        return $this->suchr;
    }

    public function setSuchr(bool $suchr): CalculateParams
    {
        $this->suchr = $suchr;

        return $this;
    }
}