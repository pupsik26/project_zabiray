<?php

class CurlAuthorizationHelper
{
    const TOKEN = '/token';
    const CARDS = '/cards';

    private $link = 'https://testapi.zabiray.ru';
    private $authorization = '';
    private $username = 'test';
    private $password = 'test1234';
    private $contentType;
    private $countConnect = 10;
    public $response;

    public function __construct()
    {
        $this->token = $_COOKIE['token'] ?? null;
        if (is_null($this->token)) {
            $this->authorization();
        } else {
            $this->authorization = 'Authorization: Bearer ' . $this->token;
        }
    }

    public function authorization()
    {
        $this->contentType = 'application/x-www-form-urlencoded';
        $this->curlInit("username={$this->username}&password={$this->password}" , $this->link . self::TOKEN);
        if ($this->responseProcessing()) {
            $this->authorization = 'Authorization: Bearer ' . $this->response['access_token'];
            setcookie("token", $this->response['access_token'], time()+3600);
        }
    }

    public function getCards()
    {
        $this->contentType = 'application/json';
        $this->curlInit(json_encode([
            'id' => 123
        ]), $this->link . self::CARDS);
        if ($this->responseProcessing()) {
            return $this->response;
        } else {
            return [];
        }
    }

    public function curlInit($data, $link)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: {$this->contentType}", $this->authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec ($ch);
        curl_close($ch);
        $this->response = json_decode($response, true);
    }

    public function responseProcessing()
    {
        if ($this->countConnect <= 0) {
            echo '<script>';
            echo 'alert("Запрашиваемый ресурс не отвечает. Попробуйте обновить страницу.")';
            echo '</script>';
        } elseif (isset($this->response['detail'])) {
            switch ($this->response['detail']) {
                case 'Not authenticated':
                    $this->writeLog('NA');
                    return false;
                case 'Could not validate credentials':
                    $this->writeLog('CNVC');
                    $this->authorization();
                    $this->getCards();
                    $this->countConnect -= 1;
                    return true;
                case 'Incorrect username or password':
                    $this->writeLog('IUOP');
                    return false;
            }
        }
        return true;
    }

    public function writeLog($details)
    {
        $fileLog = '../log/log_' . date('dmY') . '.txt';

        switch ($details) {
            case 'NA':
                $error = date('d.m.Y H:i:s') . '| Нет авторизации.';
                break;
            case 'CNVC':
                $error = date('d.m.Y H:i:s') . '| Истекло время токена.';
                break;
            case 'IUOP':
                $error = date('d.m.Y H:i:s') . '| Неврный логин или пароль.';
                break;
        }
        file_put_contents($fileLog, PHP_EOL . $error, FILE_APPEND);
    }

    public function varDump($data)
    {
        echo '<pre>';
        var_dump($data);
        die();
    }

}