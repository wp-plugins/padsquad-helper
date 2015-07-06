<?php
include (dirname ( dirname ( __FILE__ ) ) . '/functions.php');
global $MOBIFY_URL
?>
<html>
<head class="ps_head">
	<?php
	$isDev = false;
	$isNone = false;
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if (strpos($actual_link, "psdev") > -1) {
		$isDev = true;
	} else if (strpos($actual_link, "psnone") > -1) {
		$isNone = true;
	}
	if ($isDev) {
		echo '<title>PS Panels Dev</title>';
		include 'ps_mobify_tag_dev.php';
	} else if ($isNone) {
		echo '<title>PS Panels None</title>';
	} else {
		include 'ps_mobify_tag.php';
	}
	?>
	<div class="ps_header_image"><?php get_header_image(); ?></div>
	<?php wp_head(); ?>
</head>

<body class="ps_body_panel ps_id_<?php echo the_ID();?>">
	<div class="ps_user_theme" style="display:none">
    	<?php 
    		global $user_theme;
    		if (!empty($user_theme)) {
    			load_template($user_theme);
    		}
    	?>
    </div> <!-- End ps_user_theme -->
	<main class="ps_panel_page">
		<?php include 'ps_nav_menu.php'; ?>
		<header class="page-header">
		    <?php ps_get_header();?>
		</header>
		<div class="ps_panels">
			<?php
			// Load posts
			while ( have_posts () ) :
				the_post();
				load_template ( dirname ( dirname ( __FILE__ ) ) . '/panel_content.php', false, false );
			endwhile;
			?>
		</div>
		<div class="ps_pagination">
			<?php
			// Load in the next page link which is used in the Mobify.konf pagination function
			echo '<div class="ps_next_page">' . get_next_posts_link() . '</div>';
			// Load in pagination (not really needed but here just in case)
			// include 'ps_pagination.php';
			?>
		</div>
	</main>
	<?php 
	include 'ps_sidebar.php';
	wp_footer(); 
	?>
</body>
</html>