<?php

namespace Source\Support;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Source\Core\Connect;
use Source\Core\View;
use Source\Models\EmailConfig;

/**
 * Class Email
 * @package Source\Support
 */
class Email
{
    /** @var array */
    private $data;

    /** @var PHPMailer */
    private $mail;

    /** @var Message */
    private $message;

    /**
     * @var View
     */
    private $view;

    /**
     * Email constructor.
     */
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->data = new \stdClass();
        $this->message = new Message();
        $this->view = new View(__DIR__."/../../shared/views/email/");

        $this->mail->isSMTP();
        $this->mail->Host = CONF_MAIL_HOST;
        $this->mail->Port = CONF_MAIL_PORT;
        $this->mail->SMTPSecure = CONF_MAIL_OPTION_SMTP_SECURE;
        $this->mail->SMTPAutoTLS = CONF_MAIL_OPTION_SMTP_AUTOTLS;
        $this->mail->SMTPAuth = CONF_MAIL_OPTION_SMTP_AUTH;
        $this->mail->Username = CONF_MAIL_USER;
        $this->mail->Password = CONF_MAIL_PASS;
        $this->mail->isHTML(CONF_MAIL_OPTION_HTML);
        $this->mail->CharSet = CONF_MAIL_OPTION_CHARSET;
    }

    /**
     * @param string $view
     * @param array $data
     * @return Email
     */
    public function view(string $view = "default", array $data): Email
    {
        $this->data->body = $this->view->render($view, $data);
        return $this;
    }


    /**
     * @param string $subject
     * @param string $recipientEmail
     * @param string $recipientName
     * @param null|string $body
     * @param null|string $type
     * @return Email
     */
    public function bootstrap(string $subject, string $recipientEmail, string $recipientName, ?string $body = null, ?string $type = null): Email
    {
        $this->data->subject = $subject;
        $this->data->recipient_email = $recipientEmail;
        $this->data->recipient_name = $recipientName;
        $this->data->body = $body;
        $this->data->type = $type;
        return $this;
    }

    /**
     * @param string $filePath
     * @param string $fileName
     * @return Email
     */
    public function attach(string $filePath, string $fileName): Email
    {
        $this->data->attach[$filePath] = $fileName;
        return $this;
    }

    /**
     * @param string $email
     * @param string $name
     * @return Email
     */
    public function addBcc(string $email, string $name): Email
    {
        $this->data->addBcc[$email] = $name;
        return $this;
    }

    /**
     * @param $from
     * @param $fromName
     * @return bool
     */
    public function send(string $from = CONF_MAIL_SENDER['address'], string $fromName = CONF_MAIL_SENDER["name"]): bool
    {
        if (empty($this->data)) {
            $this->message->error("Erro ao enviar, favor verifique os dados");
            return false;
        }

        if (!is_email($this->data->recipient_email)) {
            $this->message->warning("O e-mail de destinatário não é válido");
            return false;
        }

        if (!is_email($from)) {
            $this->message->warning("O e-mail de remetente não é válido");
            return false;
        }

        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->body);
            $this->mail->addAddress($this->data->recipient_email, $this->data->recipient_name);
            $this->mail->setFrom($from, $fromName);

            if (!empty($this->data->attach)) {
                foreach ($this->data->attach as $path => $name) {
                    $this->mail->addAttachment($path, $name);
                }
            }

            if (!empty($this->data->addBcc)) {
                foreach ($this->data->addBcc as $mail => $name) {
                    $this->mail->addBCC($mail, $name);
                }
            }

            if($this->data->type){
                $configMail = (new EmailConfig())->find("type = :type", "type={$this->data->type}")->fetch();
                if($configMail){
                    $this->mail->Host = $configMail->host;
                    $this->mail->Port =  $configMail->port;
                    $this->mail->Username =  $configMail->user;
                    $this->mail->Password =  $configMail->pass;

                    $from = $configMail->address;
                    $fromName = $configMail->name;
                    $this->data->body = $this->data->body."<br>".$configMail->signature;
                }
            }

            $this->mail->send();
            return true;
        } catch (Exception $exception) {
            $this->message->error($exception->getMessage());
            return false;
        }
    }

    /**
     * @param string $from
     * @param string $fromName
     * @return bool
     */
    public function queue(string $from = CONF_MAIL_SENDER['address'], string $fromName = CONF_MAIL_SENDER["name"])
    {
        try {
            $stmt = Connect::getInstance()->prepare(
                "INSERT INTO
                    mail_queue (subject, body, from_email, from_name, recipient_email, recipient_name, attachment, bcc, type)
                    VALUES (:subject, :body, :from_email, :from_name, :recipient_email, :recipient_name, :attachment, :bcc, :type)"
            );

            $stmt->bindValue(":subject", $this->data->subject, \PDO::PARAM_STR);
            $stmt->bindValue(":body", $this->data->body, \PDO::PARAM_STR);

            if($this->data->type){
                $configMail = (new EmailConfig())->find("type = :type", "type={$this->data->type}")->fetch();
                if($configMail){
                    $from = $configMail->address;
                    $fromName = $configMail->name;
                }
            }

            $stmt->bindValue(":from_email", $from, \PDO::PARAM_STR);
            $stmt->bindValue(":from_name", $fromName, \PDO::PARAM_STR);
            $stmt->bindValue(":recipient_email", $this->data->recipient_email, \PDO::PARAM_STR);
            $stmt->bindValue(":recipient_name", $this->data->recipient_name, \PDO::PARAM_STR);
            $stmt->bindValue(":type", $this->data->type, \PDO::PARAM_STR);

            $attach = null;
            if(!empty($this->data->attach)){
                foreach ($this->data->attach as $url => $name) {
                    $attach .= "{$url};";
                }
            }

            $bbc = null;
            if(!empty($this->data->addBcc)){
                foreach ($this->data->addBcc as $email => $name) {
                    $bbc .= "{$email};";
                }
            }
            $stmt->bindValue(":attachment", $attach, \PDO::PARAM_STR);
            $stmt->bindValue(":bcc", $bbc, \PDO::PARAM_STR);

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
        $stmt = Connect::getInstance()->query("SELECT * FROM mail_queue WHERE sent_at IS NULL");
        if ($stmt->rowCount()) {
            foreach ($stmt->fetchAll() as $send) {
                $email = $this->bootstrap(
                    $send->subject,
                    $send->recipient_email,
                    $send->recipient_name,
                    $send->body
                );

                if(!empty($send->attachment)){
                    $attachment = explode(";", $send->attachment);
                    foreach ($attachment as $item) {
                        if(!$item){
                            continue;
                        }

                        $arquivo = "../{$item}";
                        $path_parts = pathinfo($item);
                        $email->attach($arquivo,  $path_parts['basename']);
                    }
                }

                if(!empty($send->bcc)){
                    $bcc = explode(";", $send->bcc);
                    foreach ($bcc as $item) {
                        if(!$item){
                            continue;
                        }

                        $email->addBcc($item, $item);
                    }
                }

                if ($email->send($send->from_email, $send->from_name, $send->type)) {
                    usleep(1000000 / $perSecond);
                    Connect::getInstance()->exec("UPDATE mail_queue SET sent_at = NOW() WHERE id = {$send->id}");
                }
            }
        }
    }

    /**
     * @return PHPMailer
     */
    public function mail(): PHPMailer
    {
        return $this->mail;
    }

    /**
     * @return array|\stdClass
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Message
     */
    public function message(): Message
    {
        return $this->message;
    }
}