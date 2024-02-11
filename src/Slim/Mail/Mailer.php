<?php

namespace App\Slim\Mail;

interface Mailer
{
    /**
     * @param string $email
     * @return Mailer
     */
    public function setFrom(string $email): Mailer;

    /**
     * @param string $email
     * @return Mailer
     */
    public function addReplyTo(string $email): Mailer;

    /**
     * @param string $email
     * @return Mailer
     */
    public function whitCopyTo(string $email): Mailer;

    /**
     * @param string $subject
     * @return Mailer
     */
    public function subject(string $subject): Mailer;

    /**
     * @param string $body
     * @return Mailer
     */
    public function body(string $body): Mailer;

    /**
     * @param bool $isHtml
     * @return Mailer
     */
    public function isHtml(bool $isHtml): Mailer;

    /**
     * @param string $body
     * @return Mailer
     */
    public function addAttachement(string $body): Mailer;

    /**
     * @param string $recipient
     * @return Mailer
     */
    public function setRecipient(string $recipient): Mailer;

    /**
     * @return Mailer
     */
    public function send(): Mailer;
}
