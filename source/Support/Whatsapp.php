<?php

namespace Source\Support;

use CoffeeCode\DataLayer\DataLayer;
use Source\Core\Connect;
use Source\Models\Users;

/**
 * Class Whatsapp
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

    private $data;

    private $message;

    /**
     * AppCreditCard constructor.
     */
    public function __construct()
    {
        $this->apiurl = CONF_WHATSAPP_PATH.CONF_WHATSAPP_CODIGO;
        $this->apikey = CONF_WHATSAPP_KEY;

        $this->data = new \stdClass();
        $this->message = new Message();
    }

    public function bootstrap(string $number, string $message): Whatsapp
    {
        $this->data->number = deixarNumero($number);
        $this->data->message = $message;
        return $this;
    }

    public function send(): bool
    {
        if (empty($this->data)) {
            $this->message->error("Erro ao enviar, favor verifique os dados");
            return false;
        }

        $this->build = [
            "number" => $this->data->number,
            "message" => str_replace("<br>", "\r\n", $this->data->message),
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

    public function attach(string $filePath, string $fileName): Whatsapp
    {
        $this->data->attach[$filePath] = $fileName;
        return $this;
    }

    public function queue(): bool
    {
        try {
            $stmt = Connect::getInstance()->prepare(
                "INSERT INTO
                    whatsapp_queue (message, recipient_number)
                    VALUES (:message, :recipient_number)"
            );

            $stmt->bindValue(":message", $this->data->message, \PDO::PARAM_STR);
            $stmt->bindValue(":recipient_number", $this->data->number, \PDO::PARAM_STR);

            $attach = null;
            if(!empty($this->data->attach)){
                foreach ($this->data->attach as $url => $name) {
                    $attach .= "{$url};";
                }
                $stmt->bindValue(":attachment", $attach, \PDO::PARAM_STR);
            }

            $stmt->execute();
            return true;
        } catch (\PDOException $exception) {
            $this->message->error($exception->getMessage());
            return false;
        }
    }

    /**
     * @param int $perSecond
     */
    public function sendQueue(int $perSecond = 5)
    {
        $stmt = Connect::getInstance()->query("SELECT * FROM whatsapp_queue WHERE sent_at IS NULL");
        if ($stmt->rowCount()) {
            foreach ($stmt->fetchAll() as $send) {
                $whatsapp = $this->bootstrap(
                    $send->recipient_number,
                    $send->message
                );

                if ($whatsapp->send()) {
                    usleep(1000000 / $perSecond);
                    Connect::getInstance()->exec("UPDATE whatsapp_queue SET sent_at = NOW() WHERE id = {$send->id}");
                }
            }
        }
    }

    /**
     * @return Message
     */
    public function message(): Message
    {
        return $this->message;
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