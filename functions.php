<?php
// *** Keep *** Get rid of less than useful default pagination
add_action ( 'init', 'remove_old_pagination' );
function remove_old_pagination() {
	remove_action ( 'plugins_loaded', 'paginate_links', 3 );
}
// *** Keep *** Functions taken from various places in wordpress-default and made available for the plugin
function ps_paginate_links($args = '') {
	global $wp_query, $wp_rewrite;

	// Setting up default values based on the current URL.
	$pagenum_link = html_entity_decode ( get_pagenum_link () );
	$url_parts = explode ( '?', $pagenum_link );

	// Get max pages and current page out of the current query, if available.
	$total = isset ( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
	$current = get_query_var ( 'paged' ) ? intval ( get_query_var ( 'paged' ) ) : 1;

	// Append the format placeholder to the base URL.
	$pagenum_link = trailingslashit ( $url_parts [0] ) . '%_%';

	// URL base depends on permalink settings.
	$format = $wp_rewrite->using_index_permalinks () && ! strpos ( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks () ? user_trailingslashit ( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

	$defaults = array (
			'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
			'format' => $format, // ?page=%#% : %#% is replaced by the page number
			'total' => $total,
			'current' => $current,
			'show_all' => false,
			'prev_next' => true,
			'prev_text' => __ ( '&laquo; Previous' ),
			'next_text' => __ ( 'Next &raquo;' ),
			'end_size' => 1,
			'mid_size' => 2,
			'type' => 'plain',
			'add_args' => array (), // array of query args to add
			'add_fragment' => '',
			'before_page_number' => '',
			'after_page_number' => ''
	);

	$args = wp_parse_args ( $args, $defaults );

	if (! is_array ( $args ['add_args'] )) {
		$args ['add_args'] = array ();
	}

	// Merge additional query vars found in the original URL into 'add_args' array.
	if (isset ( $url_parts [1] )) {
		// Find the format argument.
		$format_query = parse_url ( str_replace ( '%_%', $args ['format'], $args ['base'] ), PHP_URL_QUERY );
		wp_parse_str ( $format_query, $format_arg );

		// Remove the format argument from the array of query arguments, to avoid overwriting custom format.
		wp_parse_str ( remove_query_arg ( array_keys ( $format_arg ), $url_parts [1] ), $query_args );
		$args ['add_args'] = array_merge ( $args ['add_args'], urlencode_deep ( $query_args ) );
	}

	// Who knows what else people pass in $args
	$total = ( int ) $args ['total'];
	if ($total < 2) {
		return;
	}
	$current = ( int ) $args ['current'];
	$end_size = ( int ) $args ['end_size']; // Out of bounds? Make it the default.
	if ($end_size < 1) {
		$end_size = 1;
	}
	$mid_size = ( int ) $args ['mid_size'];
	if ($mid_size < 0) {
		$mid_size = 2;
	}
	$add_args = $args ['add_args'];
	$r = '';
	$page_links = array ();
	$dots = false;

	if ($args ['prev_next'] && $current && 1 < $current) :
		$link = str_replace ( '%_%', 2 == $current ? '' : $args ['format'], $args ['base'] );
		$link = str_replace ( '%#%', $current - 1, $link );
		if ($add_args)
			$link = add_query_arg ( $add_args, $link );
		$link .= $args ['add_fragment'];

		/**
		 * Filter the paginated links for the given archive pages.
		 *
		 * @since 3.0.0
		 *
		 * @param string $link
		 *        	The paginated link URL.
		 */
		$page_links [] = '<a class="ps_prev ps_page-numbers" href="' . esc_url ( apply_filters ( 'paginate_links', $link ) ) . '">' . $args ['prev_text'] . '</a>';


 endif;
	for($n = 1; $n <= $total; $n ++) :
		if ($n == $current) :
			$page_links [] = "<span class='ps_page-numbers current'>" . $args ['before_page_number'] . number_format_i18n ( $n ) . $args ['after_page_number'] . "</span>";
			$dots = true;
		 else :
			if ($args ['show_all'] || ($n <= $end_size || ($current && $n >= $current - $mid_size && $n <= $current + $mid_size) || $n > $total - $end_size)) :
				$link = str_replace ( '%_%', 1 == $n ? '' : $args ['format'], $args ['base'] );
				$link = str_replace ( '%#%', $n, $link );
				if ($add_args)
					$link = add_query_arg ( $add_args, $link );
				$link .= $args ['add_fragment'];

				/**
				 * This filter is documented in wp-includes/general-template.php
				 */
				$page_links [] = "<a class='ps_page-numbers' href='" . esc_url ( apply_filters ( 'paginate_links', $link ) ) . "'>" . $args ['before_page_number'] . number_format_i18n ( $n ) . $args ['after_page_number'] . "</a>";
				$dots = true;
			 elseif ($dots && ! $args ['show_all']) :
				$page_links [] = '<span class="ps_page-numbers dots">' . __ ( '&hellip;' ) . '</span>';
				$dots = false;
			endif;
		endif;
	endfor
	;
	if ($args ['prev_next'] && $current && ($current < $total || - 1 == $total)) :
		$link = str_replace ( '%_%', $args ['format'], $args ['base'] );
		$link = str_replace ( '%#%', $current + 1, $link );
		if ($add_args)
			$link = add_query_arg ( $add_args, $link );
		$link .= $args ['add_fragment'];

		/**
		 * This filter is documented in wp-includes/general-template.php
		 */
		$page_links [] = '<a class="ps_next ps_page-numbers" href="' . esc_url ( apply_filters ( 'paginate_links', $link ) ) . '">' . $args ['next_text'] . '</a>';


 endif;
	switch ($args ['type']) {
		case 'array' :
			return $page_links;

		case 'list' :
			$r .= "<ul class='ps_page-numbers'>\n\t<li>";
			$r .= join ( "</li>\n\t<li>", $page_links );
			$r .= "</li>\n</ul>\n";
			break;

		default :
			$r = join ( "\n", $page_links );
			break;
	}
	return $r;
}
// *** Keep ***
function ps_the_posts_pagination($args = array()) {
	echo ps_get_the_posts_pagination ( $args );
}
// *** Keep ***
function ps_get_the_posts_pagination($args = array()) {
	$navigation = '';

	// Don't print empty markup if there's only one page.
	if ($GLOBALS ['wp_query']->max_num_pages > 1) {
		$args = wp_parse_args ( $args, array (
				'mid_size' => 1,
				'prev_text' => _x ( 'Previous', 'previous post' ),
				'next_text' => _x ( 'Next', 'next post' ),
				'screen_reader_text' => __ ( 'Posts navigation' )
		) );

		// Make sure we get a string back. Plain is the next best thing.
		if (isset ( $args ['type'] ) && 'array' == $args ['type']) {
			$args ['type'] = 'plain';
		}

		// Set up paginated links.
		$links = ps_paginate_links ( $args );

		if ($links) {
			$navigation = _navigation_markup ( $links, 'pagination', $args ['screen_reader_text'] );
		}
	}

	return $navigation;
}
// *** Keep ***
function ps_get_header($name = null) {
	/**
	 * Fires before the header template file is loaded.
	 *
	 * The hook allows a specific header template file to be used in place of the
	 * default header template file. If your file is called header-new.php,
	 * you would specify the filename in the hook as get_header( 'new' ).
	 *
	 * @since 2.1.0
	 * @since 2.8.0 $name parameter added.
	 *
	 * @param string $name
	 *        	Name of the specific header file to use.
	 */
	do_action ( 'get_header', $name );

	$templates = array ();
	$name = ( string ) $name;

	$templates [] = 'header.php';

	load_template ( plugin_dir_path ( __FILE__ ) . '/header.php' );
}
// *** Keep ***
function ps_comments_template($file, $separate_comments = false) {
	global $wp_query, $withcomments, $post, $wpdb, $id, $comment, $user_login, $user_ID, $user_identity, $overridden_cpage;

	if (! (is_single () || is_page () || $withcomments) || empty ( $post ))
		return;

	if (empty ( $file ))
		$file = '/ps_comments.php';

	$req = get_option ( 'require_name_email' );

	/*
	 * Comment author information fetched from the comment cookies.
	 */
	$commenter = wp_get_current_commenter ();

	/*
	 * The name of the current comment author escaped for use in attributes.
	 * Escaped by sanitize_comment_cookies().
	 */
	$comment_author = $commenter ['comment_author'];

	/*
	 * The email address of the current comment author escaped for use in attributes.
	 * Escaped by sanitize_comment_cookies().
	 */
	$comment_author_email = $commenter ['comment_author_email'];

	/*
	 * The url of the current comment author escaped for use in attributes.
	 */
	$comment_author_url = esc_url ( $commenter ['comment_author_url'] );

	$comment_args = array (
			'order' => 'ASC',
			'orderby' => 'comment_date_gmt',
			'status' => 'approve',
			'post_id' => $post->ID
	);

	if ($user_ID) {
		$comment_args ['include_unapproved'] = array (
				$user_ID
		);
	} elseif (! empty ( $comment_author_email )) {
		$comment_args ['include_unapproved'] = array (
				$comment_author_email
		);
	}

	$comments = get_comments ( $comment_args );

	/**
	 * Filter the comments array.
	 *
	 * @since 2.1.0
	 *
	 * @param array $comments
	 *        	Array of comments supplied to the comments template.
	 * @param int $post_ID
	 *        	Post ID.
	 */
	$wp_query->comments = apply_filters ( 'comments_array', $comments, $post->ID );
	$comments = &$wp_query->comments;
	$wp_query->comment_count = count ( $wp_query->comments );
	update_comment_cache ( $wp_query->comments );

	if ($separate_comments) {
		$wp_query->comments_by_type = separate_comments ( $comments );
		$comments_by_type = &$wp_query->comments_by_type;
	}

	$overridden_cpage = false;
	if ('' == get_query_var ( 'cpage' ) && get_option ( 'page_comments' )) {
		set_query_var ( 'cpage', 'newest' == get_option ( 'default_comments_page' ) ? get_comment_pages_count () : 1 );
		$overridden_cpage = true;
	}

	if (! defined ( 'COMMENTS_TEMPLATE' ))
		define ( 'COMMENTS_TEMPLATE', true );

	$theme_template = $file;
	/**
	 * Filter the path to the theme template file used for the comments template.
	 *
	 * @since 1.5.1
	 *
	 * @param string $theme_template
	 *        	The path to the theme template file.
	 */
	$include = apply_filters ( 'comments_template', $theme_template );
	if (file_exists ( $include ))
		require ($include);
	elseif (file_exists ( TEMPLATEPATH . $file ))
		require (TEMPLATEPATH . $file);
	else // Backward compat code will be removed in a future release
		require (ABSPATH . WPINC . '/theme-compat/comments.php');
}
// *** Keep ***
function ps_comment_form($args = array(), $post_id = null) {
	if (null === $post_id)
		$post_id = get_the_ID ();

	$commenter = wp_get_current_commenter ();
	$user = wp_get_current_user ();
	$user_identity = $user->exists () ? $user->display_name : '';

	$args = wp_parse_args ( $args );
	if (! isset ( $args ['format'] ))
		$args ['format'] = current_theme_supports ( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';

	$req = get_option ( 'require_name_email' );
	$aria_req = ($req ? " aria-required='true'" : '');
	$html_req = ($req ? " required='required'" : '');
	$html5 = 'html5' === $args ['format'];
	$fields = array (
			'author' => '<p class="comment-form-author">' . '<label for="author">' . __ ( 'Name' ) . ($req ? ' <span class="required">*</span>' : '') . '</label> ' . '<input id="author" name="author" type="text" value="' . esc_attr ( $commenter ['comment_author'] ) . '" size="30"' . $aria_req . $html_req . ' /></p>',
			'email' => '<p class="comment-form-email"><label for="email">' . __ ( 'Email' ) . ($req ? ' <span class="required">*</span>' : '') . '</label> ' . '<input id="email" name="email" ' . ($html5 ? 'type="email"' : 'type="text"') . ' value="' . esc_attr ( $commenter ['comment_author_email'] ) . '" size="30" aria-describedby="email-notes"' . $aria_req . $html_req . ' /></p>',
			'url' => '<p class="comment-form-url"><label for="url">' . __ ( 'Website' ) . '</label> ' . '<input id="url" name="url" ' . ($html5 ? 'type="url"' : 'type="text"') . ' value="' . esc_attr ( $commenter ['comment_author_url'] ) . '" size="30" /></p>'
	);

	$required_text = sprintf ( ' ' . __ ( 'Required fields are marked %s' ), '<span class="required">*</span>' );

	/**
	 * Filter the default comment form fields.
	 *
	 * @since 3.0.0
	 *
	 * @param array $fields
	 *        	The default comment fields.
	 */
	$fields = apply_filters ( 'comment_form_default_fields', $fields );
	$defaults = array (
			'fields' => $fields,
			'comment_field' => '<p class="ps_comment_form_comment"><label for="comment">' . _x ( 'Comment', 'noun' ) . '</label> <textarea id="comment" name="comment" cols="45" rows="8" aria-describedby="form-allowed-tags" aria-required="true" required="required"></textarea></p>',
			/**
			 * This filter is documented in wp-includes/link-template.php
			 */
			'must_log_in' => '<p class="must-log-in">' . sprintf ( __ ( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url ( apply_filters ( 'the_permalink', get_permalink ( $post_id ) ) ) ) . '</p>',
			/**
			 * This filter is documented in wp-includes/link-template.php
			 */
			'logged_in_as' => '<p class="logged-in-as">' . sprintf ( __ ( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), get_edit_user_link (), $user_identity, wp_logout_url ( apply_filters ( 'the_permalink', get_permalink ( $post_id ) ) ) ) . '</p>',
			'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . __ ( 'Your email address will not be published.' ) . '</span>' . ($req ? $required_text : '') . '</p>',
			'comment_notes_after' => '<p class="form-allowed-tags" id="form-allowed-tags">' . sprintf ( __ ( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags () . '</code>' ) . '</p>',
			'id_form' => 'commentform',
			'id_submit' => 'submit',
			'class_submit' => 'submit',
			'name_submit' => 'submit',
			'title_reply' => __ ( 'Leave a Reply' ),
			'title_reply_to' => __ ( 'Leave a Reply to %s' ),
			'cancel_reply_link' => __ ( 'Cancel reply' ),
			'label_submit' => __ ( 'Post Comment' ),
			'submit_button' => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
			'submit_field' => '<p class="ps_form_submit">%1$s %2$s</p>',
			'format' => 'xhtml'
	);

	/**
	 * Filter the comment form default arguments.
	 *
	 * Use 'comment_form_default_fields' to filter the comment fields.
	 *
	 * @since 3.0.0
	 *
	 * @param array $defaults
	 *        	The default comment form arguments.
	 */
	$args = wp_parse_args ( $args, apply_filters ( 'comment_form_defaults', $defaults ) );

	if (comments_open ( $post_id )) :
		?>
      <?php
		/**
		 * Fires before the comment form.
		 *
		 * @since 3.0.0
		 */
		do_action ( 'comment_form_before' );
		?>
<div id="ps_respond" class="ps_comment_respond">
	<h3 id="reply-title" class="comment-reply-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small>
	</h3>
        <?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
          <?php echo $args['must_log_in']; ?>
          <?php
			/**
			 * Fires after the HTML-formatted 'must log in after' message in the comment form.
			 *
			 * @since 3.0.0
			 */
			do_action ( 'comment_form_must_log_in_after' );
			?>
        <?php else : ?>
          <form
		action="<?php echo site_url( '/wp-comments-post.php' ); ?>"
		method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>"
		class="ps_comment_form" <?php echo $html5 ? ' novalidate' : ''; ?>>
            <?php
			/**
			 * Fires at the top of the comment form, inside the form tag.
			 *
			 * @since 3.0.0
			 */
			do_action ( 'comment_form_top' );
			?>
            <?php if ( is_user_logged_in() ) : ?>
              <?php
				/**
				 * Filter the 'logged in' message for the comment form for display.
				 *
				 * @since 3.0.0
				 *
				 * @param string $args_logged_in
				 *        	The logged-in-as HTML-formatted message.
				 * @param array $commenter
				 *        	An array containing the comment author's
				 *        	username, email, and URL.
				 * @param string $user_identity
				 *        	If the commenter is a registered user,
				 *        	the display name, blank otherwise.
				 */
				echo apply_filters ( 'comment_form_logged_in', $args ['logged_in_as'], $commenter, $user_identity );
				?>
              <?php
				/**
				 * Fires after the is_user_logged_in() check in the comment form.
				 *
				 * @since 3.0.0
				 *
				 * @param array $commenter
				 *        	An array containing the comment author's
				 *        	username, email, and URL.
				 * @param string $user_identity
				 *        	If the commenter is a registered user,
				 *        	the display name, blank otherwise.
				 */
				do_action ( 'comment_form_logged_in_after', $commenter, $user_identity );
				?>
            <?php else : ?>
              <?php echo $args['comment_notes_before']; ?>
              <?php
				/**
				 * Fires before the comment fields in the comment form.
				 *
				 * @since 3.0.0
				 */
				do_action ( 'comment_form_before_fields' );
				foreach ( ( array ) $args ['fields'] as $name => $field ) {
					/**
					 * Filter a comment form field for display.
					 *
					 * The dynamic portion of the filter hook, `$name`, refers to the name
					 * of the comment form field. Such as 'author', 'email', or 'url'.
					 *
					 * @since 3.0.0
					 *
					 * @param string $field
					 *        	The HTML-formatted output of the comment form field.
					 */
					echo apply_filters ( "comment_form_field_{$name}", $field ) . "\n";
				}
				/**
				 * Fires after the comment fields in the comment form.
				 *
				 * @since 3.0.0
				 */
				do_action ( 'comment_form_after_fields' );
				?>
            <?php endif; ?>
            <?php
			/**
			 * Filter the content of the comment textarea field for display.
			 *
			 * @since 3.0.0
			 *
			 * @param string $args_comment_field
			 *        	The content of the comment textarea field.
			 */
			echo apply_filters ( 'comment_form_field_comment', $args ['comment_field'] );
			?>
            <?php echo $args['comment_notes_after']; ?>

            <?php
			$submit_button = sprintf ( $args ['submit_button'], esc_attr ( $args ['name_submit'] ), esc_attr ( $args ['id_submit'] ), esc_attr ( $args ['class_submit'] ), esc_attr ( $args ['label_submit'] ) );

			/**
			 * Filter the submit button for the comment form to display.
			 *
			 * @since 4.2.0
			 *
			 * @param string $submit_button
			 *        	HTML markup for the submit button.
			 * @param array $args
			 *        	Arguments passed to `comment_form()`.
			 */
			$submit_button = apply_filters ( 'comment_form_submit_button', $submit_button, $args );

			$submit_field = sprintf ( $args ['submit_field'], $submit_button, get_comment_id_fields ( $post_id ) );

			/**
			 * Filter the submit field for the comment form to display.
			 *
			 * The submit field includes the submit button, hidden fields for the
			 * comment form, and any wrapper markup.
			 *
			 * @since 4.2.0
			 *
			 * @param string $submit_field
			 *        	HTML markup for the submit field.
			 * @param array $args
			 *        	Arguments passed to comment_form().
			 */
			echo apply_filters ( 'comment_form_submit_field', $submit_field, $args );

			/**
			 * Fires at the bottom of the comment form, inside the closing </form> tag.
			 *
			 * @since 1.5.0
			 *
			 * @param int $post_id
			 *        	The post ID.
			 */
			do_action ( 'comment_form', $post_id );
			?>
          </form>
        <?php endif; ?>
      </div>
<!-- #respond -->
<?php
		/**
		 * Fires after the comment form.
		 *
		 * @since 3.0.0
		 */
		do_action ( 'comment_form_after' );
	 else :
		/**
		 * Fires after the comment form if comments are closed.
		 *
		 * @since 3.0.0
		 */
		do_action ( 'comment_form_comments_closed' );
	endif;
}
// *** Keep ***
function ps_entry_meta() {
	if (is_sticky () && is_home () && ! is_paged ()) {
		printf ( '<span class="ps_sticky_post">%s</span>', __ ( 'Featured', 'padsquad' ) );
	}

	$format = get_post_format ();
	if (current_theme_supports ( 'post-formats', $format )) {
		printf ( '<span class="ps_entry_format">%1$s<a href="%2$s">%3$s</a></span>', sprintf ( '<span class="screen-reader-text">%s </span>', _x ( 'Format', 'Used before post format.', 'padsquad' ) ), esc_url ( get_post_format_link ( $format ) ), get_post_format_string ( $format ) );
	}

	if (in_array ( get_post_type (), array (
			'post',
			'attachment'
	) )) {
		$time_string = '<time class="ps_entry_date published updated" datetime="%1$s">%2$s</time>';

		if (get_the_time ( 'U' ) !== get_the_modified_time ( 'U' )) {
			$time_string = '<time class="ps_entry_date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf ( $time_string, esc_attr ( get_the_date ( 'c' ) ), get_the_date (), esc_attr ( get_the_modified_date ( 'c' ) ), get_the_modified_date () );

		printf ( '<span class="ps_posted_on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>', _x ( 'Posted on', 'Used before publish date.', 'padsquad' ), esc_url ( get_permalink () ), $time_string );
	}

	if ('post' == get_post_type ()) {
		if (is_singular () || is_multi_author ()) {
			printf ( '<span class="ps_byline"><span class="ps_author vcard"><span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span></span>', _x ( 'Author', 'Used before post author name.', 'padsquad' ), esc_url ( get_author_posts_url ( get_the_author_meta ( 'ID' ) ) ), get_the_author () );
		}

		$categories_list = get_the_category_list ( _x ( ', ', 'Used between list items, there is a space after the comma.', 'padsquad' ) );
		if ($categories_list) {
			printf ( '<span class="ps_cat_links"><span class="screen-reader-text">%1$s </span>%2$s</span>', _x ( 'Categories', 'Used before category names.', 'padsquad' ), $categories_list );
		}

		$tags_list = get_the_tag_list ( '', _x ( ', ', 'Used between list items, there is a space after the comma.', 'padsquad' ) );
		if ($tags_list) {
			printf ( '<span class="ps_tags_links"><span class="screen-reader-text">%1$s </span>%2$s</span>', _x ( 'Tags', 'Used before tag names.', 'padsquad' ), $tags_list );
		}
	}

	if (is_attachment () && wp_attachment_is_image ()) {
		// Retrieve attachment metadata.
		$metadata = wp_get_attachment_metadata ();

		printf ( '<span class="ps_full_size_link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>', _x ( 'Full size', 'Used before full size attachment link.', 'padsquad' ), esc_url ( wp_get_attachment_url () ), $metadata ['width'], $metadata ['height'] );
	}

	if (! is_single () && ! post_password_required () && (comments_open () || get_comments_number ())) {
		echo '<span class="comments-link">';
		comments_popup_link ( __ ( 'Leave a comment', 'padsquad' ), __ ( '1 Comment', 'padsquad' ), __ ( '% Comments', 'padsquad' ) );
		echo '</span>';
	}
}
// *** Keep ***
function ps_wp_list_categories( $args = '' ) {
	$defaults = array(
			'show_option_all' => '', 'show_option_none' => __('No categories'),
			'orderby' => 'name', 'order' => 'ASC',
			'style' => 'list',
			'show_count' => 0, 'hide_empty' => 1,
			'use_desc_for_title' => 1, 'child_of' => 0,
			'feed' => '', 'feed_type' => '',
			'feed_image' => '', 'exclude' => '',
			'exclude_tree' => '', 'current_category' => 0,
			'hierarchical' => true, 'title_li' => __( 'Categories' ),
			'echo' => 1, 'depth' => 0,
			'taxonomy' => 'category'
	);

	$r = wp_parse_args( $args, $defaults );

	if ( !isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] )
		$r['pad_counts'] = true;

	if ( true == $r['hierarchical'] ) {
		$r['exclude_tree'] = $r['exclude'];
		$r['exclude'] = '';
	}

	if ( ! isset( $r['class'] ) )
		$r['class'] = ( 'category' == $r['taxonomy'] ) ? 'categories' : $r['taxonomy'];

	if ( ! taxonomy_exists( $r['taxonomy'] ) ) {
		return false;
	}

	$show_option_all = $r['show_option_all'];
	$show_option_none = $r['show_option_none'];

	$categories = get_categories( $r );

	$output = '';
	if ( $r['title_li'] && 'list' == $r['style'] ) {
		$output = '<li class="ps_categories_container ' . esc_attr( $r['class'] ) . '">' . $r['title_li'] . '<ul class="ps_categories_ul">';
	}
	if ( empty( $categories ) ) {
		if ( ! empty( $show_option_none ) ) {
			if ( 'list' == $r['style'] ) {
				$output .= '<li class="ps_cat_item_none">' . $show_option_none . '</li>';
			} else {
				$output .= $show_option_none;
			}
		}
	} else {
		if ( ! empty( $show_option_all ) ) {
			$posts_page = ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
			$posts_page = esc_url( $posts_page );
			if ( 'list' == $r['style'] ) {
				$output .= "<li class='ps_cat_item_all'><a href='$posts_page'>$show_option_all</a></li>";
			} else {
				$output .= "<a href='$posts_page'>$show_option_all</a>";
			}
		}

		if ( empty( $r['current_category'] ) && ( is_category() || is_tax() || is_tag() ) ) {
			$current_term_object = get_queried_object();
			if ( $current_term_object && $r['taxonomy'] === $current_term_object->taxonomy ) {
				$r['current_category'] = get_queried_object_id();
			}
		}

		if ( $r['hierarchical'] ) {
			$depth = $r['depth'];
		} else {
			$depth = -1; // Flat.
		}
		$output .= walk_category_tree( $categories, $depth, $r );
	}

	if ( $r['title_li'] && 'list' == $r['style'] )
		$output .= '</ul></li>';

	/**
	 * Filter the HTML output of a taxonomy list.
	 *
	 * @since 2.1.0
	 *
	 * @param string $output HTML output.
	 * @param array  $args   An array of taxonomy-listing arguments.
	 */
	$html = apply_filters( 'wp_list_categories', $output, $args );

	if ( $r['echo'] ) {
		echo $html;
	} else {
		return $html;
	}
}

// Change excerpt length
function ps_excerpt_length( $length ) {
	return 1;
}
add_filter( 'excerpt_length', 'ps_excerpt_length', 999 );

?>