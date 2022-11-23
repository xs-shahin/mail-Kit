<?php

namespace MailKit\Email;

use WP_Query;

class UserRegistration
{
    /**
     * User Registration class
     */
    public function __construct()
    {
        add_filter('wp_new_user_notification_email', [$this, 'filterMailDetails'], 10, 3);
    }

    /**
     * Get user email after completed registration
     */
    public function filterMailDetails($emailDetails, $user, $appName)
    {
        $args = array(
            'post_type'  => 'email_template',
            'meta_query' => array(
                array(
                    'key'     => 'tamplate_name',
                    'value'   => 'new_user_register',
                ),
                array(
                    'key'     => 'active_option',
                    'value'   => true,
                ),
            ),
        );
        $query = new WP_Query($args);
        $key = get_password_reset_key($user);
        $details = [
            "{{app_name}}" => $appName,
            "{{reset_url}}" => network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login'),
            "{{display_name}}" => $user->display_name,
            "{{user_url}}" => $user->user_url,
            "{{user_email}}" => $user->user_email,
        ];

        $emailDetails['from'] = get_option('admin_email');
        if (isset($query->posts[0])) {
            $emailDetails['message'] = str_replace(array_keys($details), array_values($details),  get_post_meta($query->posts[0]->ID, 'email_html')[0]);
        }

        $emailDetails['subject'] =str_replace(array_keys($details), array_values($details),  get_post_meta($query->posts[0]->ID, 'email_subject')[0]);
        $emailDetails['headers'] = [
            'From: MailKit <' . $emailDetails['from'] . "> \r\n",
            'Reply-To: <' . $emailDetails['from'],
            'Content-Type: text/html; charset=UTF-8'
        ];

        return $emailDetails;
    }
}
