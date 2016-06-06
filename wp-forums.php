<?php
/*
Plugin Name: wp-forums: Show only specific media using wp.media
Plugin URI: https://github.com/aubreypwd/wp-forums/
Description:
Version: 1.0.0
Author: Aubrey Portwood
Author URI: http://twitter.com/aubreypwd
License: GPL2
Topic URI: https://wordpress.org/support/topic/show-only-specific-media-using-wpmedia?replies=1
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
 * We need to enqueue our scripts.
 *
 * @author Aubrey Portwood
 */
function aubreypwd_wp_forums_metabox_add_media_test_scripts() {
	wp_enqueue_media();
	wp_enqueue_script( 'aubreypwd-wp-forums-metabox-add-media-test-script', plugins_url( 'media.js', __FILE__ ), array( 'jquery' ), '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'aubreypwd_wp_forums_metabox_add_media_test_scripts' );

/**
 * Display buttons in a metabox.
 *
 * @author  Aubrey Portwood
 */
function aubreypwd_wp_forums_metabox_add_media_test_display() {
	?>
		<p>
			<!--
				As you can see below, I've
				specified the type in a data-attribute.

				This will get passed to wp.media() as the type of file
				you want to show.

				@see media.js
			-->
			<button class="js-add-media-button" data-type="video"><?php _e( 'Select Video', 'wp-forums' ); ?></button>
			<button class="js-add-media-button" data-type="image"><?php _e( 'Select Image', 'wp-forums' ); ?></button>
			<button class="js-add-media-button" data-type="audio"><?php _e( 'Select Audio', 'wp-forums' ); ?></button>
		</p>
	<?
}

/**
 * Add a metabox to place our buttons.
 *
 * @author  Aubrey Portwood
 */
function aubreypwd_wp_forums_metabox_add_media_test() {
	add_meta_box( 'wp-forums-aubreypwd-add-media-test', __( 'Meta Box', 'wp-forums' ), 'aubreypwd_wp_forums_metabox_add_media_test_display', 'post' );
}
add_action( 'add_meta_boxes', 'aubreypwd_wp_forums_metabox_add_media_test' );
