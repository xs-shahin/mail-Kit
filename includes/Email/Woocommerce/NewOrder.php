<?php

namespace MailKit\Email\Woocommerce;

use WP_Query;

class NewOrder
{
  /**
   * User Registration class
   */
  public function __construct()
  {
    add_action('woocommerce_email', [$this, 'disablEmailsNewOrders']);
    add_filter('woocommerce_order_status_pending_to_processing_notification', [$this, 'filterMailDetails'], 10, 2);
  }

  /**
   * remove action to disbale default mail 
   */
  public function disablEmailsNewOrders($email_class)
  {
    remove_action('woocommerce_order_status_pending_to_processing_notification', [$email_class->emails['WC_Email_New_Order'], 'trigger']);
    remove_action('woocommerce_order_status_pending_to_completed_notification', [$email_class->emails['WC_Email_New_Order'], 'trigger']);
    remove_action('woocommerce_order_status_pending_to_on-hold_notification', [$email_class->emails['WC_Email_New_Order'], 'trigger']);
    remove_action('woocommerce_order_status_failed_to_processing_notification', [$email_class->emails['WC_Email_New_Order'], 'trigger']);
    remove_action('woocommerce_order_status_failed_to_completed_notification', [$email_class->emails['WC_Email_New_Order'], 'trigger']);
    remove_action('woocommerce_order_status_failed_to_on-hold_notification', [$email_class->emails['WC_Email_New_Order'], 'trigger']);
  }

  /**
   * Get user email after completed registration
   */
  public function filterMailDetails($orderId, $order)
  {
    $args = array(
      'post_type'  => 'email_template',
      'meta_query' => array(
        array(
          'key'     => 'tamplate_name',
          'value'   => 'woocommerce_order_status_pending_to_processing_for_admin',
        ),
        array(
          'key'     => 'active_option',
          'value'   => true,
        ),
      ),
    );
    $query = new WP_Query($args);

    $email = get_option('admin_email');
    if (isset($query->posts[0])) {
      $html  = get_post_meta($query->posts[0]->ID, 'email_html')[0];
      $tbody = substr($html, strpos($html, 'tbody'));
      $row = substr($tbody, strpos($tbody, '<tr'), strpos($tbody, '</tbody>')- strpos($tbody, '<tr>'));
      $rows = '';

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
        "{{order_id}}" => $orderId,

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

      $message  = str_replace(array_keys($details), array_values($details), $html);
    }

    $to =  get_option('admin_email');
    $subject =str_replace(array_keys($details), array_values($details),  get_post_meta($query->posts[0]->ID, 'email_subject')[0]);
    $headers = [
      'From: ' . $email . "\r\n",
      'Reply-To: ' . $email . "\r\n",
      'Content-Type: text/html; charset=UTF-8'
    ];

    wp_mail($to, $subject, $message, $headers);
  
  }
}
