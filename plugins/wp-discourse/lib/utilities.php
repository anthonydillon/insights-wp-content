<?php
/**
 * Static utility functions used throughout the plugin.
 *
 * @package WPDiscourse
 */

namespace WPDiscourse\Utilities;

/**
 * Class Utilities
 *
 * @package WPDiscourse
 */
class Utilities {

	/**
	 * Returns a single array of options from a given array of arrays.
	 *
	 * @return array
	 */
	public static function get_options() {
		$options = [];

		$discourse_option_groups = get_option( 'discourse_option_groups' );
		if ( $discourse_option_groups ) {
			foreach ( $discourse_option_groups as $option_name ) {
				if ( get_option( $option_name ) ) {
					$option  = get_option( $option_name );
					$options = array_merge( $options, $option );
				}
			}
		}

		return $options;
	}

	/**
	 * Checks the connection status to Discourse.
	 *
	 * @return int
	 */
	public static function check_connection_status() {
		$options = self::get_options();
		$url     = array_key_exists( 'url', $options ) ? $options['url'] : '';
		$url     = add_query_arg( array(
			'api_key'      => array_key_exists( 'api-key', $options ) ? $options['api-key'] : '',
			'api_username' => array_key_exists( 'publish-username', $options ) ? $options['publish-username'] : '',
		), $url . '/users/' . $options['publish-username'] . '.json' );

		$url      = esc_url_raw( $url );
		$response = wp_remote_get( $url );

		return self::validate( $response );
	}

	/**
	 * Validates the response from `wp_remote_get` or `wp_remote_post`.
	 *
	 * @param array $response The response from `wp_remote_get` or `wp_remote_post`.
	 *
	 * @return int
	 */
	public static function validate( $response ) {
		if ( empty( $response ) ) {
			error_log( 'Discourse has returned an empty response.' );

			return 0;
		} elseif ( is_wp_error( $response ) ) {
			error_log( $response->get_error_message() );

			return 0;

			// There is a response from the server, but it's not what we're looking for.
		} elseif ( intval( wp_remote_retrieve_response_code( $response ) ) !== 200 ) {
			$error_message = wp_remote_retrieve_response_message( $response );
			error_log( 'There has been a problem accessing your Discourse forum. Error Message: ' . $error_message );

			return 0;
		} else {
			// Valid response.
			return 1;
		}
	}

	/**
	 * Gets the Discourse categories.
	 *
	 * @return array|mixed|object|\WP_Error|WP_Error
	 */
	public static function get_discourse_categories() {
		$options = self::get_options();

		$url          = add_query_arg( array(
			'api_key'      => $options['api-key'],
			'api_username' => $options['publish-username'],
		), $options['url'] . '/site.json' );
		$force_update = isset( $options['publish-category-update'] ) ? $options['publish-category-update'] : '0';
		$remote       = get_transient( 'discourse_settings_categories_cache' );
		$cache        = $remote;
		if ( empty( $remote ) || $force_update ) {
			$remote = wp_remote_get( $url );
			if ( ! self::validate( $remote ) ) {
				if ( ! empty( $cache ) ) {
					return $cache;
				}

				return new \WP_Error( 'connection_not_established', 'There was an error establishing a connection with Discourse' );
			}
			$remote = json_decode( wp_remote_retrieve_body( $remote ), true );
			if ( array_key_exists( 'categories', $remote ) ) {
				$remote = $remote['categories'];
				if ( ! isset( $options['display-subcategories'] ) || 0 === intval( $options['display-subcategories'] ) ) {
					foreach ( $remote as $category => $values ) {
						if ( array_key_exists( 'parent_category_id', $values ) ) {
							unset( $remote[ $category ] );
						}
					}
				}
				set_transient( 'discourse_settings_categories_cache', $remote, HOUR_IN_SECONDS );
			} else {
				return new \WP_Error( 'key_not_found', 'The categories key was not found in the response from Discourse.' );
			}
		}

		return $remote;
	}
}
