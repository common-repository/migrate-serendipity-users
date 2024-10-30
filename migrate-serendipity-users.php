<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: Migrate Serendipity Users
Description: A plugin to migrate users from Serendipity to WordPress.
Author: Ramlal Solanki
Author URI: https://about.me/ramlal
Version: 1.0
*/
//to add custom page in admin section
add_action('admin_menu', 'migrate_serendipity_users_plugin');
function migrate_serendipity_users_plugin(){
	$plugins_url = plugin_dir_url( __FILE__ ) . 'images/serendipity.jpg' ;
	add_menu_page( 'Migrate serendipity Users', 'Migrate Serendipity Users', 'manage_options', 'migrate-serendipity-users-plugin', 'migrate_serendipity_users_init', $plugins_url );
}

function migrate_serendipity_users_init(){
	require plugin_dir_path( __FILE__ ) . 'migrate_serendipity_users.php';
}


?>