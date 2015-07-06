<div class="ps_related_articles_newest">
<?php
$categories = get_the_category();
$category = $categories[0]->cat_ID;
$args = array(
	'posts_per_page'   => 5,
	'offset'           => 0,
	'category'         => $category,
	'category_name'    => '',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => 'post',
	'post_mime_type'   => '',
	'post_parent'      => '',
	'post_status'      => 'publish',
	'suppress_filters' => true 
);
$posts_array = get_posts( $args );

foreach ($posts_array as $post) {
	$id = $post->ID;
	echo '<div class="ps_related_post_newest">';
	echo '<a href="';
	echo get_permalink($id);
	echo '" ';
	echo '>' . '<div class="ps_related_title_newest">' . apply_filters( 'the_title', $post->post_title ) . '</div></a>';
	echo '<div class="ps_related_img_newest" style="display: none;">' . get_the_post_thumbnail($id) . '</div>';
	
	echo '<div class="ps_related_whole" style="display: none;">' . $post->post_content . '</div>';
	echo '</div>';
}
?>
</div>