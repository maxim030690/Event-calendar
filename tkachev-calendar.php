<?php
/*
Plugin Name: Tkachev Event Calendar
Description: Easy events calendar
Version: 1.0
Author: Max Tkachov
Author URI: http://calendar.dev
Plugin URI: http://calendar.dev
*/

define('MAX_CALENDAR_DIR', plugin_dir_path(__FILE__));
define('MAX_CALENDAR_URL', plugin_dir_url(__FILE__));

function max_calendar_load(){
 
    if(is_admin()) // подключаем файлы администратора, только если он авторизован
        require_once(MAX_CALENDAR_DIR.'includes/admin.php');
		require_once(MAX_CALENDAR_DIR.'includes/core.php');
}
max_calendar_load();

register_activation_hook(__FILE__, 'max_calendar_activation');
 
function max_calendar_activation() {
    register_uninstall_hook(__FILE__, 'max_calendar_uninstall');
}
 
function max_calendar_uninstall(){
}