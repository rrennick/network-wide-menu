<?php
/*
Plugin Name: Network wide menu
Plugin URI: http://wpmututorials.com/plugins/networkwide-menu/
Description: Implements a network wide menu using a menu in the main site of your network
Version: 0.1
Author: Ron Rennick
Author URI: http://ronandandrea.com/
Network: true

*/
/* Copyright:   (C) 2011 Ron Rennick, All rights reserved.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$slot = 1;

function ra_wp_nav_menu_filter( $content, $args ) {
	global $slot;
	
	$registered = get_registered_nav_menus();
	$current = array_slice( $registered, absint( $slot - 1 ), 1 );
	if( empty( $current ) || empty( $current[$args->theme_location] ) )
		return $content;
		
	if( !is_main_site() ) {
		$network_menu = get_site_option( 'ra_network_menu' );
		if( !empty( $network_menu ) )
			return $network_menu;
	} elseif( !get_option( 'ra_network_menu' ) ) {
		update_option( 'ra_network_menu', '1' );
		update_site_option( 'ra_network_menu', $content );
	}
			
	return $content;
}
add_filter( 'wp_nav_menu_objects', 'ra_wp_nav_menu_filter', 10, 2 );

function ra_wp_nav_menu_flush( $post_id, $post ) {
	if( is_main_site() && $post->post_type == 'nav_menu_item' )
		update_option( 'ra_network_menu', '' );
}
add_action( 'save_post', 'ra_wp_nav_menu_flush', 10, 2 );