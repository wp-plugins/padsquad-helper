<?php
include (dirname ( dirname ( __FILE__ ) ) . '/functions.php');
global $MOBIFY_URL;
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
		echo '<title>PS Article Dev</title>';
		include 'ps_mobify_tag_dev.php';
	} else if ($isNone) {
		echo '<title>PS Article None</title>';
	} else {
		include 'ps_mobify_tag.php';
	}
	?>
	<div class="ps_header_image"><?php get_header_image(); ?></div>
	<?php wp_head(); ?>
</head>

<body class="ps_body_article ps_id_<?php echo the_ID();?>">
	<main class="ps_article_page">
		<?php 
		include 'ps_nav_menu.php';
		ps_get_header();
		?>
		<div class="ps_user_theme" style="display:none">
			<?php
				global $user_theme;
	    		if (!empty($user_theme)) {
	    			load_template($user_theme);
	    		}
			?>
		</div> <!-- End ps_user_theme -->
		<?php 
		if ( have_posts() ) : 
		?>
		<div class="ps_article">
			<?php
				// Load posts
				while ( have_posts () ) :
					the_post ();
					load_template ( dirname ( dirname ( __FILE__ ) ) . '/article_content.php', false, false );
				endwhile;
			endif;
			// Load Comments
			if (comments_open () || get_comments_number ()) :
				ps_comments_template ( dirname ( dirname ( __FILE__ ) ) . '/comments.php' );
		   	endif;
			// Show next articles at bottom of page\
		   	echo '</br>==Next Articles==</br>';
			include 'ps_next_articles.php';
			// Show related
			echo '</br>==Related Articles==</br>';
			include 'ps_related_articles.php';
			// Show related newest
			echo '</br>==Related Articles Newest==</br>';
			include 'ps_related_articles_newest.php';
			?>
		</div>
	</main>
	<?php 
	include 'ps_sidebar.php';
	wp_footer(); 
	?>
</body>
</html>