<?php
// remove_all_filters('wp_nav_menu_args');
// $menus = get_registered_nav_menus();

// foreach ( $menus as $location => $description ) {
// 	echo $location . ': ' . $description . '<br />';
// }

echo '==All Menus==</br>';
$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
$counter = 0;
foreach ( $menus as $menu ):
?>
	<ul class="ps_menu_class_<?php echo $counter ?>">
		<?php wp_nav_menu(array(
				'menu' => $menu,
				'container_class' => 'ps_'. $menu->name .'_container',
				'container_id'    => 'ps_'. $menu->name .'_container',
				'menu_class'      => 'ps_nav_menu_class',
				'menu_id'         => 'ps_nav_menu_id',
				'items_wrap'      => '<ul id="ps_'. $menu->name .'_ul %1$s" class="ps_'. $menu->name .'_ul %2$s">%3$s</ul>',
		));?>
	</ul>
<?php 
$counter++;
endforeach;
// echo '</br>==Categories==</br>';
// ps_wp_list_categories();

// echo '</br>==Primary==</br>';
// Primary navigation menu.
// $args = array (
// 'menu_class' => 'ps_primary_menu',
// 'theme_location' => 'primary',
// 'container_class' => 'ps_primary_container',
// 'container_id'    => 'ps_primary_container',
// 'items_wrap'      => '<ul id="ps_primary_ul %1$s" class="ps_primary_ul %2$s">%3$s</ul>',
// );
// wp_nav_menu($args);

// echo '</br>==Default==</br>';
// $args = array(
// 		'theme_location'  => '',
// 		'menu'            => '',
// 		'container'       => 'div',
// 		'container_class' => 'ps_default_container',
// 		'container_id'    => 'ps_default_container',
// 		'menu_class'      => 'ps_nav_menu_class',
// 		'menu_id'         => 'ps_nav_menu_id',
// 		'echo'            => true,
// 		'fallback_cb'     => 'wp_page_menu',
// 		'before'          => '',
// 		'after'           => '',
// 		'link_before'     => '',
// 		'link_after'      => '',
// 		'items_wrap'      => '<ul id="ps_default_ul %1$s" class="ps_default_ul %2$s">%3$s</ul>',
// 		'depth'           => 0,
// 		'walker'          => ''
// );
// wp_nav_menu($args);
// echo '</br>=====</br>';
?>