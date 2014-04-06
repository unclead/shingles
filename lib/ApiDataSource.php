<?php
/**
 * Created by PhpStorm.
 * User: unclead.nsk
 * Date: 06.04.14
 * Time: 15:17
 */

require_once 'DataSourceInterface.php';

/**
 * Class ApiDataSource
 *
 * http://{URL}/{TOKEN}/{COUNT}/{OFFSET}
 * {TOKEN} – индивидуальный ключ кандидата
 * {COUNT} – количество текстов для выдачи (необязательный параметр, значение по умолчанию = 10). Доступные значения [1;100000]. Для полной выборки используйте значение «-1».
 * {OFFSET} – сдвиг выборки текстов для выдачи (необязательный параметр, значение по умолчанию = 0). Доступные значения [0;99999].
 * Выходные данные метода представлены в формате JSON.
 */
class ApiDataSource implements DataSourceInterface
{

    const REQUEST_GET = 'GET';

    private $token = null;

    private $url;

    private $count = 10;

    private $offset = 0;

    public function setUrl($url)
    {
        $url = rtrim(preg_replace('/http(s)?:\/\//','', $url),'/');
        $this->url = $url;
    }

    public function getUrl()
    {
        return 'http://' . $this->url;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setCount($count)
    {
        $this->count = $count;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getData($use_api = false)
    {
        if($use_api) {
            $data = $this->sendRequest();
            //$filename = dirname(__FILE__) . '/../data.txt';
            //file_put_contents($filename, $data->response);
            return $data->response;
        } else {
            $text = 'Моя основная задача была разработать систему проверки ссылок. Причем эта система должна была отрабатывать максимально быстро и запускаться как страница. То есть при заходе клиента из браузера на страницу ему должно было вывестись сообщение с просьбой об ожидании и в это время начиналась проверка ссылок. Естественно, в один поток это очень долго.Для этого нужно использовать много потоко (триды, форки). Но так как PCNTL невозможен в условиях mod_php пришлось делать так как есть.Хостинг я использую чужой, что не позволяет мне попросить админов перекомпилировать пхп со всеми нужным мне параметрами.';

            $data = array();
            for ($i = 0; $i < 10; $i++) {
                $data[$i] = $text;
            }
        }

        return $data;
    }

    /**
     * Отправка запроса
     * @return mixed ответ
     */
    private function sendRequest()
    {
        $url = $this->getUrl() . '/' . $this->getToken() . '/' . $this->getCount() . '/' . $this->getOffset();

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::REQUEST_GET);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));

        if(property_exists($response,'error')) {
            throw new Exception($response->error->message);
        }

        curl_close($ch);

        return $response;

    }
} 