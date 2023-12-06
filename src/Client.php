<?php /** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */

namespace Grokhotov\Boxberry;

use Grokhotov\Boxberry\Entity\CalculateParams;
use Grokhotov\Boxberry\Entity\Intake;
use Grokhotov\Boxberry\Entity\Order;
use Grokhotov\Boxberry\Entity\TariffInfo;
use Grokhotov\Boxberry\Exception\BoxBerryException;
use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Client implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private const PARCEL_TRACK = 'track';
    private const PARCEL_ORDER_ID = 'order_id';

    private array $tokenList = [];
    private ?string $currentToken = null;
    private string $apiUrl = 'https://api.boxberry.ru/json.php';

    private HttpClientInterface $httpClient;

    public function __construct(?string $apiUrl = null)
    {
        $this->httpClient = HttpClient::createForBaseUri($apiUrl ?? $this->apiUrl, [
            'timeout' => 300,
        ]);
    }

    /**
     * Возвращает токен из хранилища по ключу
     *
     * @param string $key - Ключ токена
     * @return string|false
     */
    public function getToken(string $key): bool|string
    {
        return $this->tokenList[$key] ?? false;
    }

    /**
     * Заносит токен в хранилище
     *
     * @param string $key - Ключ токена
     * @param string $token - Токен доступа к API
     */
    public function setToken(string $key, string $token): static
    {
        $this->tokenList[$key] = $token;
        $this->setCurrentToken($key);

        return $this;
    }

    /**
     * Задает токен, который будет использован клиентом для запросов к API
     *
     * @param string $key - Ключ токена
     * @throws InvalidArgumentException
     */
    public function setCurrentToken(string $key): static
    {
        $this->currentToken = $this->getToken($key);
        if (empty($this->currentToken)) {
            throw new InvalidArgumentException('Не выбран API-токен!');
        }

        return $this;
    }

    public function getCurrentToken(): ?string
    {
        if (empty($this->currentToken)) {
            throw new InvalidArgumentException('Не выбран API-токен!');
        }

        return $this->currentToken;
    }

    /**
     * Инициализирует вызов к API
     */
    private function callApi(string $type, string $method, array $params = []): array
    {
        if ($type === 'POST') {
            $data = $params;
            $params = [];
            if ($method === 'ParcelInfo') {
                $params = $data;
            } else {
                $params['sdata'] = json_encode($data);
            }
            unset($data);
        }

        $params['token'] = $this->getCurrentToken();
        $params['method'] = $method;

        switch ($type) {
            case 'GET':
                $this->logger?->info("BoxBerry {$type} API request {$method}: " . http_build_query($params));
                $response = $this->httpClient->request($type, '', options: ['query' => $params]);
                break;
            case 'POST':
                $this->logger?->info("BoxBerry API {$type} request {$method}: " . json_encode($params));
                $response = $this->httpClient->request($type, '', options: ['form_params' => $params]);
                break;
            default:
                $this->logger?->info("BoxBerry API: method {$type} not exist");
                throw new BoxBerryException('Метод ' . $type . ' не существует');
        }

        $request = http_build_query($params);

        $json = $response->getContent();

        if ($this->logger) {
            $headers = $response->getHeaders();
            $headers['http_status'] = $response->getStatusCode();
            $this->logger->info("BoxBerry API response {$method}: " . $json, $headers);
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new BoxBerryException('Неверный код ответа от сервера BoxBerry при вызове метода ' . $method . ': ' . $response->getStatusCode(), $response->getStatusCode(), $json, $request);
        }

        $respBB = $response->toArray();

        if (empty($respBB)) {
            throw new BoxBerryException('От сервера BoxBerry при вызове метода ' . $method . ' пришел пустой ответ', $response->getStatusCode(), $json, $request);
        }

        if (!empty($respBB['err'])) {
            throw new BoxBerryException('От сервера BoxBerry при вызове метода ' . $method . ' получена ошибка: ' . $respBB['err'], $response->getStatusCode(), $json, $request);
        }


        if (!empty($respBB[0]['err'])) {
            throw new BoxBerryException('От сервера BoxBerry при вызове метода ' . $method . ' получена ошибка: ' . $respBB[0]['err'], $response->getStatusCode(), $json, $request);
        }

        return $respBB;
    }

    /**
     * Возврат списка ПВЗ
     *
     * @param boolean $prepaid true - все ПВЗ, false - с возможностью оплаты при получении
     * @param boolean $short - true - краткая информация о ПВЗ с датой последнего изменения
     * @param int|null $city_code - код города BB, если нужны ПВЗ в заданном городе
     * @return array
     * @throws BoxBerryException
     */
    public function getPvzList(bool $prepaid = false, bool $short = false, int $city_code = null): array
    {
        $method = 'ListPoints';
        $params = [];

        if ($short) {
            $method .= 'Short';
        }

        if ($prepaid) {
            $params['prepaid'] = 1;
        }

        if ($city_code) {
            $params['CityCode'] = $city_code;
        }

        return $this->callApi('GET', $method, $params);
    }

    /**
     * Возвращает список городов, в которых есть пункты выдачи заказов
     *
     * @param boolean $all - true - список городов, в которых осуществляется доставка + в которых есть ПВЗ
     * @return array
     * @throws BoxBerryException
     */
    public function getCityList(bool $all = false): array
    {
        $method = 'ListCities';
        if ($all) {
            $method .= 'Full';
        }

        return $this->callApi('GET', $method);
    }

    /**
     * Возвращает список почтовых индексов, для которых возможна курьерская доставка
     *
     * @return array
     * @throws BoxBerryException
     */
    public function getZipList(): array
    {
        return $this->callApi('GET', 'ListZips');
    }

    /**
     * Проверка возможности КД в заданном индексе
     *
     * @param int $index - Почтовый индекс получателя
     * @return array
     * @throws BoxBerryException
     */
    public function checkZip(int $index): array
    {
        $response = $this->callApi('GET', 'ZipCheck', ['Zip' => $index]);
        return $response[0];
    }

    /**
     * Информация о статусах заказа
     *
     * @param string $order_id - ID заказа магазина или трекномер BB
     * @param bool $all true - полная информация, false - краткая информация
     * @return array
     * @throws BoxBerryException
     */
    public function getOrderStatuses(string $order_id, bool $all = false): array
    {
        $method = 'ListStatuses';
        if ($all) {
            $method .= 'Full';
        }

        return $this->callApi('GET', $method, ['ImId' => $order_id]);
    }

    /**
     * Информация о услугах по отправлению
     *
     * @param string $order_id - ID заказа магазина или трекномер BB
     * @return array|bool
     * @throws BoxBerryException
     */
    public function getOrderServices(string $order_id): array|bool
    {
        $response = $this->callApi('GET', 'ListServices', ['ImId' => $order_id]);
        if (empty($response) || empty($response[0]['Sum'])) {
            return false;
        }

        return $response;
    }

    /**
     * Список городов, в которых осуществляется курьерская доставка
     *
     * @return array
     * @throws BoxBerryException
     */
    public function getCourierCities(): array
    {
        return $this->callApi('GET', 'CourierListCities');
    }

    /**
     * Список точек приема посылок
     *
     * @return array
     * @throws BoxBerryException
     */
    public function getPointsForParcels(): array
    {
        return $this->callApi('GET', 'PointsForParcels');
    }


    /**
     * Информация о ПВЗ
     *
     * @param int $point_id
     * @param bool $photo
     * @return array
     * @throws BoxBerryException
     */
    public function pointDetails(int $point_id, bool $photo = false): array
    {
        return $this->callApi('GET', 'PointsDescription', ['code' => $point_id, 'photo' => (int)$photo]);
    }

    /**
     * Расчета тарифа на доставку
     *
     * @param CalculateParams $calcParams
     * @return TariffInfo
     * @throws BoxBerryException
     */
    public function calcTariff(CalculateParams $calcParams): TariffInfo
    {
        $params = $calcParams->asArr();
        $response = $this->callApi('GET', 'DeliveryCosts', $params);

        return new TariffInfo($response);
    }

    /**
     * Этикетка по заказу
     *
     * @param string $track - трекномер BB
     * @return array
     * @throws BoxBerryException
     */
    public function getLabel(string $track): array
    {
        return $this->callApi('GET', 'ParselCheck', ['ImId' => $track]);
    }

    /**
     * Получить файл этикетки
     *
     * @param $track - трекномер BB
     * @return ResponseInterface
     * @throws BoxBerryException|TransportExceptionInterface
     */
    public function getLabelFile($track): ResponseInterface
    {
        $response = $this->getLabel($track);
        if ($response) {
            return $this->getFileByLink($response['label']);
        }
        throw new BoxBerryException('Ошибка получения этикетки');
    }

    /**
     * @param $order_ids - список заказов
     *
     * @param string $parcel_type - тип выборки (трек номер посылки или номер заказа магазина)
     * @return array|void
     * @throws BoxBerryException
     */
    public function getAllOrdersInfo($order_ids, string $parcel_type = self::PARCEL_ORDER_ID)
    {
        if (!in_array($parcel_type, $this->getParcelsType())) {
            $parcel_type = self::PARCEL_ORDER_ID;
        }
        $parcels = array_map(static function ($order) use ($parcel_type) {
            return [$parcel_type => trim($order)];
        }, $order_ids);
        if (!$parcels) {
            return;
        }
        return $this->callApi('POST', 'ParcelInfo', ['parcels' => $parcels]);
    }

    /**
     * Универсальный метод получения файлов по ссылке
     *
     * @param $link - ссылка на файл
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function getFileByLink($link): ResponseInterface
    {
        return $this->httpClient->request('GET', $link);
    }

    /**
     * Полная информация о заказе по трек номеру
     *
     * @param $track_id - трекномер BB
     * @return array
     * @throws BoxBerryException
     *
     */
    public function getOrderInfoByTrack($track_id): array
    {
        return $this->callApi('POST', 'ParcelInfo', ['parcels' => [['track' => $track_id]]]);
    }

    /**
     * Полная информация о заказе по ID заказа в магазине
     *
     * @param $order_id - ID заказа магазина
     * @return array
     * @throws BoxBerryException
     */
    public function getOrderInfoByOrderId($order_id): array
    {
        return $this->callApi('POST', 'ParcelInfo', ['parcels' => [['order_id' => $order_id]]]);
    }

    /**
     * Получает информацию по заказам, которые фактически переданы на доставку в BoxBerry, но они еще не доставлены получателю
     *
     * @return array
     * @throws BoxBerryException
     */
    public function getOrdersInProgress(): array
    {
        return $this->callApi('GET', 'OrdersBalance');
    }

    /**
     * Позволяет получить список созданных через API посылок
     * Если не указывать диапазоны дат, то будет возвращен последний созданный заказ
     *
     * @param string|null $from - период от (дата в любом формате)
     * @param string|null $to - период до (дата в любом формате)
     * @return array
     * @throws BoxBerryException
     */
    public function getOrderList(?string $from = null, ?string $to = null): array
    {
        $params = [];

        if ($from) {
            $params['from'] = date('Ymd', strtotime($from));
        }

        if ($to) {
            $params['to'] = date('Ymd', strtotime($to));
        }

        return $this->callApi('GET', 'ParselStory', $params);
    }

    /**
     * Создание заявки на забор
     *
     * @param Intake $intake - заявка на забор
     * @return int - номер созданной заявки на забор
     * @throws BoxBerryException
     */
    public function createIntake(Intake $intake): int
    {
        $params = $intake->asArr();
        $response = $this->callApi('GET', 'CreateIntake', $params);
        if (empty($response['message'])) {
            throw new BoxBerryException('От сервера BoxBerry не пришел номер заявки!');
        }

        return $response['message'];
    }


    /**
     * Позволяет получить список всех трекинг кодов посылок которые есть в кабинете но не были сформированы в акт
     *
     * @param bool $arr - вернуть список в виде массива
     * @return array|string
     * @throws BoxBerryException
     */
    public function getOrdersNotAct(bool $arr = false): array|string
    {
        $response = $this->callApi('GET', 'ParselList');
        if ($arr) {
            return explode(',', $response['ImIds']);
        }

        return $response['ImIds'];
    }


    /**
     * Позволяет удалить заказ по ID заказа магазина
     *
     * @param string $order_id - ID заказа магазина
     * @param int $cancelType - вариант отмены заказа (1 - удалить посылку, 2 - отозвать посылку)
     * @return bool
     * @throws BoxBerryException
     */
    public function deleteOrderByOrderId(string $order_id, int $cancelType = 2): bool
    {
        $response = $this->callApi('GET', 'CancelOrder', ['orderid' => $order_id, 'cancelType' => $cancelType]);
        if (!empty($response['err'])) {
            return true;
        }

        return false;
    }

    /**
     * Позволяет удалить заказ по трекномеру BB
     *
     * @param $track - трекномер BB
     * @param int $cancelType
     * @return bool
     * @throws BoxBerryException
     */
    public function deleteOrderByTrack($track, int $cancelType = 2): bool
    {
        $response = $this->callApi('GET', 'CancelOrder', ['track' => $track, 'cancelType' => $cancelType]);
        if (!empty($response['err'])) {
            return true;
        }

        return false;
    }

    /**
     * Создание заказа
     *
     * @param Order $order - Параметры заказа
     * @return array
     * @throws BoxBerryException
     */
    public function createOrder(Order $order): array
    {
        $params = $order->asArr();

        return $this->callApi('POST', 'ParselCreate', $params);
    }


    /**
     * Создание акта передачи посылок в BB.
     * Внимание! сервис работает только с посылками созданными через API ЛК.
     *
     * @param $track_nums
     * @return array
     * @throws BoxBerryException
     */
    public function createOrdersTransferAct($track_nums): array
    {
        if (empty($track_nums) || !is_array($track_nums)) {
            throw new InvalidArgumentException('Не передан массив трек-номеров заказов!');
        }

        return $this->callApi('GET', 'ParselSend', ['ImIds' => implode(',', $track_nums)]);
    }

    /**
     * Позволяет получить список созданных через API актов передачи заказов
     * Если не указывать диапазоны дат, то будет возвращен последний созданный акт
     *
     * @param string|null $from - период от (дата в любом формате)
     * @param string|null $to - период до (дата в любом формате)
     * @return array
     * @throws BoxBerryException
     */
    public function getActsList(string $from = null, string $to = null): array
    {
        $params = [];

        if ($from) {
            $params['from'] = date('Ymd', strtotime($from));
        }

        if ($to) {
            $params['to'] = date('Ymd', strtotime($to));
        }

        return $this->callApi('GET', 'ParselSendStory', $params);
    }

    /**
     * @param string $typeFile - тип файла, принимает значения 'act' - Акт приема передачи посылки, 'barcodes' - печатная форма этикеток
     * @param array $params - параметры
     * @return mixed
     * @throws TransportExceptionInterface
     */
    private function parcelFiles(string $typeFile = '', array $params = []): Response
    {
        $uri = 'https://api.boxberry.ru/parcel-files/' . $typeFile;
        $httpClient = HttpClient::createForBaseUri($uri);
        $params['token'] = $this->getCurrentToken();

        return $httpClient->request($typeFile, '', options: ['query' => $params]);
    }

    /**
     * Позволяет получить файл "Акта приема передачи посылки (АПП)" по номеру АПП
     *
     * @param $parcelId - номер акта приема передачи посылки
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getParcelFileActToId($parcelId): Response
    {
        return $this->parcelFiles('act', ['upload_id' => $parcelId]);
    }

    /**
     * Позволяет получить файл акта ТМЦ (если подключена услуга в ЛК) по номеру АПП
     *
     * @param $parcelId - номер акта приема передачи посылки
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getParcelFileActTMCToId($parcelId): Response
    {
        return $this->parcelFiles('act', ['upload_id' => $parcelId, 'type_act' => 'tmc']);
    }

    /**
     * Позволяет получить печатную форму этикеток по номеру АПП
     *
     * @param $parcelId - номер акта приема передачи посылки
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getParcelFileBarcodesToId($parcelId): Response
    {
        return $this->parcelFiles('barcodes', ['upload_id' => $parcelId]);
    }

    private function getParcelsType(): array
    {
        return [self::PARCEL_TRACK, self::PARCEL_ORDER_ID];
    }
}