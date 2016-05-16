<?php
/*
Plugin Name: wp-forums: Username with quote can't login
Plugin URI:
Description:
Version:
Author: Aubrey Portwood
Author URI: http://aubreypwd.com
License: GPL2
*/

/*  Copyright 2015 Aubrey Portwood <aubreypwd@gmail.com>

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Remove WP's authenticate filter.
remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );

/**
 * Allows the user to login with a backtick in the username.
 *
 * @author  Aubrey Portwood
 *
 * @param  object $user      If WordPress finds a user object.
 * @param  string $username  The username that may have the backtick.
 * @param  string $password  The user's password.
 *
 * @return boolean           If the user is authenticated or not.
 */
function aubreypwd_wp_forums_username_with_quote_login_authenticate( $user, $username, $password ) {

	// If WP found a user first.
	if ( is_a( $user, 'WP_User' ) ) {
		return $user;
	}

	// We need a username.
	if ( ! empty( $username ) ) {

		// Remove the backtick.
		$username = str_replace( "'", '', stripslashes( $username ) );

		// Get the user by username with backtick removed.
		$user = get_user_by( 'username', $username );

		// If we have a WP user.
		if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status ) {
			$username = $user->user_login;
		}
	}

	// Authenticate the user.
	return wp_authenticate_username_password( null, $username, $password );
}

// Add the filter to use our authentication function.
add_filter( 'authenticate', 'aubreypwd_wp_forums_username_with_quote_login_authenticate', 20, 3 );
