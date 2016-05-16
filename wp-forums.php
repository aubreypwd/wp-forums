<?php
/*
Plugin Name: wp-forums
Plugin URI: https://github.com/aubreypwd/wp-forums/
Description:
Version: 1.0.0
Author: Aubrey Portwood
Author URI: http://twitter.com/aubreypwd
License: GPL2
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
 * Show 90 Day Terms
 *
 * @author  Aubrey Portwood
 * @since 1.0.0
 */
class WP_Forums_aubreypwd_Ninety_Terms extends WP_Widget {

	/**
	 * Sets up the widgets name etc.
	 *
	 * @author Aubrey Portwood
	 * @since 1.0.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'wp-forums-aubreypwd-ninety-terms',
			'description' => __( 'Shows terms used in last 90 days' ),
		);

		parent::__construct( 'wp-forums-aubreypwd-ninety-terms', __( '90 Days Terms' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @author Aubrey Portwood
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		// Before
		echo $args['before_widget'];

		// The posts we're going to get.
		$query_args = array(
			'post_type'    => apply_filters( 'wp_forums_aubreypwd_ninety_terms/post_type', array( 'post' ) ),
			'post_status'  => apply_filters( 'wp_forums_aubreypwd_ninety_terms/post_status', array( 'publish' ) ),

			// Just the id's.
			'fields'       => 'ids',

			// Only posts within last 90 days.
			'date_query' => apply_filters( 'wp_forums_aubreypwd_ninety_terms/date_query', array(
				array(
					'column' => 'post_date_gmt',
					'after'  => '90 days ago',
				),
			) ),
		);

		// Get those posts.
		$posts = get_posts( $query_args );

		// The taxonomies to use, add your custom taxonomy here or use filter.
		$taxonomies = apply_filters( 'wp_forums_aubreypwd_ninety_terms/taxonomies', array( 'category' ) );

		// For each of these taxonomies.
		foreach ( $taxonomies as $taxonomy ) {

			// Collect the terms from all the posts.
			foreach ( $posts as $post_id ) {
				$post_terms[ $taxonomy ][ $post_id ] = wp_get_post_terms( $post_id, $taxonomy, array(
					'fields' => 'ids',
				) );
			}
		}

		if ( ! isset( $post_terms ) ) {
			return; // Nothing to show.
		}

		// Output the list.
		?><ul class="terms"><?php

			// Go thorough the terms.
			foreach ( $post_terms as $taxonomy => $terms ) {
				foreach ( $terms as $term_id ) {

					// Get the term info.
					$term = get_term( $term_id, $taxonomy, OBJECT );

					// Output this category.
					?><li class="term-<?php echo absint( $term_id ); ?> term-<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></li><?php
				}
			}

		?></ul><?php

		// After
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin.
	 *
	 * @author Aubrey Portwood
	 * @since 1.0.0
	 *
	 * @param array $instance The widget options.
	 */
	public function form( $instance ) {
		echo sprintf( __( '%s Place this widget where you want terms to show. %s' ), '<p>', '</p>' );
	}

	/**
	 * Processing widget options on save.
	 *
	 * @author Aubrey Portwood
	 * @since 1.0.0
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 */
	public function update( $new_instance, $old_instance ) {
		// No widget options.
	}
}

/**
 * Register the Widget.
 *
 * @author  Aubrey Portwood
 * @since 1.0.0
 *
 * @return boolean True if we register the widget, false if not.
 */
function wp_forums_aubreypwd_ninety_terms() {
	return register_widget( 'WP_Forums_aubreypwd_Ninety_Terms' );
}

// Register the widget on this hook.
add_action( 'widgets_init', 'wp_forums_aubreypwd_ninety_terms' );
