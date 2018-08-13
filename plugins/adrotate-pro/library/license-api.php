<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2015 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from it's use.
------------------------------------------------------------------------------------ */

/*-------------------------------------------------------------
 Name:      AJdG Solutions Licensing Library
 Version:	1.3.1
---------------------------------------------------------------
 Changelog:
---------------------------------------------------------------
 Aug/3/2015 - 1.3.1
 Updated for Software Add-On 1.5
-------------------------------------------------------------*/

function adrotate_license_activate() {
	if(wp_verify_nonce($_POST['adrotate_nonce_license'], 'adrotate_license')) {
		$a = array();

		$network = false;
		if(isset($_POST['adrotate_license_network'])) $network = trim($_POST['adrotate_license_network'], "\t\n ");
		if($network == 1) {
			$redirect = 'adrotate';
		} else {
			$redirect = 'adrotate-settings';
		}

		if(isset($_POST['adrotate_license_key'])) $a['key'] = trim($_POST['adrotate_license_key'], "\t\n ");
		if(isset($_POST['adrotate_license_email'])) $a['email'] = trim($_POST['adrotate_license_email'], "\t\n ");
		if(isset($_POST['adrotate_license_hide'])) {
			$hide = 1;
		} else {
			$hide = 0;
		}

		if(!empty($a['key']) AND !empty($a['email'])) {
			list($a['version'], $a['type'], $a['serial']) = explode("-", $a['key'], 3);
			if(!is_email($a['email'])) {
				adrotate_return($redirect, 603, array('tab' => 'license'));
				exit();
			}
			$a['instance'] = uniqid(rand(1000,9999));
			$a['platform'] = get_option('siteurl');
			
			// New Licenses
			if(strtolower($a['type']) == "s") $a['type'] = "Single";
			if(strtolower($a['type']) == "d") $a['type'] = "Duo";
			if(strtolower($a['type']) == "m") $a['type'] = "Multi";
			if(strtolower($a['type']) == "u") $a['type'] = "Developer";
			if(strtolower($a['type']) == "n") $a['type'] = "Network"; // meh
	
			if($network == 1 && $a['type'] != 'Network' && $a['type'] != 'Developer') {
				adrotate_return($redirect, 611, array('tab' => 'license'));
				exit;
			}

			if($a) adrotate_license_response('activation', $a, false, $network, $hide);

			adrotate_return($redirect, 604, array('tab' => 'license'));
			exit;
		} else {
			adrotate_return($redirect, 601, array('tab' => 'license'));
			exit;
		}
	} else {
		adrotate_nonce_error();
		exit;
	}
}

function adrotate_license_deactivate() {
	if(wp_verify_nonce($_POST['adrotate_nonce_license'], 'adrotate_license')) {
		$network = false;
		if(isset($_POST['adrotate_license_network'])) $network = trim($_POST['adrotate_license_network'], "\t\n ");
		if($network == 1) {
			$redirect = 'adrotate';
			$a = get_site_option('adrotate_activate');
		} else {
			$redirect = 'adrotate-settings';
			$a = get_option('adrotate_activate');
		}
		$force = (isset($_POST['adrotate_license_force'])) ? 1 : 0;

		if($a) adrotate_license_response('deactivation', $a, false, $network, 0, $force);

		adrotate_return($redirect, 600, array('tab' => 'license'));
	} else {
		adrotate_nonce_error();
		exit;
	}
}

function adrotate_license_deactivate_uninstall() {
	$a = get_option('adrotate_activate');
	if($a) adrotate_license_response('deactivation', $a, true);
}

function adrotate_license_response($request = '', $a = array(), $uninstall = false, $network = false, $hide = 0, $force = 0) {
	global $ajdg_solutions_domain;

	$args = $license = array();
	if($request == 'activation') $args = array('request' => 'activation', 'email' => $a['email'], 'license_key' => $a['key'], 'product_id' => $a['type'], 'instance' => $a['instance'], 'platform' => $a['platform']);

	if($request == 'deactivation') $args = array('request' => 'deactivation', 'email' => $a['email'], 'license_key' => $a['key'], 'product_id' => $a['type'], 'instance' => $a['instance']);

	$http_args = array('timeout' => 15, 'sslverify' => false, 'headers' => array('user-agent' => 'AdRotate Pro;'));
	if($a['version'] == '101') {
		// Old licenses from adrotateplugin.com
		$response = wp_remote_get($ajdg_solutions_domain.'api/license/?' . http_build_query($args, '', '&'), $http_args);
	} else {
		// Licenses from ajdg.solutions
		$response = wp_remote_get(add_query_arg('wc-api', 'software-api', $ajdg_solutions_domain) . '&' . http_build_query($args, '', '&'), $http_args);
	}

	if($network) {
		$redirect = 'adrotate';
	} else {
		$redirect = 'adrotate-settings';	
	}

	if($uninstall) return;

	if(!is_wp_error($response) || wp_remote_retrieve_response_code($response) === 200) {
		$data = json_decode($response['body'], 1);
		
		if(empty($data['code'])) $data['code'] = 0;
		if(empty($data['activated'])) $data['activated'] = 0;
		if(empty($data['reset'])) $data['reset'] = 0;

		if($data['code'] == 100) { // Invalid Request
			adrotate_return($redirect, 600, array('tab' => 'license'));
			exit;
		} else if($data['code'] == 101) { // Invalid License
			adrotate_return($redirect, 604, array('tab' => 'license'));
			exit;
		} else if($data['code'] == 102) { // Order is not complete
			adrotate_return($redirect, 605, array('tab' => 'license'));
			exit;
		} else if($data['code'] == 103) { // No activations remaining
			adrotate_return($redirect, 606, array('tab' => 'license')); 
			exit;
		} else if($data['code'] == 104) { // Could not (de)activate
			adrotate_return($redirect, 607, array('tab' => 'license'));
			exit;
		} else if($data['code'] == 0 && $data['activated'] == 1) {
			update_option('adrotate_hide_license', $hide);
			if($network) {
				update_site_option('adrotate_activate', array('status' => 1, 'instance' => $a['instance'], 'activated' => current_time('timestamp'), 'deactivated' => '', 'type' => $a['type'], 'key' => $a['key'], 'email' => $a['email'], 'version' => $a['version'], 'firstrun' => 0));
			} else {
				update_option('adrotate_activate', array('status' => 1, 'instance' => $a['instance'], 'activated' => current_time('timestamp'), 'deactivated' => '', 'type' => $a['type'], 'key' => $a['key'], 'email' => $a['email'], 'version' => $a['version'], 'firstrun' => 0));
			}

			unset($a, $args, $response, $data);

			if($request == 'activation') adrotate_return($redirect, 608, array('tab' => 'license'));
			exit;
		} else if(($data['code'] == 0 && $data['reset'] == 1) OR $force == 1) {
			update_option('adrotate_hide_license', 0);
			if($network) {
				update_site_option('adrotate_activate', array('status' => 0, 'instance' => '', 'activated' => $a['activated'], 'deactivated' => current_time('timestamp'), 'type' => '', 'key' => '', 'email' => '', 'version' => '', 'firstrun' => 1));
			} else {
				update_option('adrotate_activate', array('status' => 0, 'instance' => '', 'activated' => $a['activated'], 'deactivated' => current_time('timestamp'), 'type' => '', 'key' => '', 'email' => '', 'version' => '', 'firstrun' => 1));
			}

			unset($a, $args, $response, $data);

			if($request == 'deactivation') adrotate_return($redirect, 609, array('tab' => 'license'));
			exit;
		} else {
			adrotate_return($redirect, 600, array('tab' => 'license'));
			exit;
		}
	} else {
		adrotate_return($redirect, 602, array('error' => wp_remote_retrieve_response_code($response), 'tab' => 'license'));
		exit;
	}
}
?>