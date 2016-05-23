<?php
/*
Plugin Name: wp-forums: Always using gallery instead of single media
Plugin URI: https://github.com/aubreypwd/wp-forums/
Description:
Version: 1.0.0
Author: Aubrey Portwood
Author URI: http://twitter.com/aubreypwd
License: GPL2
Topic URI: https://wordpress.org/support/topic/always-using-gallery-instead-of-single-media
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
 * Change Add Media to Add Gallery on Post Edit Screens.
 *
 * @author Aubrey Portwood
 * @since  1.0.0
 *
 * @param  string $translated_text The translated text.
 * @param  string $text            The original text.
 * @param  string $domain          The text domain.
 *
 * @return string                  If we're on a edit post screen, replace Add Media
 *                                 with Add Gallery.
 */
function wp_forums_aubreypwd_change_add_media( $translated_text, $text, $domain ) {

	// Only on the admin side.
	if ( ! is_admin() ) {
		return $translated_text;
	}

	// The current screen (because we can't get_current_screen()).
	$self = basename( $_SERVER['PHP_SELF'] );

	// Just when editing or adding a new post.
	$screens = array(
		'post-new.php',
		'post.php',
	);

	// Only show on the above screens.
	if ( ! in_array( $self, $screens ) ) {
		return $translated_text;
	}

	if ( 'Add Media' == $text ) {
		return __( 'Add Gallery' );
	}

	return $translated_text;
}
add_filter( 'gettext', 'wp_forums_aubreypwd_change_add_media', 20, 3 );
