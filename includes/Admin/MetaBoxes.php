<?php

namespace MailKit\Admin;

use WP_Query;

class MetaBoxes
{

    public $templates = [
        'woocommerce_low_stock' => 'WC - Low Stock',
        'woocommerce_no_stock' => 'WC - No Stock',
        'woocommerce_product_on_backorder' => 'WC - Product No Back Order',
        'woocommerce_order_status_pending_to_processing_for_admin'  => 'WC - Order  Pending to processing for admin',
        'woocommerce_order_status_pending_to_processing_for_customer'  => 'WC - Order  Pending to processing for customer',
        'woocommerce_order_status_pending_to_completed' => 'WC - Order  Pending to completed',
        'woocommerce_order_status_processing_to_cancelled' => 'WC - Order  Processing to cancelled',
        'woocommerce_order_status_pending_to_failed' => 'WC - Order  Pending to failed',
        'woocommerce_order_status_pending_to_on-hold' => 'WC - Order  Pending to on hold',
        'woocommerce_order_status_failed_to_processing' => 'WC - Order  failed to processing',
        'woocommerce_order_status_failed_to_completed' => 'WC - Order  failed to completed',
        'woocommerce_order_status_failed_to_on-hold' => 'WC - Order  failed to on hold',
        'woocommerce_order_status_cancelled_to_processing' => 'WC - Order  Cancelled to processing',
        'woocommerce_order_status_cancelled_to_completed' => 'WC - Order  Cancelled to completed',
        'woocommerce_order_status_cancelled_to_on-hold' => 'WC - Order  Cancelled to on hold',
        'woocommerce_order_status_on-hold_to_processing' => 'WC - Order  On hold to processing',
        'woocommerce_order_status_on-hold_to_cancelled' => 'WC - Order  On hold to Cancelled',
        'woocommerce_order_status_on-hold_to_failed' => 'WC - Order  On hold to failed',
        'woocommerce_order_status_completed' => 'WC - Order  Comoleted',
        'woocommerce_order_fully_refunded' => 'WC - Order  Fully Refunded',
        'woocommerce_order_partially_refunded' => 'WC - Order  partially Refunded',
        'woocommerce_new_customer_note' => 'WC - New User Note',
        'woocommerce_created_customer' => 'WC - Created Customer',
        'new_user_register' => 'New user registration',
    ];


    public function __construct()
    {
        add_action('add_meta_boxes_email_template', [$this, 'create']);
        add_action('save_post', [$this, 'updateSavePost']);
    }

    // Create Custom meta boxes for Email Template
    public function create()
    {
        add_meta_box('email_template_id', 'Email templates meta', [$this, 'emailTemplatesMetabox']);
    }
    public function updateSavePost()
    {
        global $post;
        if (
            isset($post->ID) &&
            wp_verify_nonce($_POST['email_meta_nonce'], "email_meta_nonce_action") &&
            is_user_logged_in() &&
            current_user_can('administrator')
        ) {

            if (isset($_POST['email_subject'])) {
                update_post_meta($post->ID, 'email_subject', sanitize_text_field($_POST['email_subject']));
            }

            if (isset($_POST['email_html'])) {
                update_post_meta($post->ID, 'email_html', wp_kses($_POST['email_html'],  \MailKit\Helpers\Utils::get_kses_array()));
            }

            $tamplateName   = sanitize_text_field($_POST['tamplate_name']);
            if (isset($_POST['tamplate_name']) && isset($this->templates[$tamplateName])) {
                update_post_meta($post->ID, 'tamplate_name', $tamplateName);
            }

            if (isset($_POST['active_option']) && $_POST['active_option'] == 'check') {
                $args = array(
                    'post_type'              => array('email_template'),
                    'meta_query'             => array(
                        array(
                            'key'       => 'tamplate_name',
                            'value'     => $tamplateName,
                        ),
                    ),
                );
                // The Query
                $query = new WP_Query($args);
                foreach ($query->posts as $singlePost) {
                    update_post_meta($singlePost->ID, 'active_option', false);
                }
                update_post_meta($post->ID, 'active_option', true);
            } else {
                update_post_meta($post->ID, 'active_option', false);
            }
        }
    }

    public function emailTemplatesMetabox()
    {
        global $post;
        wp_nonce_field('email_meta_nonce_action', 'email_meta_nonce');
        $data = get_post_custom($post->ID);

        $email_subject = isset($data['email_subject']) ? esc_attr($data['email_subject'][0]) : '';
        $email_html = isset($data['email_html']) ? esc_attr($data['email_html'][0]) : '';
        $tamplate_name = isset($data['tamplate_name']) ? esc_attr($data['tamplate_name'][0]) : '';
        $active_option = isset($data['active_option']) ? esc_attr($data['active_option'][0]) : false;


?>
        <style>
            .email-templates-meta-box {
                background-color: #051750;
                padding: 0.5rem;
                color: #dcdcde;
                font-weight: 500;
            }

            .email-html,
            .template-name {
                display: flex;
                flex-direction: column;
                align-items: start;
                margin: 1rem 0;
            }
            .email-subject>input,
            .email-html>textarea,
            .template-name>select {
                width: 100%;
            }

            .active-option>label {
                vertical-align: text-bottom;
            }
        </style>

        <div class="email-templates-meta-box">
            <div class="email-subject">
                <label for="vehicle3">Email Subject</label>
                <input name="email_subject" id="email_html" value='<?php esc_html_e($email_subject) ?>'></input>
            </div>
            <div class="email-html">
                <label for="vehicle3">Email Html</label>
                <textarea name="email_html" id="email_html" rows="10"><?php esc_html_e($email_html) ?></textarea>
            </div>

            <div class="template-name">
                <label for="tamplate_name">Select a template :</label>
                <select name="tamplate_name" id="tamplate_name">
                    <?php foreach ($this->templates as $key => $template) { ?>
                        <option <?php if ($tamplate_name == $key) _e("selected='selected'"); ?> value=<?php esc_attr_e($key) ?>><?php _e($template) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="active-option">
                <label for="vehicle3">Active / Inactive</label>
                <input type="checkbox" id="active_option" <?php if ($active_option) { ?> checked <?php } ?> name="active_option" value='check'>
            </div>
        </div>
<?php

    }
}
