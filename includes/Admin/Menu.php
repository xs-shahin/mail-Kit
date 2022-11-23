<?php

namespace MailKit\Admin;

use DOMDocument;
use DOMXPath;
use WP_Query;

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
        $args = array(
            'post_type'  => 'email_template',
            'meta_query' => array(
                array(
                    'key'     => 'tamplate_name',
                    'value'   => 'wc_processing_order',
                ),
                array(
                    'key'     => 'active_option',
                    'value'   => true,
                ),
            ),
        );
        $query = new WP_Query($args);
        $html  = get_post_meta($query->posts[0]->ID, 'email_html')[0];

        $tbody = substr($html, strpos($html, '<tbody'));
        $row = substr($tbody, strpos($tbody, '<tr>'), strpos($tbody, '</tbody>')-strpos($tbody, '<tr>'));
        
        $rows = '';
        $order = wc_get_order(661);
        // Iterating through each WC_Order_Item_Product objects
        foreach ($order->get_items() as $item_id => $item) {
            $id         = $item['product_id'];
            $product_name       = $item['name'];
            $item_qty   = $item['quantity'];
            $item_total      = $item['total'];
            $rows .= str_replace(["{{product_name}}", "{{item_qty}}", "{{item_total}}"], [$product_name, $item_qty, $item_total], $row);
        }
        $html = str_replace($row, $rows, $html);

        $details = [
            "{{shipping_method}}" => $order->shipping_method,
            "{{payment_method}}" => $order->payment_method_title,
            "{{total}}" => $order->total,

            "{{billing_first_name}}" => $order->billing_first_name,
            "{{billing_last_name}}" => $order->billing_last_name,
            "{{billing_company}}" => $order->billing_company,
            "{{billing_address_1}}" => $order->billing_address_1,
            "{{billing_address_2}}" => $order->billing_address_2,
            "{{billing_city}}" => $order->billing_city,
            "{{billing_state}}" => $order->billing_state,
            "{{billing_postcode}}" => $order->billing_postcode,
            "{{billing_country}}" => $order->billing_country,

            "{{shipping_first_name}}" => $order->shipping_first_name,
            "{{shipping_last_name}}" => $order->shipping_last_name,
            "{{shipping_company}}" => $order->shipping_company,
            "{{shipping_address_1}}" => $order->shipping_address_1,
            "{{shipping_address_2}}" => $order->shipping_address_2,
            "{{shipping_city}}" => $order->shipping_city,
            "{{shipping_state}}" => $order->shipping_state,
            "{{shipping_postcode}}" => $order->shipping_postcode,
            "{{shipping_country}}" => $order->shipping_country,
        ];

        $html = str_replace(array_keys($details), array_values($details), $html);

        ini_set("xdebug.var_display_max_children", '-1');
        ini_set("xdebug.var_display_max_data", '-1');
        ini_set("xdebug.var_display_max_depth", '-1');

       
        var_dump($html);
       
    }
}
