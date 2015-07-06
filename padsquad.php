<?php
/*
Plugin Name: PadSquad Helper
Plugin URI: http://padsquad.com/
Description: PadSquad Helper
Author: John Chen
Version: 0.0.9
Author URI: http://padsquad.com/
License: GPL3

	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
    require 'plugin-updates/plugin-update-checker.php';
	$MyUpdateChecker = PucFactory::buildUpdateChecker(
			'http://asset.padsquad.com/plugin/update.json',
			__FILE__,
			'padsquad'
	);
	add_action('plugins_loaded', 'ps_initialize');
	function ps_initialize() {
		include 'ps_settings.php';
		// Get the url to check for preview "?pspreview"
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if (strpos($actual_link, "pspreview") > -1 || strpos($actual_link, "psdev") > -1 || strpos($actual_link, "psnone") > -1) {
			$isPreview = true;
		} else {
			$isPreview = false;
		}
		// Start plugin
		if ($isPreview) {
			include 'ps_filters.php';
		} else if (get_option('psplugin-checkbox')) {
			if (!is_admin() && !is_network_admin()) {
				$user_agent = $_SERVER ['HTTP_USER_AGENT'];
				$cookie = 'mobify-path';
				if (isset ( $user_agent )) {
					// 0 for desktop, 1 for mobile
					$isMobile = preg_match ( "/ip(hone|od|ad)|android|blackberry.*applewebkit|bb1\d.*mobile/i", $user_agent );
					$isBlackList = preg_match ( "/Android 2\.|Silk|iPhone OS 4_|iPad; CPU OS 4_|iPhone OS 5_|iPad; CPU OS 5_/i", $user_agent );
					$optedOut = false;
					if (isset($_COOKIE[$cookie]) && !is_null($_COOKIE[$cookie]) && strlen($_COOKIE[$cookie]) == 0) {
						$optedOut = true;
					}
					// Check for mobile or not
					if (($isMobile && !$isBlackList && !$optedOut)) {
						include 'ps_filters.php';
					}
				}
			}
		}
	}
?>