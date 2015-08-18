<?php
/**
 * Plugin Name: The Booking Form
 * Plugin URI:	http://booking.drivenot.com
 * Description: Integration for WordPress of The Booking Form - transportation companies booking and dispatch system. Please use shortcode [transport-booking-form] in your Page content.
 * Author:	KANEV.COM
 * Author URI:	http://kanev.com
 * Version:		1.0.1
 * Text Domain: tbsaasform
 * Domain Path: /languages
 *
 * The Booking Form is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('admin_menu', 'tbsaasform_setup_menu');
 
function tbsaasform_setup_menu(){
        add_menu_page( 'The Booking Form Page', 'The Booking Form', 'manage_options', 'tbsaasform', 'tbsaasform_init' );
}
 
function tbsaasform_init(){
	load_plugin_textdomain('tbsaasform', false, basename( dirname( __FILE__ ) ) . '/languages' );
	
        //must check that the user has the required capability 
	if (!current_user_can('manage_options'))
	{
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	// variables for the field and option names 
	$opt_name = 'tbsaasform_iframe_code';
	$hidden_field_name = 'tbsaasform_submit_hidden';
	$data_field_name = 'tbsaasform_iframe_code';
    
	// Read in existing option value from database
	$opt_val = get_option( $opt_name );
    
	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		
		if ( get_magic_quotes_gpc() ) {
			$_POST      = array_map( 'stripslashes_deep', $_POST );
			$_GET       = array_map( 'stripslashes_deep', $_GET );
			$_COOKIE    = array_map( 'stripslashes_deep', $_COOKIE );
			$_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );
		}
		
		$opt_val = stripslashes_deep($_POST[ $data_field_name ]);
	
		// Save the posted value in the database
		update_option( $opt_name, $opt_val );
	
		// Put a "settings saved" message on the screen
    
	?>
	<div class="updated"><p><strong><?php _e('Settings saved.', 'tbsaasform' ); ?></strong></p></div>
	<?php
    
	}
	// Now display the settings editing screen
	echo '<div class="wrap">';
    
	// header
	echo "<h2>" . __( 'The Booking Form Settings', 'tbsaasform' ) . "</h2>";
	
	echo "<h4>" . __( '1. Copy and Paste the Code for The Booking Form from booking.drivenot.com > My Company.', 'tbsaasform' ) . "</h4>";
	
	echo "<h4>" . __( '2. Use shortcode [transport-booking-form] in your Page/Post content to display The Booking Form.', 'tbsaasform' ) . "</h4>";
    
	// settings form
	?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p>
<textarea name="<?php echo $data_field_name; ?>" rows="6" cols="40"><?php echo $opt_val; ?></textarea>
</p><hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>

<?php
 
}

function show_tbsaas_booking_form() {
	$tbsaasform_iframe_code = get_option( 'tbsaasform_iframe_code' );
	
	return $tbsaasform_iframe_code;
}
 
function tbsaasform_register_shortcode() {
    add_shortcode( 'transport-booking-form', 'show_tbsaas_booking_form' );
}
 
add_action( 'init', 'tbsaasform_register_shortcode' );