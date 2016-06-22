<?php
/*
Plugin Name: wp-forums: delete_theme example?
Plugin URI: https://github.com/aubreypwd/wp-forums/
Description:
Version: 1.0.0
Author: Aubrey Portwood
Author URI: http://twitter.com/aubreypwd
License: GPL2
Topic URI: https://wordpress.org/support/topic/delete_theme-example?replies=3
*/

/*
 * Copyright 2015 Aubrey Portwood <aubreypwd@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Will delete themes except the one's specified.
 *
 * Note with this implementation, everytime the
 * Dashboard is accessed it deletes all the themes
 * except the ones specified.
 *
 * @author  Aubrey Portwood
 * @since   1.0.0
 */
function wp_forums_aubreypwd_delete_themes() {

	// The current themes.
	$themes = wp_get_themes();

	// The themes we want to keep (delete the others).
	$themes_to_keep = array(

		// Replace this with the theme(s) you created or don't want deleted.
		'twentyeleven', // Going to leave at least one active.
		'another-theme',
	);

	// Loop through installed themes.
	foreach ( $themes as $theme ) {

		// This is the name of the theme.
		$name = $theme->get_template();

		// If it's not one we want to keep...
		if ( ! in_array( $name, $themes_to_keep ) ) {
			$stylesheet = $theme->get_stylesheet();

			// Delete the theme.
			delete_theme( $stylesheet, false );
		}
	}
}
add_action( 'admin_init', 'wp_forums_aubreypwd_delete_themes' );
