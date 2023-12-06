<?php

namespace Grokhotov\Boxberry\Entity;

class Customer
{
    /**
     * ФИО
     */
    private ?string $fio = null;

    /**
     * Телефон
     */
    private ?string $phone = null;

    /**
     * Дополнительный телефон
     */
    private ?string $second_phone = null;

    /**
     * Email
     */
    private ?string $email = null;

    /**
     * Наименование организации
     */
    private ?string $org_name = null;

    /**
     * Адрес организации
     */
    private ?string $org_address = null;

    /**
     * ИНН
     */
    private ?string $org_inn = null;

    /**
     * КПП
     */
    private ?string $org_kpp = null;

    /**
     * Расчетный счет
     */
    private ?string $org_rs = null;

    /**
     * Кор. счет
     */
    private ?string $org_ks = null;

    /**
     * БИК банка
     */
    private ?string $org_bank_bik = null;

    /**
     * Наименование банка
     */
    private ?string $org_bank_name = null;

    /**
     * Почтовый индекс доставки
     */
    private ?int $index = null;

    /**
     * Город доставки
     */
    private ?string $city = null;

    /**
     * Адрес доставки
     */
    private ?string $address = null;

    /**
     * Время доставки от
     */
    private ?string $time_from = null;

    /**
     * Время доставки до
     */
    private ?string $time_to = null;

    /**
     * Время доставки от альтернативное
     */
    private ?string $time_from_second = null;

    /**
     * Время доставки до альтернативное
     */
    private ?string $time_to_second = null;

    /**
     * Время доставки в свободной форме
     */
    private ?string $delivery_time = null;


    public function getFio(): ?string
    {
        return $this->fio;
    }


    public function setFio(?string $fio): static
    {
        $this->fio = $fio;

        return $this;
    }


    public function getPhone(): ?string
    {
        return $this->phone;
    }


    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSecondPhone(): ?string
    {
        return $this->second_phone;
    }

    public function setSecondPhone(?string $second_phone): static
    {
        $this->second_phone = $second_phone;

        return $this;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }


    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getOrgName(): ?string
    {
        return $this->org_name;
    }


    public function setOrgName(?string $org_name): static
    {
        $this->org_name = $org_name;

        return $this;
    }


    public function getOrgAddress(): ?string
    {
        return $this->org_address;
    }

    public function setOrgAddress(string $org_address): static
    {
        $this->org_address = $org_address;

        return $this;
    }

    public function getOrgInn(): ?string
    {
        return $this->org_inn;
    }

    public function setOrgInn(?string $org_inn): static
    {
        $this->org_inn = $org_inn;

        return $this;
    }

    public function getOrgKpp(): ?string
    {
        return $this->org_kpp;
    }

    public function setOrgKpp(?string $org_kpp): static
    {
        $this->org_kpp = $org_kpp;

        return $this;
    }


    public function getOrgRs(): ?string
    {
        return $this->org_rs;
    }

    public function setOrgRs(?string $org_rs): static
    {
        $this->org_rs = $org_rs;

        return $this;
    }


    public function getOrgKs(): ?string
    {
        return $this->org_ks;
    }

    public function setOrgKs(?string $org_ks): static
    {
        $this->org_ks = $org_ks;

        return $this;
    }


    public function getOrgBankBik(): ?string
    {
        return $this->org_bank_bik;
    }


    public function setOrgBankBik(?string $org_bank_bik): static
    {
        $this->org_bank_bik = $org_bank_bik;

        return $this;
    }


    public function getOrgBankName(): ?string
    {
        return $this->org_bank_name;
    }


    public function setOrgBankName(?string $org_bank_name): static
    {
        $this->org_bank_name = $org_bank_name;
        return $this;
    }

    public function getIndex(): ?int
    {
        return $this->index;
    }

    public function setIndex(?int $index): static
    {
        $this->index = $index;

        return $this;
    }


    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }


    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }


    public function getTimeFrom(): ?string
    {
        return $this->time_from;
    }

    public function setTimeFrom(?string $time_from): static
    {
        $this->time_from = $time_from;

        return $this;
    }


    public function getTimeTo(): ?string
    {
        return $this->time_to;
    }

    public function setTimeTo(?string $time_to): static
    {
        $this->time_to = $time_to;

        return $this;
    }


    public function getTimeFromSecond(): ?string
    {
        return $this->time_from_second;
    }

    public function setTimeFromSecond(?string $time_from_second): static
    {
        $this->time_from_second = $time_from_second;

        return $this;
    }

    public function getTimeToSecond(): ?string
    {
        return $this->time_to_second;
    }

    public function setTimeToSecond(?string $time_to_second): static
    {
        $this->time_to_second = $time_to_second;

        return $this;
    }


    public function getDeliveryTime(): ?string
    {
        return $this->delivery_time;
    }


    public function setDeliveryTime(?string $delivery_time): static
    {
        $this->delivery_time = $delivery_time;

        return $this;
    }
}
