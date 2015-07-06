<?php
add_action("admin_init", "psplugin_settings_page");
add_action("admin_menu", "menu_item");
// Initial setting fields
function psplugin_settings_page() {
	add_settings_section("section", "Check box to activate PadSquad Helper", null, "psplugin");
	add_settings_field("psplugin-checkbox", "Check to activate", "psplugin_checkbox_display", "psplugin", "section");
	add_settings_field("psplugin-textfield", "Enter your Mobify URL", "psplugin_textfield_display", "psplugin", "section");
	add_settings_field("psplugin-radio", "Choose how to load content", "psplugin_radio_display", "psplugin", "section");
	register_setting("section", "psplugin-checkbox");
	register_setting("section", "psplugin-textfield");
	register_setting("section", "psplugin-radio");
}

// Show saved information in checkbox
function psplugin_checkbox_display() {
	echo '<input type="checkbox" name="psplugin-checkbox" value="1"';
	checked(1, get_option('psplugin-checkbox'), true);
	echo '/>';
}
// Show saved info in textfield
function psplugin_textfield_display(){
	$options = get_option( 'psplugin-textfield' );
	echo '<input type="text" id="input_text" name="psplugin-textfield" value="' . $options . '"/>';
}
// radio for displaying post or excerpt in panels page
function psplugin_radio_display(){
	$options = get_option('psplugin-radio');
	echo '
	<input type="radio" name="psplugin-radio" value="excerpt" '. checked('excerpt', $options, false) .'>Load in excerpt (Faster/Every post needs thumbnail)</br>
	<input type="radio" name="psplugin-radio" value="whole" '. checked('whole', $options, false) .'>Load in post (Slower/Posts do not need thumbnail)
	';
}

// Display settings page
function psplugin_page() {
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'simpli';
	if( isset( $_GET[ 'tab' ] ) ) {
		$active_tab = $_GET[ 'tab' ];
	} // end if
	
	?>
	<div class="wrap">
	<h2 class="nav-tab-wrapper">
    <a href="?page=psplugin&tab=simpli" class="nav-tab <?php echo $active_tab == 'simpli' ? 'nav-tab-active' : ''; ?>">Simpli</a>
    <a href="?page=psplugin&tab=options" class="nav-tab <?php echo $active_tab == 'options' ? 'nav-tab-active' : ''; ?>">Options</a>
	</h2>
	<?php 
	if ($active_tab == "simpli") {
		?>
		<a href="http://simpli.padsquad.com/">Go to Simpli to configure your theme</a>
		<?php
	} else {
		?>
		<h1>PadSquad Helper</h1>
		<form method="post" action= "options.php">
		
		<?php
		settings_fields("section");
		do_settings_sections("psplugin");
		submit_button();
	}
	?>
	</form>
	<?php
}
// Add option to settings area
function menu_item() {
	$logo = plugin_dir_url( __FILE__ ) . "assets/logo.png";
	add_menu_page("PadSquad", "PadSquad", "manage_options", "psplugin", "psplugin_page", $logo);
}
?>