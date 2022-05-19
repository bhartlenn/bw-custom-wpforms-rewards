<?php
/**
* Plugin Name: BW Custom WPForms Rewards
* Description: The Custom WPForms Rewards plugin is used to provide registered users with custom rewards(coupons or woorewards points) on completion of a survey form
* Requires at least: 5.2
* Requires PHP: 7.0
* Author: Ben HartLenn
* Author URI: https://bountifulweb.com
* License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
* Text Domain: bwcwr
*/


//Only run function when form with id == 14 is completed successfully
add_action( 'wpforms_process_complete_14', 'bw_custom_wpforms_rewards', 10, 4 );
function bw_custom_wpforms_rewards( $fields, $entry, $form_data, $entry_id ) {
	
	// Could process only form with id = 14 and use the broader wpforms_process_complete hook(instead of wpforms_process_complete_14, and multiple add_action calls and functions ) to keep processing of multiple forms more organized inside one function. See below code that is commented out:
	/*
	// if form with id == 14 is completed... 
	if ( absint( $form_data['id'] ) === 14 ) {
        // ...Do stuff after form with id == 14 is completed successfully
    }
	// ...process more forms here just like above in a conditional statement
	*/

	// Get users email from survey form sanitized fields
	$bwcwr_customer_email = $fields[2]['value'];
	
	// Get existing coupon object 
	$bwcwr_coupon = new WC_Coupon(26); // existing SURVEY-REWARD coupon has id of 26(seen in url when editing coupon on wp dashboard)
	
	// Get existing email restrictions for this coupon, "get_email_restrictions()" always returns an array even when empty
	$bwcwr_email_restrictions = $bwcwr_coupon->get_email_restrictions();
	
	// Add users email from survey form to existing email_restrictions for the coupon. 
	$bwcwr_email_restrictions[] = $bwcwr_customer_email;

	// set the coupons email restrictions
	$bwcwr_coupon->set_email_restrictions( $bwcwr_email_restrictions );
	
	// save coupon
	$bwcwr_coupon->save();
	
}
 
// Next upgrade will be a settings page allowing you to enter the form id you want to trigger the reward for, and the coupon id you want to add users to. Will also likely need to automate how this plugin gets the email field from forms, because field id will change(so find type=email inputs)