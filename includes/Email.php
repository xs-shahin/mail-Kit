<?php

namespace MailKit;

/**
 * The Email class
 */
class Email
{
    public function __construct()
    {
        add_action('phpmailer_init', [$this, 'mailtrap']);
        $this->emails();
    }

    /**
     * SMTP Configaration
     */
    function mailtrap($phpmailer)
    {
      
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'b9127245c5424d';
        $phpmailer->Password = '3ede9a616f5e61';
    }


    private function emails()
    {
      
        new Email\UserRegistration();
        new Email\Woocommerce\NewOrder();
        new Email\Woocommerce\CustomerProcessingOrder();
        // new Email\UserLogin();
    }
}
