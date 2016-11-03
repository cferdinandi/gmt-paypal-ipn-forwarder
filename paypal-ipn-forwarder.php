<?php

/**
 * Plugin Name: GMT PayPal IPN Forwarder
 * Plugin URI: https://github.com/cferdinandi/gmt-paypal-ipn-forwarder/
 * GitHub Plugin URI: https://github.com/cferdinandi/gmt-paypal-ipn-forwarder/
 * Description: Forward PayPal IPN to multiple other IPN services in WordPress. Extends <a href="https://wordpress.org/plugins/paypal-ipn/">PayPal IPN for WordPress</a>. Add forwarding URLs under <a href="options-general.php?page=gmt_paypal_ipn_forwarder_options">Settings &rarr; PayPal IPN Forwarder</a>
 * Version: 1.0.3
 * Author URI: http://gomakethings.com
 * License: MIT
 *
 * Kudos to Shawn Gaffney for the inspiration.
 * @link https://gist.github.com/anointed/3805698
 */

require_once( plugin_dir_path( __FILE__ ) . 'options.php' );

function gmt_paypal_ipn_forwarder( $posted ) {

    $options = gmt_paypal_ipn_forwarder_get_theme_options();

	// Broadcast
	foreach ( $options['urls'] as $url ) {
		wp_remote_post( trim( esc_url_raw( $url ) ), array(
				'timeout' => 150,
				'httpversion' => '1.1',
                'blocking' => false,
				'body' => $posted,
		    )
		);
	}

}
add_action( 'paypal_ipn_for_wordpress_ipn_response_handler', 'gmt_paypal_ipn_forwarder', 10, 1 );


// Check that PayPal IPN is installed
function gmt_paypal_ipn_forwarder_required_plugins_admin_notice() {

    // PayPal Framework
    if ( !class_exists( 'AngellEYE_Paypal_Ipn_For_Wordpress' ) ) :
    ?>
    <div class="notice notice-error"><p><strong>Warning!</strong> PayPal IPN Forwarder requires the <a href="https://wordpress.org/plugins/paypal-ipn/">PayPal IPN for WordPress</a> plugin. Please install it immediately.</p></div>
    <?php
    endif;

}
add_action( 'admin_notices', 'gmt_paypal_ipn_forwarder_required_plugins_admin_notice' );