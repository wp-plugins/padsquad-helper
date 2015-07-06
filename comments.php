<?php
// Permissions check
if ( post_password_required() ) {
	return;
}
?>
<div id="ps_comments_area" class="ps_comments_area">
	<?php if ( have_comments() ) : ?>
		<ol class="ps_comment_list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 56,
				) );
			?>
		</ol>
	<?php endif; // have_comments() ?>
	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="ps_no_comments"><?php _e( 'Comments are closed.', 'padsquad' ); ?></p>
	<?php endif; ?>
	<?php ps_comment_form(); ?>
</div>