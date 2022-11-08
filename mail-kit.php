<?php

/**
 * Plugin Name: Mail Kit
 * Plugin URI:  Plugin URL Link
 * Author:      Plugin Author Name
 * Author URI:  Plugin Author Link
 * Description: This plugin does wonders
 * Version:     0.1.0
 * License:     GPL-2.0+
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: prefix-plugin-name
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The Main Plugin Class
 */
final class Mail_Kit
{
    const version = '1.0';

    /**
     * Class contructor
     * 
     * @return void
     */
    private function __construct()
    {
        $this->define_constants();
        register_activation_hook(__FILE__, [$this, 'activate']);
        add_action('plugins_loaded', [$this, 'init_plugin']);
    }


    public static function write_log($log)
    {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
    /**
     * Initializes Singleton Instance
     * 
     * @return \Mail_Kit
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define all constant variable.
     * 
     * @return void
     */
    public function define_constants()
    {
        define('MK_VERSION', self::version);
        define('MK_FILE', __FILE__);
        define('MK_PATH', __DIR__);
        define('MK_URL', plugins_url('', MK_FILE));
        define('MK_ASSETS', MK_URL . '/assets');
    }

    /**
     * initialize the plugin.
     * 
     * @return void
     */
    public function init_plugin()
    {
        new \MailKit\Email\SMTP();
        new \MailKit\Email\UserRegistration();
        if (is_Admin()) {
            new \MailKit\Admin();
        }
    }
    /**
     * Do stuff upon plugin activation
     */
    public function activate()
    {
        /**
         * Save plugin installation time.
         */
        $installed = get_option('mk_installed');
        if (!$installed) {
            update_option('mk_installed', time());
        }

        /**
         * Update plugin version.
         * 
         * @param string $option
         */
        update_option('mk_version', MK_VERSION);
    }
}

/**
 * Initializes the main plugin
 * 
 * @return \Mail_Kit
 */
function mail_kit()
{
    return Mail_Kit::init();
}

/**
 * Kick off the plugin
 */
mail_kit();
