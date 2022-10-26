<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Users;

/**
 * Class AppCreditCard
 * @package Source\Models\CafeApp
 */
class Whatsapp
{
    /** @var string */
    private $apiurl;

    /** @var string */
    private $apikey;

    /** @var string */
    private $endpoint;

    /** @var array */
    private $build;

    /** @var string */
    private $callback;

    /**
     * AppCreditCard constructor.
     */
    public function __construct()
    {
        $this->apiurl = CONF_WHATSAPP_PATH.CONF_WHATSAPP_CODIGO;
        $this->apikey = CONF_WHATSAPP_KEY;
    }

    public function status()
    {
        $this->build = [];
        $this->endpoint = "/api/v1/status";
        $this->get();
    }

    public function neWqrCode()
    {
        $this->build = [];
        $this->endpoint = "/api/v1/generate_qrcode";
        $this->get();
    }

    public function sendMessage($number, $message)
    {
        $numero = deixarNumero($number);
        $this->build = [
            "number" => "+55{$numero}",
            "message" => str_replace("<br>", "\r\n", $message),
            "quoed_message_id" => "string"
        ];
        $this->endpoint = "/api/v1/send_message";
        $this->post();
        if(!empty($this->callback()->status) && $this->callback()->status == true){
            return true;
        }else{
            return false;
        }
    }
    /**
     * @return mixed
     */
    public function callback()
    {
        return $this->callback;
    }

    /**
     * @param string $number
     * @return string
     */
    private function clear(string $number): string
    {
        return preg_replace("/[^0-9]/", "", $number);
    }

    /**
     *
     */
    private function post()
    {
        $url = $this->apiurl . $this->endpoint;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($this->build),
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.CONF_WHATSAPP_KEY,
                'Content-Type: application/json'
            ),
        ));

        $this->callback = json_decode(curl_exec($curl));
        curl_close($curl);
    }

    private function get()
    {
        $url = $this->apiurl . $this->endpoint;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.CONF_WHATSAPP_KEY
            ),
        ));

        $this->callback = json_decode(curl_exec($curl));
        curl_close($curl);
    }
}