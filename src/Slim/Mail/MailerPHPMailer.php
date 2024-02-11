<?php

namespace App\Slim\Mail;

use App\App;
use DI\DependencyException;
use DI\NotFoundException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MailerPHPMailer implements Mailer
{
    /**
     * @var PHPMailer
     */
    private PHPMailer $mailer;

    /**
     * @throws NotFoundExceptionInterface
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     */
    public function __construct()
    {
        $mailSettings = (object)App::settings()->get('mailer.phpmailer');

        $this->mailer = new PHPMailer($mailSettings->smtp_exceptions);
        $this->mailer->isSMTP();
        $this->mailer->SMTPAuth = true;

        $this->mailer->SMTPDebug = $mailSettings->smtp_debug;
        $this->mailer->Host = $mailSettings->smtp_host;
        $this->mailer->Username = $mailSettings->username;
        $this->mailer->Password = $mailSettings->password;
        $this->mailer->Port = $mailSettings->smtp_port;
        $this->mailer->SMTPOptions = $mailSettings->smtp_options;

        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    }

    /**
     * @param string $email
     * @return $this
     * @throws Exception
     */
    public function setFrom(string $email): MailerPHPMailer
    {
        $this->mailer->setFrom($email);

        return $this;
    }

    /**
     * @param string $email
     * @return $this
     * @throws Exception
     */
    public function addReplyTo(string $email): MailerPHPMailer
    {
        $this->mailer->addReplyTo($email);

        return $this;
    }

    /**
     * @param string $email
     * @return $this
     * @throws Exception
     */
    public function whitCopyTo(string $email): MailerPHPMailer
    {
        $this->mailer->addCC($email);

        return $this;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function subject(string $subject): MailerPHPMailer
    {
        $this->mailer->Subject = utf8_decode($subject);

        return $this;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function body(string $body): MailerPHPMailer
    {
        $this->mailer->Body = utf8_decode($body);

        return $this;
    }

    /**
     * @param string $path
     * @param string $name
     * @return MailerPHPMailer
     * @throws Exception
     */
    public function addAttachement(string $path, string $name = ''): MailerPHPMailer
    {
        $this->mailer->addAttachment($path, $name);

        return $this;
    }

    /**
     * @return Mailer
     * @throws Exception
     */
    public function send(): Mailer
    {
        $this->mailer->send();

        return $this;
    }

    /**
     * @param string $recipient
     * @return Mailer
     * @throws Exception
     */
    public function setRecipient(string $recipient): Mailer
    {
        $this->mailer->addAddress($recipient);

        return $this;
    }

    /**
     * @param bool $isHtml
     * @return Mailer
     */
    public function isHtml(bool $isHtml): Mailer
    {
        $this->mailer->isHTML($isHtml);

        return $this;
    }
}
