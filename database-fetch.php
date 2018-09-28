<?php 

/*

Plugin Name: Databases Fetch
Plugin URI: http://aubbusta.com
Description: Fetch any table to cvs file
Version: 0.2
Author: Ayoub Bousetta
Author URI: http://aubbusta.com
License: GPL2

*/
?><?php 


class Database_fetch{



	//Execute Hooks
		 public function __construct()
		    {
		       
		        add_action('admin_menu', array($this, 'my_plugin_menu'), 20);
		        wp_enqueue_style('my_plugin_menu', plugins_url('/assets/css/main.css', __FILE__));
		        $this->creat_tabel();
		        

		   	}



		//Create Table in database
		public function creat_tabel()
		{
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		if($wpdb->get_var("SHOW TABLES LIKE fetch_database")) {
		  $sql = "CREATE TABLE fetch_database (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  created_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  database_name varchar(55) DEFAULT '' NOT NULL,
		  separator_style varchar(55) DEFAULT '' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
}

if($wpdb->get_var("SHOW TABLES LIKE $table_name") != $table_name) {
  /*For table drop use $wpdb->query("DROP TABLE IF EXISTS $table_name");*/
  $sql = "CREATE TABLE full_fetch_data (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  created_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  database_name varchar(55) DEFAULT '' NOT NULL,
  separator_style varchar(55) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

}

		}








		//Create Menu in Dashboard
		public function my_plugin_menu() {
				
		add_menu_page( 'Database Fetch - Aubbusta Plugin', 'Database Fetch', 'manage-options','menu-parent-fulldata', '', 'dashicons-editor-kitchensink', 7 );
		add_submenu_page( 'menu-parent-fulldata', 'Configuration', 'Configuration', 'manage_options', 'database-fetch/config.php');
		add_submenu_page( 'menu-parent-fulldata', 'Show all data', 'Show all data', 'manage_options', 'database-fetch/appfiles/core-index.php');
		add_submenu_page( 'menu-parent-fulldata', 'Export all data', 'Export all data', 'manage_options', 'database-fetch/appfiles/core-export.php');

		}











}




new Database_fetch;