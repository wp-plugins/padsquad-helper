<div class="ps_related_articles">
<?php
$categories = get_the_category();
$category = $categories[0]->cat_ID;
// var_dump($categories);
// echo 'category_num: ' . $category . '</br>';
$args = array(
	'posts_per_page'   => 50,
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
$offset = 0;
$current_post = get_the_ID();
// echo 'current_id: ' . $current_post . ". </br>";
foreach ($posts_array as $ppost) {
	$offset++;
	if ($current_post == $ppost->ID) {
		break;
	}
}
// echo 'offset: ' . $offset . '.';
$args = array(
		'posts_per_page'   => 5,
		'offset'           => $offset,
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
	echo '<div class="ps_related_post">';
	echo '<a href="';
	echo get_permalink($id);
	echo '" ';
	echo '>' . '<div class="ps_related_title">' . apply_filters( 'the_title', $post->post_title ) . '</div></a>';
	echo '<div class="ps_related_img" style="display: none;">' . get_the_post_thumbnail($id) . '</div>';
	echo '<div class="ps_related_whole" style="display: none;">' . $post->post_content . '</div>';
	echo '</div>';
}
?>
</div>