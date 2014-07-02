<?php
/*
Plugin Name: Block IPs in Content
Description: Block the access to certain pages
Author: ancawonka
Version: 1.0
Author URI: http://profiles.wordpress.org/ancawonka/
*/

/* ----------------------------  Constants ----------------------------------------- */ 

define ( 'TL_SETTING_BLOCK_IP' ,  'tl_block_ips' );
define ( 'TL_SETTING_BLOCK_MESSAGE' ,  'tl_block_message' );

/* ----------------------------  Admin Menu + Options ------------------------------ */ 

add_action('admin_menu', 'tl_menu_page_block');

function tl_menu_page_block()
{
	add_options_page('Block IPs', 'Block IPs', 'manage_options', 'tl_block_ips', 'tl_setting_ip_address');
}

function tl_setting_ip_address()
{
	$option_values = get_option( TL_SETTING_BLOCK_IP );

	$action = isset($_POST['action']) ? $_POST['action'] : '';
	
	switch($action){
		case 'update':
			$ips = isset($_POST[TL_SETTING_BLOCK_IP]) ? $_POST[TL_SETTING_BLOCK_IP] : 0;
			$message = isset($_POST[TL_SETTING_BLOCK_MESSAGE]) ? $_POST[TL_SETTING_BLOCK_MESSAGE] : '';
			$nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';
			if (wp_verify_nonce( $nonce, TL_SETTING_BLOCK_IP)){
				update_option(TL_SETTING_BLOCK_IP, $ips);
				update_option(TL_SETTING_BLOCK_MESSAGE, $message);
			} else {
				echo 'invalid nonce: ' . $nonce;
			}
			break;
		case 'test':
			$ip_to_test = isset($_POST['test_ip']) ? $_POST['test_ip'] : '';
			$block = tl_should_block($ip_to_test);
			if ($block){
				echo '<p class="message">' . $ip_to_test . ': <strong>blocked</strong>.</p>';
			} else {
				echo '<p class="message">' . $ip_to_test . ': allowed.</p>';
			}
			break;
		default:
			break;
	}

	include_once('options_form.php');
	
}

function tl_should_block ($your_ip)
{
	if(empty($your_ip))
		return FALSE;

	$your_long_ip = ip2long($your_ip);

	$tl_blocklist = get_option(TL_SETTING_BLOCK_IP);

	$ar = explode(",",$tl_blocklist);

	foreach($ar as $tl_ip_address)
	{
		$add = explode('-', $tl_ip_address);
		$add[0]=ip2long(trim($add[0]));
		if (isset($add[1])){
			$add[1]=ip2long(trim($add[1]));
		} else {
			$add[1]=$add[0];
		}

    if ( ($your_long_ip >= ($add[0])) && ($your_long_ip <= ($add[1])) ){
			return TRUE;
    }
	}	
    return FALSE;
}

/* ----------------------------  Filter the content ------------------------------ */ 


add_filter( 'the_content','tl_checkip', 10000);

function tl_checkip($content) {
	global $post;
	
	$should_check_block = get_post_meta($post->ID, 'block_page_by_ips', true);
	if ($should_check_block){
		$ip = $_SERVER['REMOTE_ADDR'];
		if (tl_should_block($ip)){
			return get_option(TL_SETTING_BLOCK_MESSAGE);
		} else {
			return $content;
		}
	} else {
		return $content;
	}
	
}


?>