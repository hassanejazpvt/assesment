<?php

declare(strict_types=1);

namespace Hassan\Assesment\Core;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private $mailer;
    private $to = [];
    private $subject;
    private $from;
    private $body;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host       = env('SMTP_HOST');
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = env('SMTP_USER');
        $this->mailer->Password   = env('SMTP_PASS');
        $this->mailer->SMTPSecure = env('SMTP_ENCRYPTION');
        $this->mailer->Port       = env('SMTP_PORT');
        $this->mailer->isHTML(true);

        $this->from(env('EMAIL_FROM_ADDRESS'), env('EMAIL_FROM_NAME'));
    }

    /**
     * @param string|array $addresses
     */
    public function to($addresses): self
    {
        $addresses = (array) $addresses;
        foreach ($addresses as $address) {
            $this->mailer->addAddress($address);
        }

        return $this;
    }

    public function from(string $email, string $name): self
    {
        $this->mailer->setFrom($email, $name);

        return $this;
    }

    public function body(string $body): self
    {
        $this->mailer->Body = $body;

        return $this;
    }

    public function subject(string $subject): self
    {
        $this->mailer->Subject = $subject;

        return $this;
    }

    /**
     * @throws Error
     */
    public function send(): bool
    {
        try {
            return $this->mailer->send();
        } catch (\Exception $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }
}
