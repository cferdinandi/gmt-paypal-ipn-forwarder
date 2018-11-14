<?php

/**
 * Theme Options v1.1.0
 * Adjust theme settings from the admin dashboard.
 * Find and replace `YourTheme` with your own namepspacing.
 *
 * Created by Michael Fields.
 * https://gist.github.com/mfields/4678999
 *
 * Forked by Chris Ferdinandi
 * http://gomakethings.com
 *
 * Free to use under the MIT License.
 * http://gomakethings.com/mit/
 */


	/**
	 * Theme Options Fields
	 * Each option field requires its own uniquely named function. Select options and radio buttons also require an additional uniquely named function with an array of option choices.
	 */

	function gmt_paypal_ipn_forwarder_settings_field_urls() {
		$options = gmt_paypal_ipn_forwarder_get_theme_options();
		?>
		<textarea class="large-text" type="text" name="gmt_paypal_ipn_forwarder_theme_options[urls]" id="urls" cols="50" rows="10" /><?php echo esc_textarea( implode( ",", $options['urls'] ) ); ?></textarea>
		<label class="description" for="urls"><?php _e( 'URLs to forward to. Separate each one with a comma.', 'paypal' ); ?></label>
		<?php
	}



	/**
	 * Theme Option Defaults & Sanitization
	 * Each option field requires a default value under gmt_paypal_ipn_forwarder_get_theme_options(), and an if statement under gmt_paypal_ipn_forwarder_theme_options_validate();
	 */

	// Get the current options from the database.
	// If none are specified, use these defaults.
	function gmt_paypal_ipn_forwarder_get_theme_options() {
		$saved = (array) get_option( 'gmt_paypal_ipn_forwarder_theme_options' );
		$defaults = array(
			'urls' => array(),
		);

		$defaults = apply_filters( 'gmt_paypal_ipn_forwarder_default_theme_options', $defaults );

		$options = wp_parse_args( $saved, $defaults );
		$options = array_intersect_key( $options, $defaults );

		return $options;
	}

	// Sanitize and validate updated theme options
	function gmt_paypal_ipn_forwarder_theme_options_validate( $input ) {
		$output = array();

		if ( isset( $input['urls'] ) && ! empty( $input['urls'] ) )
			$output['urls'] = explode( ",", wp_filter_nohtml_kses( $input['urls'] ) );

		return apply_filters( 'gmt_paypal_ipn_forwarder_theme_options_validate', $output, $input );
	}



	/**
	 * Theme Options Menu
	 * Each option field requires its own add_settings_field function.
	 */

	// Create theme options menu
	// The content that's rendered on the menu page.
	function gmt_paypal_ipn_forwarder_theme_options_render_page() {
		?>
		<div class="wrap">
			<h2><?php _e( 'PayPal IPN Forwarder Options', 'paypal' ); ?></h2>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'gmt_paypal_ipn_forwarder_options' );
					do_settings_sections( 'gmt_paypal_ipn_forwarder_options' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	// Register the theme options page and its fields
	function gmt_paypal_ipn_forwarder_theme_options_init() {

		// Register a setting and its sanitization callback
		// register_setting( $option_group, $option_name, $sanitize_callback );
		// $option_group - A settings group name.
		// $option_name - The name of an option to sanitize and save.
		// $sanitize_callback - A callback function that sanitizes the option's value.
		register_setting( 'gmt_paypal_ipn_forwarder_options', 'gmt_paypal_ipn_forwarder_theme_options', 'gmt_paypal_ipn_forwarder_theme_options_validate' );


		// Register our settings field group
		// add_settings_section( $id, $title, $callback, $page );
		// $id - Unique identifier for the settings section
		// $title - Section title
		// $callback - // Section callback (we don't want anything)
		// $page - // Menu slug, used to uniquely identify the page. See gmt_paypal_ipn_forwarder_theme_options_add_page().
		add_settings_section( 'general', '',  '__return_false', 'gmt_paypal_ipn_forwarder_options' );


		// Register our individual settings fields
		// add_settings_field( $id, $title, $callback, $page, $section );
		// $id - Unique identifier for the field.
		// $title - Setting field title.
		// $callback - Function that creates the field (from the Theme Option Fields section).
		// $page - The menu page on which to display this field.
		// $section - The section of the settings page in which to show the field.
		add_settings_field( 'urls', __( 'URLs', 'paypal' ), 'gmt_paypal_ipn_forwarder_settings_field_urls', 'gmt_paypal_ipn_forwarder_options', 'general' );
	}
	add_action( 'admin_init', 'gmt_paypal_ipn_forwarder_theme_options_init' );

	// Add the theme options page to the admin menu
	// Use add_theme_page() to add under Appearance tab (default).
	// Use add_menu_page() to add as it's own tab.
	// Use add_submenu_page() to add to another tab.
	function gmt_paypal_ipn_forwarder_theme_options_add_page() {

		// add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $function );
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function );
		// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		// $page_title - Name of page
		// $menu_title - Label in menu
		// $capability - Capability required
		// $menu_slug - Used to uniquely identify the page
		// $function - Function that renders the options page
		// $theme_page = add_theme_page( __( 'PayPal IPN Forwarder', 'paypal' ), __( 'PayPal IPN Forwarder', 'paypal' ), 'edit_theme_options', 'gmt_paypal_ipn_forwarder_options', 'gmt_paypal_ipn_forwarder_theme_options_render_page' );

		// $theme_page = add_menu_page( __( 'Theme Options', 'paypal' ), __( 'Theme Options', 'paypal' ), 'edit_theme_options', 'gmt_paypal_ipn_forwarder_options', 'gmt_paypal_ipn_forwarder_theme_options_render_page' );
		$theme_page = add_submenu_page( 'options-general.php', __( 'PayPal IPN Forwarder', 'paypal' ), __( 'PayPal IPN Forwarder', 'paypal' ), 'edit_theme_options', 'gmt_paypal_ipn_forwarder_options', 'gmt_paypal_ipn_forwarder_theme_options_render_page' );
	}
	add_action( 'admin_menu', 'gmt_paypal_ipn_forwarder_theme_options_add_page' );



	// Restrict access to the theme options page to admins
	function gmt_paypal_ipn_forwarder_option_page_capability( $capability ) {
		return 'edit_theme_options';
	}
	add_filter( 'option_page_capability_gmt_paypal_ipn_forwarder_options', 'gmt_paypal_ipn_forwarder_option_page_capability' );
