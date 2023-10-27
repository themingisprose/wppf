<?php
/**
 * Add Settings link in plugin list page
 *
 * @since 1.0.0
 */
function wppf_add_setings_link( $actions ){
	$dashboard = new WPPF\Admin\Dashboard;
	$args = array(
		'page'	=> $dashboard->menu_slug
	);
	return array_merge( array(
		'settings' => '<a href="'. add_query_arg( $args, admin_url( 'admin.php' ) ) .'">'. __( 'Settings', 'wppf' ) .'</a>'
	), $actions );
}
add_filter( 'plugin_action_links_wppf/wppf.php', 'wppf_add_setings_link' );
add_filter( 'network_admin_plugin_action_links_wppf/wppf.php', 'wppf_add_seting_link' );
