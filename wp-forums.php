<?php
/*
Plugin Name: wp-forums (Plugin)
Plugin URI: https://github.com/aubreypwd/wp-forums/
Description:
Version:
Author: Aubrey Portwood
Author URI: http://twitter.com/aubreypwd
License: GPL2
Topic URI:
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

function wp_forums_get_freshness_count( $freshness ) {
	$freshness = explode( ' ',  $freshness );

	if ( isset( $freshness[0] ) && isset( $freshness[1] ) && ! empty( $freshness[0] ) && ! empty( $freshness[1] ) ) {
		return array(
			'frame'     => $freshness[1],
			'count'     => $freshness[0],
		);
	}

	return false;
}

function wp_forums_forum_data() {
	if ( ! isset( $_GET['forums'] ) ) {
		return;
	}

	require_once( 'simple_html_dom.php' );

	$single_tags_url = "http://wordpress.org/tags/%s";
	$paged_tags_url  = "http://wordpress.org/tags/%s/page/%s";

	// These Forum tags.
	$tags = array(
		'code',
		'php',
		'coding',
		'code',
		'jquery',
		'javascript',
		'js',
		'plugin',
		'plugins',
		'aubreypwd',
	);

	// Go in this many paged pages.
	$paged = 2;

	// Each tag.
	foreach ( $tags as $tag ) {

		// Each page.
		for ( $page = 1; $page <= $paged; $page++ ) {

			if ( 0 == $page ) {
				$tag_url = sprintf( $single_tags_url, $tag );
			} else {
				$tag_url = sprintf( $paged_tags_url, $tag, $page );
			}

			$html = file_get_html( $tag_url );
			$trs = $html->find( '.wrapper table.widefat tbody tr' );

			foreach ( $trs as $row ) {
				$fullname = ( $fullname = $row->find( 'td', 0 ) ) ? $fullname->plaintext : false;

				$record = array(
					'resolved'  => (boolean) stristr( $fullname, '[Resolved]' ),
					'tag'       => $tag,
					'paged_url' => $tag_url,
					'name'      => strip_tags( $fullname ),
					'url'       => ( $link = $row->find( 'td a', 0 ) )    ? $link->href                                            : false,
					'posts'     => ( $posts = $row->find( 'td', 1 )   )   ? $posts->plaintext                                      : false,
					'freshness' => ( $freshness = $row->find( 'td', 3 ) ) ? wp_forums_get_freshness_count( $freshness->plaintext ) : false,
				);

				// Get resolved or not.
				$resolved_filter = false;

				// Does the filter match?
				$filter_match = (bool) $resolved_filter == (bool) $record['resolved'];

				// If filter is set to all, always add the record, otherwise test if the records resolved == the chosen filter.
				$show = ( 'all' === $resolved_filter ) ? true : $filter_match;

				// Name at least, common!
				if ( isset( $record['name'] ) && ! empty( $record['name'] ) && $show ) {

					// Set the record.
					$record_id = sanitize_title_with_dashes( $record['name'] );
					$forum[ $page ][ $record_id ] = $record;

					// Require these.
					$req_keys = array( 'freshness', 'posts', 'url', 'name', );

					// If required key is not met.
					foreach ( $req_keys as $key ) {
						if ( ! isset( $record[ $key ] ) || ! $record[ $key ] ) {

							// Remove the record.
							unset( $record[ $page ][ $record_id ] );
						}
					}

				} // name set
			}
		}
	} // foreach

	if ( ! $forum ) {
		return array();
	}

	// Max posts (e.g. 30 posts is not interesting).
	$max_posts = 2;

	foreach ( $forum as $page_key => $page ) {
		foreach ( $page as $post_key => $post ) {
			if ( isset( $post['posts'] ) && $post['posts'] && $post['posts'] > $max_posts ) {
				unset( $forum[ $page_key ][ $post_key ] );
			}
		}
	}

	// Freshness filters.
	$max_freshness = array(

		// Singular.
		'second'  => 1,
		'minute'  => 1,
		'hour'    => 1,
		'day'     => 1,
		'week'    => 0,
		'month'   => 0,
		'year'    => 0,

		// Plural.
		'seconds' => 60,
		'minutes' => 60,
		'hours'   => 24,
		'days'    => 2,
		'weeks'   => 0,
		'months'  => 0,
		'years'   => 0,
	);

	foreach ( $forum as $page_key => $page ) {
		foreach ( $page as $post_key => $post ) {
			if ( isset( $max_freshness[ $post['freshness']['frame'] ] ) && $post['freshness']['count'] > $max_freshness[ $post['freshness']['frame'] ] ) {
				unset( $forum[ $page_key ][ $post_key ] );
			}
		}
	}

	error_log( print_r( $forum, true ) );
}
add_action( 'init', 'wp_forums_forum_data' );
