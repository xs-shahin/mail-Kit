<?php

namespace MailKit\Admin;

/**
 * The menu handler class
 */
class Menu
{
    function __construct()
    {
        add_action('admin_menu', [$this, 'admin_menu']);
    }
    public function admin_menu()
    {
        add_menu_page(__('Mail Kit', 'mail-kit'), __('Mail Kit', 'mail-kit'), 'manage_options', 'mail-kit', [$this, 'plugin_page'], 'dashicons-email-alt');
    }
    public function plugin_page()
    {
        echo 'hello world';
    }
}
