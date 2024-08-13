<?php
/*
Plugin Name: Custom Checkout Plugin
Description: Customizes the WooCommerce checkout page.
Version: 1.0
Author: Your Name
*/


add_action('woocommerce_checkout_process', 'custom_checkout_process');
add_action('woocommerce_after_order_notes', 'custom_checkout_field');

// Function to add custom field
function custom_checkout_field($checkout) {
    echo '<div id="custom_checkout_field"><h2>' . __('Custom Field') . '</h2>';
    woocommerce_form_field('custom_field', array(
        'type'          => 'text',
        'class'         => array('custom-field-class form-row-wide'),
        'label'         => __('Custom Field'),
        'placeholder'   => __('Enter something'),
    ), $checkout->get_value('custom_field'));
    echo '</div>';
}

// Function to validate custom field
function custom_checkout_process() {
    if (empty($_POST['custom_field'])) {
        wc_add_notice(__('Please enter a value for the custom field.'), 'error');
    }
}

// Save custom field value
add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');
function custom_checkout_field_update_order_meta($order_id) {
    if (!empty($_POST['custom_field'])) {
        update_post_meta($order_id, '_custom_field', sanitize_text_field($_POST['custom_field']));
    }
}

// Display custom field value in order edit page
add_action('woocommerce_admin_order_data_after_billing_address', 'custom_checkout_field_display_admin_order_meta', 10, 1);
function custom_checkout_field_display_admin_order_meta($order) {
    $custom_field = get_post_meta($order->get_id(), '_custom_field', true);
    if ($custom_field) {
        echo '<p><strong>' . __('Custom Field') . ':</strong> ' . $custom_field . '</p>';
    }
}
