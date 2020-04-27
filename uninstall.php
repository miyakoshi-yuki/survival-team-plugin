<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

global $wpdb;
$wpdb->sti_table_name = $wpdb->prefix . 'stiTable';
$wpdb->sti2_table_name = $wpdb->prefix . 'stiTable2';
delete_option('sti_db_version');
delete_option('sti_posion_name');
$wpdb->query( sprintf( "DROP TABLE IF EXISTS %s",$wpdb->sti_table_name ) );
$wpdb->query( sprintf( "DROP TABLE IF EXISTS %s",$wpdb->sti2_table_name ) );
