<?php

namespace Source\Support;

use Source\Core\Connect;

class WhatsappAPI
{

    private $id;

    private $key;

    private $data;

    private $message;

    public function __construct()
    {
        $this->id = 1927;
        $this->key = "b5330cc09a1629c6bbaf799662d6d383b53964ec";

        $this->data = new \stdClass();
        $this->message = new Message();
    }

    public function bootstrap(string $number, string $message): WhatsappAPI
    {
        $this->data->number = $number;
        $this->data->message = $message;
        return $this;
    }

    public function send(): bool
    {
        if (empty($this->data)) {
            $this->message->error("Erro ao enviar, favor verifique os dados");
            return false;
        }

        try {
            var_dump($this->post());
            return true;
        } catch (\Exception $exception) {
            $this->message->error($exception->getMessage());
            return false;
        }
    }

    public function attach(string $filePath, string $fileName): WhatsappAPI
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
            return $this->message->error($exception->getMessage());
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

    private function post()
    {
        $data = ['to' => $this->data->number, 'msg' => $this->data->message];

        $url = "https://onyxberry.com/services/wapi/Client/sendMessage";
        $url = $url.'/'.$this->id.'/'.$this->key;
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec( $ch );
        return $response;
    }
}
