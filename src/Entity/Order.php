<?php

namespace Grokhotov\Boxberry\Entity;

use InvalidArgumentException;

class Order
{
    // Типы выдачи заказа получателю (TOI_*)
    const TOI_DELIVERY_WITHOUT_OPENING = 0; // выдача без вскрытия
    const TOI_DELIVERY_WITH_OPENING_AND_VERIFICATION = 1; // выдача со вскрытием и проверкой комплектности
    const TOI_PARTIAL_ISSUE = 2; // выдача части вложения

    // Виды доставки (vid)
    const PVZ = 1; // Доставка до пункта выдачи (ПВЗ)
    const COURIER = 2; // Курьерская доставка (КД)
    const RUSSIAN_POST = 3; // Доставка Почтой России (ПР)

    /**
     * Трекинг-код ранее созданной посылки
     */
    private ?string $track_num = null;

    /**
     * ID заказа в ИМ
     */
    private ?string $order_id = null;

    /**
     * Номер паллета
     * @var int
     */
    private int $pallet_num = 0;

    /**
     * Штрих-код заказа
     * @var string
     */
    private ?string $barcode = null;

    /**
     * Объявленная стоимость (price)
     * @var float
     */
    private int|float $valuated_amount = 0;

    /**
     * Сумма к оплате (payment_sum)
     * @var float
     */
    private int|float $payment_amount = 0;

    /**
     * Стоимость доставки (delivery_sum)
     * @var float
     */
    private int|float $delivery_amount = 0;

    /**
     * Вид доставки (1/2)
     * 1 – самовывоз (доставка до ПВЗ)
     * 2 – курьерская доставка (экспресс-доставка до получателя)
     * @var int
     */
    private int $vid = 1;

    /**
     * Код ПВЗ (shop_name)
     * @var string
     */
    private ?string $pvz_code = null;

    /**
     * Код пункта поступления (shop_name1)
     * @var string
     */
    private ?string $point_of_entry = null;

    /**
     * Получатель
     *
     * @var Customer
     */
    private ?Customer $customer = null;

    /**
     * Массив товаров в заказе
     *
     * @var array
     */
    private array $items = [];

    /**
     * Места в заказе (коробки)
     * @var array
     */
    private array $places = [];

    /**
     * Дата доставки
     * @var string
     */
    private string $delivery_date = '';

    /**
     * Комментарий
     * @var string
     */
    private string $comment = '';

    /**
     * Примечание к заказу
     * @var string
     */
    private string $notice = '';

    /**
     * Вид выдачи
     * @var int
     */
    private ?int $issue = null;

    /**
     * Наименование магазина отправителя для sms/e-mail оповещений и внутренней маркировки Boxberry
     * @var string
     */
    private ?string $sender_name = null;

    /**
     * Параметры для отправления Почтой России (vid = 3)
     * @var RussianPostParams
     */
    private ?RussianPostParams $russian_post_params = null;

    public function asArr(): array
    {
        $params = [];
        $params['updateByTrack'] = $this->track_num;
        $params['order_id'] = $this->order_id;
        $params['PalletNumber'] = $this->pallet_num;
        $params['barcode'] = $this->barcode;
        $params['price'] = $this->valuated_amount;
        $params['payment_sum'] = $this->payment_amount;
        $params['delivery_sum'] = $this->delivery_amount;
        $params['vid'] = $this->vid;
        $params['shop']['name'] = $this->pvz_code;
        $params['shop']['name1'] = $this->point_of_entry;

        $customer = [];
        $customer['fio'] = $this->customer->getFio();
        $customer['phone'] = $this->customer->getPhone();
        $customer['phone2'] = $this->customer->getSecondPhone();
        $customer['email'] = $this->customer->getEmail();
        $customer['name'] = $this->customer->getOrgName();
        $customer['address'] = $this->customer->getOrgAddress();
        $customer['inn'] = $this->customer->getOrgInn();
        $customer['kpp'] = $this->customer->getOrgKpp();
        $customer['r_s'] = $this->customer->getOrgRs();
        $customer['bank'] = $this->customer->getOrgBankName();
        $customer['kor_s'] = $this->customer->getOrgKs();
        $customer['bik'] = $this->customer->getOrgBankBik();
        $params['customer'] = $customer;

        $kurdost = [];
        $kurdost['index'] = $this->customer->getIndex();
        $kurdost['citi'] = $this->customer->getCity();
        $kurdost['addressp'] = $this->customer->getAddress();
        $kurdost['timesfrom1'] = $this->customer->getTimeFrom();
        $kurdost['timesto1'] = $this->customer->getTimeTo();
        $kurdost['timesfrom2'] = $this->customer->getTimeFromSecond();
        $kurdost['timesto2'] = $this->customer->getTimeToSecond();
        $kurdost['timep'] = $this->customer->getDeliveryTime();
        $kurdost['delivery_date'] = $this->delivery_date;
        $kurdost['comentk'] = $this->comment;

        // Доп. параметры для Почты России
        if (!empty($this->russian_post_params)) {
            $kurdost['type'] = $this->russian_post_params->getType();
            $kurdost['fragile'] = (string)($this->russian_post_params->isFragile()) ? '1' : '0';
            $kurdost['strong'] = (string)($this->russian_post_params->isStrong()) ? '1' : '0';
            $kurdost['optimize'] = (string)($this->russian_post_params->isOptimize()) ? '1' : '0';
            $kurdost['packing_type'] = $this->russian_post_params->getPackingType();
            $kurdost['packing_strict'] = $this->russian_post_params->isPackingStrict();
        }

        $params['kurdost'] = $kurdost;


        if (empty($this->places)) {
            throw new InvalidArgumentException('В заказе не заполнена информация о местах!');
        }

        /*if (count($this->places) > 5)
            throw new \InvalidArgumentException('В заказе не может быть больше 5 мест!');*/

        $weights = [];
        /**
         * @var Place $place
         */
        foreach ($this->places as $num => $place) {
            $num++;

            if ($num === 1) {
                $num = '';
            }

            $weights['weight' . $num] = $place->getWeight();
            $weights['barcode' . $num] = $place->getBarcode();
            $weights['x' . $num] = $place->getX();
            $weights['y' . $num] = $place->getY();
            $weights['z' . $num] = $place->getZ();
        }
        $params['weights'] = $weights;

        // Габариты места для Почты России
        if (!empty($this->russian_post_params)) {
            $params['weights']['x'] = $this->russian_post_params->getWidth();
            $params['weights']['y'] = $this->russian_post_params->getHeight();
            $params['weights']['z'] = $this->russian_post_params->getLength();
        }

        if (empty($this->items)) {
            throw new InvalidArgumentException('В заказе не заполнены товары!');
        }

        $items = [];
        /**
         * @var Item $item
         */
        foreach ($this->items as $num => $item) {
            $bbItem['id'] = (string)$item->getId();
            $bbItem['name'] = $item->getName();
            $bbItem['UnitName'] = $item->getUnit();
            $bbItem['nds'] = $item->getVat();
            $bbItem['price'] = $item->getAmount();
            $bbItem['quantity'] = $item->getQuantity();
            $items[] = $bbItem;
        }

        $params['items'] = $items;
        $params['issue'] = $this->issue;
        $params['notice'] = $this->notice;
        $params['sender_name'] = $this->sender_name;

        return $params;
    }


    public function getTrackNum(): ?string
    {
        return $this->track_num;
    }


    public function setTrackNum(string $track_num): static
    {
        $this->track_num = $track_num;

        return $this;
    }


    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    /**
     * @param string $order_id
     */
    public function setOrderId(string $order_id): void
    {
        $this->order_id = $order_id;
    }

    /**
     * @return int
     */
    public function getPalletNum(): int
    {
        return $this->pallet_num;
    }

    /**
     * @param int $pallet_num
     */
    public function setPalletNum(int $pallet_num): void
    {
        $this->pallet_num = $pallet_num;
    }

    /**
     * @return string
     */
    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     */
    public function setBarcode(string $barcode): void
    {
        $this->barcode = $barcode;
    }

    /**
     * @return float
     */
    public function getValuatedAmount(): float|int
    {
        return $this->valuated_amount;
    }

    /**
     * @param float $valuated_amount
     */
    public function setValuatedAmount(float $valuated_amount): void
    {
        $this->valuated_amount = $valuated_amount;
    }

    /**
     * @return float
     */
    public function getPaymentAmount(): float|int
    {
        return $this->payment_amount;
    }

    /**
     * @param float $payment_amount
     */
    public function setPaymentAmount(float $payment_amount): void
    {
        $this->payment_amount = $payment_amount;
    }

    /**
     * @return float
     */
    public function getDeliveryAmount(): float|int
    {
        return $this->delivery_amount;
    }

    /**
     * @param float $delivery_amount
     */
    public function setDeliveryAmount(float $delivery_amount): void
    {
        $this->delivery_amount = $delivery_amount;
    }

    /**
     * @return int
     */
    public function getVid(): int
    {
        return $this->vid;
    }

    /**
     * @param int $vid
     */
    public function setVid(int $vid): void
    {
        $this->vid = $vid;
    }

    /**
     * @return string
     */
    public function getPvzCode(): ?string
    {
        return $this->pvz_code;
    }

    /**
     * @param string $pvz_code
     */
    public function setPvzCode(string $pvz_code): void
    {
        $this->pvz_code = $pvz_code;
    }

    /**
     * @return string
     */
    public function getPointOfEntry(): ?string
    {
        return $this->point_of_entry;
    }

    /**
     * @param string $point_of_entry
     */
    public function setPointOfEntry(string $point_of_entry): void
    {
        $this->point_of_entry = $point_of_entry;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Item $item
     */
    public function setItems(Item $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @return array
     */
    public function getPlaces(): array
    {
        return $this->places;
    }

    /**
     * @param Place $place
     */
    public function setPlaces(Place $place): void
    {
        $this->places[] = $place;
    }

    /**
     * @return string
     */
    public function getDeliveryDate(): string
    {
        return $this->delivery_date;
    }

    /**
     * @param string $delivery_date
     */
    public function setDeliveryDate(string $delivery_date): void
    {
        $this->delivery_date = $delivery_date;
    }

    /**
     * @return string
     */
    public function getComment(): string
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

    /**
     * @return string
     */
    public function getNotice(): string
    {
        return $this->notice;
    }

    /**
     * @param string $notice
     */
    public function setNotice(string $notice): void
    {
        $this->notice = $notice;
    }

    /**
     * @return int
     */
    public function getIssue(): ?int
    {
        return $this->issue;
    }

    /**
     * @param int $issue
     */
    public function setIssue(int $issue): void
    {
        $this->issue = $issue;
    }

    /**
     * @return string
     */
    public function getSenderName(): ?string
    {
        return $this->sender_name;
    }

    /**
     * @param string $sender_name
     */
    public function setSenderName(string $sender_name): void
    {
        $this->sender_name = $sender_name;
    }

    /**
     * @return RussianPostParams
     */
    public function getRussianPostParams(): ?RussianPostParams
    {
        return $this->russian_post_params;
    }

    /**
     * @param RussianPostParams $russian_post_params
     */
    public function setRussianPostParams(RussianPostParams $russian_post_params): void
    {
        $this->russian_post_params = $russian_post_params;
    }
}
