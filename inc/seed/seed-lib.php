<?php
/**
 * Shared helpers for the eval-file seeders in this directory.
 *
 * Every function is guarded so a seeder that defines its own copy (seed-history
 * predates this file) can still be loaded in the same process without a
 * redeclare fatal.
 *
 * @package stjo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

if ( ! function_exists( 'stjo_seed_say' ) ) {
	function stjo_seed_say( $msg ) {
		if ( class_exists( 'WP_CLI' ) ) {
			WP_CLI::log( $msg );
			return;
		}
		echo $msg . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

if ( ! function_exists( 'stjo_seed_abort' ) ) {
	function stjo_seed_abort( $msg ) {
		if ( class_exists( 'WP_CLI' ) ) {
			WP_CLI::error( $msg );
		}
		exit( $msg . "\n" ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

if ( ! function_exists( 'stjo_seed_find_page' ) ) {
	/**
	 * A page by slug in any state but trash, drafts included (get_posts() with
	 * name= misses drafts from the CLI). Returns the WP_Post or null.
	 *
	 * @param string $slug post_name.
	 * @return WP_Post|null
	 */
	function stjo_seed_find_page( $slug ) {
		global $wpdb;
		$id = $wpdb->get_var( $wpdb->prepare( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'page' AND post_name = %s AND post_status NOT IN ('trash','auto-draft') ORDER BY (post_status = 'publish') DESC, ID ASC LIMIT 1",
			$slug
		) );
		return $id ? get_post( (int) $id ) : null;
	}
}

if ( ! function_exists( 'stjo_seed_find_attachment_by_filename' ) ) {
	/**
	 * An existing attachment whose file is this exact filename, however it got
	 * there (typically media-sync registering a deploy-pushed upload).
	 *
	 * @param string $file Source filename.
	 * @return int Attachment ID, or 0.
	 */
	function stjo_seed_find_attachment_by_filename( $file ) {
		$candidates = get_posts( array(
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'posts_per_page' => 20,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array( array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'key'     => '_wp_attached_file',
				'value'   => $file,
				'compare' => 'LIKE',
			) ),
		) );
		foreach ( $candidates as $id ) {
			if ( basename( (string) get_post_meta( $id, '_wp_attached_file', true ) ) === $file ) {
				return (int) $id;
			}
		}
		return 0;
	}
}

if ( ! function_exists( 'stjo_seed_find_orphan_upload' ) ) {
	/**
	 * An uploads file with this exact name that no attachment row points at
	 * (a files-only deploy push leaves these behind).
	 *
	 * @param string $file Source filename.
	 * @return string Absolute path, or ''.
	 */
	function stjo_seed_find_orphan_upload( $file ) {
		$dir  = wp_upload_dir();
		$base = trailingslashit( $dir['basedir'] );
		$hits = array_merge(
			(array) glob( $base . '*/*/' . $file ),
			(array) glob( $base . $file )
		);
		foreach ( array_filter( $hits ) as $hit ) {
			$relative = ltrim( str_replace( $base, '', $hit ), '/' );
			$attached = get_posts( array(
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'meta_query'     => array( array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'key'     => '_wp_attached_file',
					'value'   => $relative,
					'compare' => '=',
				) ),
			) );
			if ( ! $attached ) {
				return $hit;
			}
		}
		return '';
	}
}

if ( ! function_exists( 'stjo_seed_import_images' ) ) {
	/**
	 * Import a directory of theme-shipped images into the Media Library once.
	 *
	 * Same portability contract as seed-history: each attachment is tagged
	 * `_stjo_seed_source` = filename so stjo_seeded_image() resolves it in
	 * whichever environment the seeder runs, and alt text in the JSON map stays
	 * authoritative. Re-runs reuse (or adopt) existing rows; nothing duplicates.
	 *
	 * @param string $dir Absolute directory holding the files.
	 * @param array  $map filename => alt text.
	 * @return array filename => attachment ID.
	 */
	function stjo_seed_import_images( $dir, array $map ) {
		$ids      = array();
		$imported = 0;
		$reused   = 0;
		$adopted  = 0;

		foreach ( $map as $file => $alt ) {
			$existing = stjo_seeded_image( $file );
			if ( $existing['id'] ) {
				update_post_meta( $existing['id'], '_wp_attachment_image_alt', $alt );
				$ids[ $file ] = (int) $existing['id'];
				$reused++;
				continue;
			}

			$found = stjo_seed_find_attachment_by_filename( $file );
			if ( $found ) {
				update_post_meta( $found, '_wp_attachment_image_alt', $alt );
				update_post_meta( $found, '_stjo_seed_source', $file );
				$ids[ $file ] = $found;
				$adopted++;
				continue;
			}

			$path = trailingslashit( $dir ) . $file;
			if ( ! file_exists( $path ) ) {
				stjo_seed_say( '  MISSING FILE: ' . $file );
				continue;
			}
			$bytes  = (string) file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			$orphan = stjo_seed_find_orphan_upload( $file );
			if ( $orphan ) {
				if ( md5_file( $orphan ) !== md5( $bytes ) ) {
					file_put_contents( $orphan, $bytes ); // phpcs:ignore WordPress.WP.AlternativeFunctions
				}
				$file_path = $orphan;
			} else {
				$upload = wp_upload_bits( $file, null, $bytes );
				if ( ! empty( $upload['error'] ) ) {
					stjo_seed_say( '  upload failed: ' . $file . ': ' . $upload['error'] );
					continue;
				}
				$file_path = $upload['file'];
			}

			$type   = wp_check_filetype( $file_path, null );
			$att_id = wp_insert_attachment( array(
				'post_mime_type' => $type['type'],
				'post_title'     => sanitize_text_field( pathinfo( $file, PATHINFO_FILENAME ) ),
				'post_status'    => 'inherit',
			), $file_path );
			if ( is_wp_error( $att_id ) || ! $att_id ) {
				stjo_seed_say( '  attachment insert failed: ' . $file );
				continue;
			}
			wp_update_attachment_metadata( $att_id, wp_generate_attachment_metadata( $att_id, $file_path ) );
			update_post_meta( $att_id, '_wp_attachment_image_alt', $alt );
			update_post_meta( $att_id, '_stjo_seed_source', $file );
			$ids[ $file ] = (int) $att_id;
			$imported++;
		}

		stjo_seed_say( sprintf( '  %d imported, %d already seeded, %d adopted, %d total', $imported, $reused, $adopted, count( $ids ) ) );
		return $ids;
	}
}
