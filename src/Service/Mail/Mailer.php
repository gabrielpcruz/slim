<?php

namespace App\Service\Mail;

interface Mailer
{
    /**
     * @param string $email
     * @return mixed
     */
    public function setFrom(string $email): Mailer;

    /**
     * @param string $email
     * @return mixed
     */
    public function addReplyTo(string $email): Mailer;

    /**
     * @param string $email
     * @return mixed
     */
    public function whitCopyTo(string $email): Mailer;

    /**
     * @param string $subject
     * @return mixed
     */
    public function subject(string $subject): Mailer;

    /**
     * @param string $body
     * @return mixed
     */
    public function body(string $body): Mailer;

    /**
     * @param bool $isHtml
     * @return mixed
     */
    public function isHtml(bool $isHtml): Mailer;

    /**
     * @param string $body
     * @return mixed
     */
    public function addAttachement(string $body): Mailer;

    /**
     * @param string $recipient
     * @return mixed
     */
    public function setRecipient(string $recipient): Mailer;

    /**
     * @return Mailer
     */
    public function send(): Mailer;
}