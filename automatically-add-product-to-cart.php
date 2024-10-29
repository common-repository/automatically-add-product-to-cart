<?php
/*
Plugin Name: Automatically add product to cart
Plugin URI: https://www.c-metric.com/
Description: Customer can select multiple products from WordPress dashboard and selected products will add in cart automatically
Version: 1.5
Author: C-Metric
Author URI: https://www.c-metric.com/
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! defined( 'WC_CMETRIC_APTC_DB_VERSION' ) ) {
      define( 'WC_CMETRIC_APTC_DB_VERSION', '1.0' );
}

// include all required files here
require_once('class-woocommerce-automatically-add-product-to-cart.php');

/**
 * Get it Started
*/
$GLOBALS['WC_Cmetric_Aptc'] = new WC_Cmetric_Aptc();	
?>