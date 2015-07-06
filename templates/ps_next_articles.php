<div class="ps_next_articles">
	<?php
	function get_post_siblings( $limit = 3, $date = '' ) {
		global $wpdb, $post;

		if( empty( $date ) )
			$date = $post->post_date;

		//$date = '2009-06-20 12:00:00'; // test data

		$limit = absint( $limit );
		if( !$limit )
			return;

		$p = $wpdb->get_results( "
				(
				SELECT
				p1.post_title,
				p1.post_date,
				p1.ID
				FROM
				$wpdb->posts p1
				WHERE
				p1.post_date < '$date' AND
				p1.post_type = 'post' AND
				p1.post_status = 'publish'
				ORDER by
				p1.post_date DESC
				LIMIT
				$limit
		)
				UNION
				(
				SELECT
				p2.post_title,
				p2.post_date,
				p2.ID
				FROM
				$wpdb->posts p2
				WHERE
				p2.post_date > '$date' AND
				p2.post_type = 'post' AND
				p2.post_status = 'publish'
				ORDER by
				p2.post_date ASC
				LIMIT
				$limit
		)
				ORDER by post_date ASC
				" );
		$i = 0;
		$adjacents = array();
		for( $c = count($p); $i < $c; $i++ )
			if( $i < $limit )
				$adjacents['prev'][] = $p[$i];
				else
						$adjacents['next'][] = $p[$i];

						return $adjacents;
	}

	global $post;

	$siblings = get_post_siblings( 5 ); // This is the same as doing the call below(which is just for illustration)
	//$siblings = get_post_siblings( 3, $post->post_date );

	$prev = $siblings['prev'];

	foreach( $prev as $p ) {
		$id = $p->ID;
		$content_post = get_post($id);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		
		echo '<div class="ps_next_post">';
		echo '<a href="';
		echo get_permalink($id);
		echo '" ';
		echo '>' . '<div class="ps_next_title">' . apply_filters( 'the_title', $p->post_title ) . '</div></a>';
		echo '<div class="ps_next_img" style="display: none;">' . get_the_post_thumbnail($id) . '</div>';
		echo '<div class="ps_related_whole" style="display: none;">' . $content . '</div>';
		echo '</div>';
	}
	// This is used to get the future posts
// 	$next = $siblings['next'];
// 	foreach( $next as $p )
// 		echo get_the_time( 'd m Y', $p ) . ': ' . apply_filters( 'the_title', $p->post_title ) . '<br />';
	?>
</div>