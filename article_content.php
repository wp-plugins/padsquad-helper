<article id="ps_post" <?php post_class(); ?>>
	<header class="ps_entry_header">
	<div class="ps_thumbnail_image">
	<?php echo get_the_post_thumbnail(); ?>
	</div>
	<?php
	if (is_single ()) :
		the_title ( '<h1 class="ps_entry_title">', '</h1>' );
 	else :
		the_title ( sprintf ( '<h2 class="ps_entry_title"><a class="ps_entry_link" href="%s" rel="bookmark">', esc_url ( get_permalink () ) ), '</a></h2>' );
	endif;
	?>
  	</header>
	<div class="ps_entry_content">
	<?php
		the_content ( sprintf ( __ ( 'Continue reading %s', 'padsquad' ), the_title ( '<span class="screen-reader-text">', '</span>', false ) ) );
	?>
	</div>
	<footer class="ps_entry_footer">
	<?php
	ps_entry_meta();
	?>
	</footer>
</article>