<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
/*
@TODO idea for future use:
function sliced_ff_delete_plugin() {
	global $wpdb;
    
	$wpdb->query( sprintf( "DROP TABLE IF EXISTS %s",
		$wpdb->prefix . 'siff_settings' ) );
}

sliced_ff_delete_plugin();
*/
