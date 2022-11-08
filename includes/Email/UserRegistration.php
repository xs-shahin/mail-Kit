<?php

namespace MailKit\Email;

use Mail_Kit;

class UserRegistration
{
    /**
     * User Registration class
     */
    public function __construct()
    {
        add_filter('user_registration_email', [$this, 'get_user_email_after_registration']);
    }

    /**
     * Get user email after completed registration
     */
    public function get_user_email_after_registration($user_email)
    {
        Mail_Kit::write_log($user_email);
        $this->send_mail_after_registration($user_email);
        return $user_email;
    }

    /**
     * Email send after completed registration
     */
    private function send_mail_after_registration($user_email)
    {
        //user posted variables
        $email = 'shahinahnab@gmail.com';
        $message = 'This is an custom mail to user after registration.';

        //php mailer variables
        $to = $user_email;
        $subject = "Some text in subject...";
        $headers = 'From: ' . $email . "\r\n" .
            'Reply-To: ' . $email . "\r\n";

        //Here put your Validation and send mail
        $sent = wp_mail($to, $subject, strip_tags($message), $headers);
        //message sent!
        if ($sent) {
            Mail_Kit::write_log('Mail send successfully');
        }
        //message wasn't sent
        else {
            Mail_Kit::write_log('Mail send Unsuccessfull');
        }
    }
}
