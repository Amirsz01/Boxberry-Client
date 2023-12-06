<?php

namespace Grokhotov\Boxberry\Entity;

class Intake
{
    /**
     * Почтовый индекс
     * @var int
     */
    private ?int $zip = null;

    /**
     * Город
     * @var string
     */
    private ?string $city = null;

    /**
     * Улица
     * @var string
     */
    private ?string $street = null;

    /**
     * Дом
     * @var string
     */
    private ?string $house = null;

    /**
     * Строение
     * @var string
     */
    private ?string $building = null;

    /**
     * Корпус
     * @var string
     */
    private ?string $corpus = null;

    /**
     * Квартира
     * @var string
     */
    private ?string $flat = null;

    /**
     * Контактное лицо
     * @var string
     */
    private ?string $contact_person = null;

    /**
     * Контактный телефон
     * @var string
     */
    private ?string $contact_phone = null;

    /**
     * Дата забора
     * @var string
     */
    private ?string $taking_date = null;

    /**
     * Время забора с
     * @var string
     */
    private string $taking_time_from = '10:00';

    /**
     * Время забора по
     * @var string
     */
    private string $taking_time_to = '18:00';

    /**
     * Количество мест
     * @var int
     */
    private int $places = 1;

    /**
     * Объем в м3
     * @var float
     */
    private float $volume = 0.5;

    /**
     * Вес в кг
     * @var float
     */
    private int|float $weight = 1;

    /**
     * Комментарий
     * @var string
     */
    private ?string $comment = null;

    /**
     * Формирует массив параметров для запроса
     *
     * @return array
     */
    public function asArr(): array
    {
        $params = [];
        $params['zip'] = $this->zip;
        $params['city'] = $this->city;
        $params['street'] = $this->street;
        $params['house'] = $this->house;
        $params['building'] = $this->corpus;
        $params['housing'] = $this->building;
        $params['flat'] = $this->flat;
        $params['contact_person'] = $this->contact_person;
        $params['contact_phone'] = $this->contact_phone;
        $params['taking_date'] = date('d.m.Y', strtotime($this->taking_date));
        $params['taking_time_from'] = $this->taking_time_from;
        $params['taking_time_to'] = $this->taking_time_to;
        $params['seats_count'] = $this->places;
        $params['volume'] = $this->volume;
        $params['weight'] = $this->weight;
        $params['comment'] = $this->comment;

        return $params;
    }

    /**
     * @return int
     */
    public function getZip(): ?int
    {
        return $this->zip;
    }

    /**
     * @param int $zip
     */
    public function setZip(int $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getHouse(): ?string
    {
        return $this->house;
    }

    /**
     * @param string $house
     */
    public function setHouse(string $house): void
    {
        $this->house = $house;
    }

    /**
     * @return string
     */
    public function getBuilding(): ?string
    {
        return $this->building;
    }

    /**
     * @param string $building
     */
    public function setBuilding(string $building): void
    {
        $this->building = $building;
    }

    /**
     * @return string
     */
    public function getCorpus(): ?string
    {
        return $this->corpus;
    }

    /**
     * @param string $corpus
     */
    public function setCorpus(string $corpus): void
    {
        $this->corpus = $corpus;
    }

    /**
     * @return string
     */
    public function getFlat(): ?string
    {
        return $this->flat;
    }

    /**
     * @param string $flat
     */
    public function setFlat(string $flat): void
    {
        $this->flat = $flat;
    }

    /**
     * @return string
     */
    public function getContactPerson(): ?string
    {
        return $this->contact_person;
    }

    /**
     * @param string $contact_person
     */
    public function setContactPerson(string $contact_person): void
    {
        $this->contact_person = $contact_person;
    }

    /**
     * @return string
     */
    public function getContactPhone(): ?string
    {
        return $this->contact_phone;
    }

    /**
     * @param string $contact_phone
     */
    public function setContactPhone(string $contact_phone): void
    {
        $this->contact_phone = $contact_phone;
    }

    /**
     * @return string
     */
    public function getTakingDate(): ?string
    {
        return $this->taking_date;
    }

    /**
     * @param string $taking_date
     */
    public function setTakingDate(string $taking_date): void
    {
        $this->taking_date = $taking_date;
    }

    /**
     * @return string
     */
    public function getTakingTimeFrom(): string
    {
        return $this->taking_time_from;
    }

    /**
     * @param string $taking_time_from
     */
    public function setTakingTimeFrom(string $taking_time_from): void
    {
        $this->taking_time_from = $taking_time_from;
    }

    /**
     * @return string
     */
    public function getTakingTimeTo(): string
    {
        return $this->taking_time_to;
    }

    /**
     * @param string $taking_time_to
     */
    public function setTakingTimeTo(string $taking_time_to): void
    {
        $this->taking_time_to = $taking_time_to;
    }

    /**
     * @return int
     */
    public function getPlaces(): int
    {
        return $this->places;
    }

    /**
     * @param int $places
     */
    public function setPlaces(int $places): void
    {
        $this->places = $places;
    }

    /**
     * @return float
     */
    public function getVolume(): float
    {
        return $this->volume;
    }

    /**
     * @param float $volume
     */
    public function setVolume(float $volume): void
    {
        $this->volume = $volume;
    }

    /**
     * @return float
     */
    public function getWeight(): float|int
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}