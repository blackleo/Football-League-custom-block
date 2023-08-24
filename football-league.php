<?php
/*
Plugin Name:  Football League
Plugin URI:    
Description:  Football League 
Version:      1.0
Author:       Mehul Dave 
Author URI:   
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  
Domain Path:  
*/

register_activation_hook(__FILE__, 'myPluginCreateTable');
define('LEAGUEPLUGIN_FILE', __FILE__);
include_once dirname(LEAGUEPLUGIN_FILE) . '/league.php';
include_once dirname(LEAGUEPLUGIN_FILE) . '/team.php';
add_action("admin_menu", "createMenu");
function createMenu(){
    add_menu_page("Football League", "Football League", 0, "football-league", "footballleagueFunction");
    $league = new League();
    $team = new Team();

}

function footballleagueFunction(){

}
function myPluginCreateTable() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name1 = $wpdb->prefix . 'league';
    $sql1 = "CREATE TABLE `$table_name1` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(220) DEFAULT NULL,
        `logo` varchar(220) DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (id)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
    ";
    //$sql1 .= "ALTER TABLE `$table_name1` ADD PRIMARY KEY (`id`)";
    //$sql1 .= "ALTER TABLE `$table_name1` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";
    $table_name2 = $wpdb->prefix . 'team';
    $sql2 = "CREATE TABLE `$table_name2` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `league_id` int(11) NOT NULL,
        `name` varchar(220) DEFAULT NULL,
        `logo` varchar(220) DEFAULT NULL,
        `history` text DEFAULT NULL,
        `nickname` varchar(220) DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (id)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
    ";
    //$sql2 .= "ALTER TABLE `$table_name2` ADD PRIMARY KEY (`id`)";
    //$sql2 .= "ALTER TABLE `$table_name2` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";
    
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name1'") != $table_name1 && $wpdb->get_var("SHOW TABLES LIKE '$table_name2'") != $table_name2) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql1);
        dbDelta($sql2);
    }
    
}
function register_footballleague_widget( $widgets_manager ) {

  require_once( __DIR__ . '/FootballLeaguewidget.php' );

  $widgets_manager->register( new \FootballLeaguewidget() );

}
add_action( 'elementor/widgets/register', 'register_footballleague_widget' );