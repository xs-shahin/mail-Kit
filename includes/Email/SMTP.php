<?php

namespace MailKit\Email;

class SMTP
{
    /**
     * SMTP configaration.
     */
    public function __construct()
    {
        add_action('phpmailer_init', [$this, 'mailtrap']);
    }

    public function mailtrap($phpmailer)
    {
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '22a81ba3679d96';
        $phpmailer->Password = '66065e75e5d5ff';
    }
}
