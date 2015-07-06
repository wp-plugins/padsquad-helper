<?php
// Clear filters from other plugins and themes
remove_all_filters('template_include');
// Gives plugin highest priority
add_filter( 'template_include', 'ps_panel_custom_template', -9999 );
function ps_panel_custom_template( $template ) {
	// Clear filters from other plugins and themes
	remove_all_filters('template_include');
	// Dont change the login page
	if ( in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) )) {
		return $template;
	}
	// Current theme of the page for loading in custom content if available
	global $user_theme;
	$user_theme = get_page_template();
	
	// Check panel or article
	// is_home() || is_archive() || is_post_type_archive() || is_front_page() || 
// 	if (!is_singular('post') || (!is_front_page() && is_home())) {
// 		$template = plugin_dir_path(__FILE__) . 'templates/padsquad_panel.php';
// 		// Load custom pagination
// 		add_action('plugins_loaded','ps_paginate_links', 3);
// 	} else {
// 		$template = plugin_dir_path(__FILE__) . 'templates/padsquad_article.php';
// 	}
	
	if ( (is_page() && !is_category()) || is_singular('post') || (is_front_page() && !is_home())) {
		$template = plugin_dir_path(__FILE__) . 'templates/padsquad_article.php';
	} else {
		$template = plugin_dir_path(__FILE__) . 'templates/padsquad_panel.php';
		// Load custom pagination
		add_action('plugins_loaded','ps_paginate_links', 3);
	}
	
	return $template;
}
?>