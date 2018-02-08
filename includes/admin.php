<?php

add_action('admin_menu', function(){
	add_menu_page( 'Settings', 'Events', 'manage_options', 'max_events_plugin_settings', '', '', 10 );
} );

add_action('admin_menu', 'register_my_custom_submenu_page');

function register_my_custom_submenu_page() {
	add_submenu_page( 'max_events_plugin_settings', 'My events', 'Settings', 'manage_options', 'my-custom-submenu-page', 'add_my_setting' ); 
}

add_action('init', 'create_post_type_markers');

function create_post_type_markers() { // создаем новый тип записи
	register_post_type( 'calendar', // указываем названия типа
	array(
	'labels' => array( 
	'name' => __( 'Events' ), // даем названия разделу для панели управления
	'singular_name' => __( 'events' ), // даем названия одной записи
	'add_new' => __('Add events'),// далее полная русификация админ. панели
	'add_new_item' => __('Add new events'),
	'edit_item' => __('Edit events'),
	'new_item' => __('New events'),
	'all_items' => __('My events'),
	'view_item' => __('View events'),
	'search_items' => __('Search events'),
	'not_found' => __('No events'),
	'not_found_in_trash' => __('Markers not found'), 
	'menu_name' => 'Events',
	), 
	'public' => true,
	// 'menu_position' => 5,
	'show_in_menu'  => 'max_events_plugin_settings',
	// 'show_ui'  => false,
	'rewrite' => array('slug' => 'markers'), // указываем slug для ссылок например: mysite/reviews/
	'supports' => array('title', 'thumbnail', 'editor')
	) 
	); 
}

/* Добавляем блоки в основную колонку на страницах постов и пост. страниц */
function myplugin_add_custom_box() {
	$screens = array( 'calendar');
	foreach ( $screens as $screen )
		add_meta_box( 'myplugin_sectionid', 'Date and link event', 'myplugin_meta_box_callback', $screen );
}
add_action('add_meta_boxes', 'myplugin_add_custom_box');

/* HTML код блока */
function myplugin_meta_box_callback() {
	$post_id = get_the_ID();
	$date = get_post_meta( $post_id, 'date', true );
	$link = get_post_meta( $post_id, 'link-event', true );

	// Используем nonce для верификации
	wp_nonce_field( plugin_basename(__FILE__), 'max_event_plugin' );


	echo '<input type="text" id="datepicker" placeholder="Date" value="'.$date.'" name="date">';

	echo '<input type="text" id="link-event" placeholder="Link event" value="'.$link.'" name="link-event">';

	echo '<script>
		    jQuery(document).ready(function($) {
		        $("#datepicker").datepicker();
		    });
		</script>';
}

/* Сохраняем данные, когда пост сохраняется */
function myplugin_save_postdata( $post_id ) {
	// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
	
	if ( ! wp_verify_nonce( $_POST['max_event_plugin'], plugin_basename(__FILE__) ) )
		return $post_id;

	// проверяем, если это автосохранение ничего не делаем с данными нашей формы.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;

	// проверяем разрешено ли пользователю указывать эти данные
	if ( 'calendar' == $_POST['post_type'] && ! current_user_can( 'edit_page', $post_id ) ) {
		  return $post_id;
	} elseif( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	// Убедимся что поле установлено.
	if ( ! isset( $_POST['date'] ) )
		return;

	// Все ОК. Теперь, нужно найти и сохранить данные
	// Очищаем значение поля input.
	$date = $_POST['date'] ;
	$link = $_POST['link-event'] ;
	// Обновляем данные в базе данных.
	update_post_meta( $post_id, 'date', $date );
	update_post_meta( $post_id, 'link-event', $link );
}
add_action( 'save_post', 'myplugin_save_postdata' );

function add_my_setting(){
 	echo '<h3>Shortcode: [display_calendar]</h3>';
}

/**
 * Load jQuery datepicker.
 *
 * By using the correct hook you don't need to check `is_admin()` first.
 * If jQuery hasn't already been loaded it will be when we request the
 * datepicker script.
 */
function wpse_enqueue_datepicker() {
    // Load the datepicker script (pre-registered in WordPress).
    wp_enqueue_script( 'jquery-ui-datepicker' );

    // You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
    wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style( 'jquery-ui' );  
}
add_action( 'admin_enqueue_scripts', 'wpse_enqueue_datepicker' );