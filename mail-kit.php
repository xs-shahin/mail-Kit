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
        defined('MK_VERSION', self::version);
        defined('MK_FILE', __FILE__);
        defined('MK_PATH', __DIR__);
        defined('MK_URL', plugins_url('', MK_FILE));
        defined('MK_ASSETS', MK_URL . '/assets');
    }
    public function activate()
    {
        $installed = get_option('mk_installed');
        if (!$installed) {
            update_option('mk_installed', time());
        }

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
