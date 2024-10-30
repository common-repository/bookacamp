<?php

/**
 * Fired during plugin init
 *
 * @link       https://bookacamp.de/
 * @since      1.0.0
 *
 * @package    Wp_Bookacamp
 * @subpackage Wp_Bookacamp/admin
 */

/**
 * Fired during plugin init.
 *
 * This class defines all code necessary to register custom post types.
 *
 * @package    Wp_Bookacamp
 * @subpackage Wp_Bookacamp/admin
 * @author     Mathias Methner, Bookacamp <support@bookacamp.de>
 */
class Wp_Bookacamp_Blocktypes {

	/**
	 * Registers the bookacamp Gutenberg block
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function register_blocks() {

		if ( ! function_exists( 'register_block_type' ) ) {
			// Gutenberg is not active.
			return;
		}

		wp_register_script(
			'wp-bookacamp-blocks',
			plugins_url( 'js/wp-bookacamp-block.js', __FILE__ ),
			[ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor', 'wp-editor' ],
			filemtime( plugin_dir_path( __FILE__ ) . 'js/wp-bookacamp-block.js' )
		);

		$bookacamp_skey = get_option( 'wp-bookacamp_skey' );

		$inline_script = '
        var bookacamp_skey = "' . $bookacamp_skey . '";
        var wp_admin_ajax_url = "' . admin_url( 'admin-ajax.php' ) . '";
        var wp_admin_ajax_nonce = "' . wp_create_nonce( 'wp-bookacamp-nonce' ) . '";';
		wp_add_inline_script( 'wp-bookacamp-blocks', $inline_script, 'before' );

		register_block_type( 'wp-bookacamp/searchresults', [
			'api_version'   => 2,
			'editor_script' => 'wp-bookacamp-blocks',
			'attributes'    => [
				'campType'     => [
					'type'    => 'string',
					'default' => '',
				],
				'label1'       => [
					'type'    => 'string',
					'default' => '',
				],
				'label2'       => [
					'type'    => 'string',
					'default' => '',
				],
				'embeddedCode' => [
					'type'    => 'string',
					'default' => '',
				]
			],
		] );
	}

	/**
	 * Registers the bookacamp shortcodes
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {

		//self::register_cpt_soaringspot_contest();

	}

	/**
	 * Requests and returns available filters from bookacamp.de
	 * This is a proxy method to be called from the block.js
	 *
	 * @since   1.0.0
	 */
	public function ajax_get_bookacamp_filters() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wp-bookacamp-nonce' ) ) {
			wp_die( - 1 );
		}
		$bookacamp_skey     = get_option( 'wp-bookacamp_skey' );
		$bookacamp_username = get_option( 'wp-bookacamp_username' );
		$bookacamp_password = get_option( 'wp-bookacamp_password' );

		echo $this->get_bookacamp_filters( $bookacamp_skey, $bookacamp_username, $bookacamp_password );
		wp_die();
	}

	/**
	 * Requests and returns available filters from bookacamp
	 * by using the Skey
	 *
	 * @return string a json string
	 * @since   1.0.0
	 */
	private function get_bookacamp_filters( $skey, $username, $password ) {
		$raw_response = $this->get_bookacamp_filter_html();

		$filters     = [];
		$filter_name = '';

		$html = new DOMDocument();
		$html->loadHTML( $raw_response );

		foreach ( $html->getElementsByTagName( 'select' ) as $node ) {
			$node_name = $node->getAttribute( 'name' );

			$filters[ $node_name ] = [];

			$options = $node->getElementsByTagName( 'option' );
			foreach ( $options as $option ) {
				$filter     = [];
				$filtername = $option->getAttribute( 'value' );
				if ( empty( $filtername ) ) {
					$filtername = "null";
				}
				$children = $option->childNodes;
				foreach ( $children as $child ) {
					$filtervalue = $child->ownerDocument->saveXML( $child );
				}
				$filters[ $node_name ][] = [ 'id' => $filtername, 'title' => $filtervalue ];
			}
		}

		return wp_json_encode( $filters );
	}

	/**
	 * Requests the HTML code from bookacamp.de to build the filter select boxes
	 *
	 * @since   1.0.0
	 */
	private function get_bookacamp_filter_html() {
		$bookacamp_skey = get_option( 'wp-bookacamp_skey' );
		$url            = 'https://bookacamp.de/de/api/v1/integration/filter/' . $bookacamp_skey;

		return $this->get_bookacamp_resources( $url );
	}

	/**
	 * Requests resources from bookacamp.de
	 *
	 * uses username and password from the plugin settings
	 *
	 * @return string raw response body
	 *
	 * @since   1.0.0
	 */
	private function get_bookacamp_resources( $url ) {

		$bookacamp_username = get_option( 'wp-bookacamp_username' );
		$bookacamp_password = get_option( 'wp-bookacamp_password' );

		$request     = [
			'headers' => [
				'Authorization' => 'Digest ' . $this->get_bookacamp_digest_header(
						$url,
						$bookacamp_username,
						$bookacamp_password
					),
			],
		];
		$response    = wp_remote_get( $url, $request );
		$status_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $status_code ) {
			return __( 'Fehler beim Abrufen der Daten von bookacamp.de (Code ' . $status_code . '). Bitte versuchen Sie es später erneut',
				'wp-bookacamp' );
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Requests resources from bookacamp.de
	 *
	 * uses username and password from the plugin settings
	 *
	 * @return string raw response body
	 *
	 * @since   1.0.0
	 */
	private function get_bookacamp_digest_header( $url, $bookacamp_username, $bookacamp_password ) {

		/*
		 * this code is adapted and reviewed from
		 * https://danielbachhuber.com/2016/01/08/using-http-digest-authentication-with-wordpress-wp_remote_get/
		 * date January 8, 2016
		 *
		 * and
		 * Beispiel #2 Beispiel: Digest-HTTP-Authentifizierung
		 * https://www.php.net/manual/de/features.http-auth.php
		 *
		 * Makes an initial request for the server's provided headers
		 */
		$response = wp_remote_get( $url, $request );
		$header   = wp_remote_retrieve_header( $response, 'www-authenticate' );
		if ( empty( $header ) ) {
			return '';
		}

		$matches     = [];
		$server_bits = [];
		/*
		 * Parses the 'www-authenticate' header for nonce, realm and other values.
		 *
		 * $header looks like
		 * Digest realm="bookacamp authentication",qop="auth",nonce="611e1e6f9a552",opaque="a707ae2edc22354e2f8d80a9af0fd335"
		 */
		preg_match_all(
			'#(realm|nonce|qop|opaque)=(?:([\'"])([^\2]+?)\2|([^\s,]+))#',
			$header,
			$matches,
			PREG_SET_ORDER
		);

		foreach ( $matches as $match ) {
			$server_bits[ $match[1] ] = $match[3] ?? $match[4];
		}

		$nc           = '00000001';
		$path         = parse_url( $url, PHP_URL_PATH );
		$client_nonce = uniqid();
		$ha1          = md5( $bookacamp_username . ':' . $server_bits['realm'] . ':' . $bookacamp_password );
		$ha2          = md5( 'GET:' . $path );

		// The order of this array matters, because it affects resulting hashed val
		$response_bits = [
			$ha1,
			$server_bits['nonce'],
			$nc,
			$client_nonce,
			$server_bits['qop'],
			$ha2
		];

		$digest_header_values = [
			'username' => sprintf( 'username="%s"', $bookacamp_username ),
			'realm'    => sprintf( 'realm="%s"', $server_bits['realm'] ),
			'nonce'    => sprintf( 'nonce="%s"', $server_bits['nonce'] ),
			'uri'      => sprintf( 'uri="%s"', $path ),
			'response' => sprintf( 'response="%s"', md5( implode( ':', $response_bits ) ) ),
			'opaque'   => sprintf( 'opaque="%s"', $server_bits['opaque'] ),
			'qop'      => sprintf( 'qop=%s', $server_bits['qop'] ),
			'nc'       => sprintf( 'nc=%s', $nc ),
			'cnonce'   => sprintf( 'cnonce="%s"', $client_nonce )
		];

		return implode( ', ', $digest_header_values );
	}

	/**
	 * Requests iFrame code from bookacamp.de based on selected filters
	 * This method is a proxy method to be called from the block.js
	 */
	public function ajax_get_bookacamp_iframe() {
		global $allowedposttags;

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wp-bookacamp-nonce' ) ) {
			wp_die( - 1 );
		}
		$attributes             = [];
		$attributes['campType'] = sanitize_text_field( $_POST['campType'] ?? '' );
		$attributes['label1']   = sanitize_text_field( $_POST['label1'] ?? '' );
		$attributes['label2']   = sanitize_text_field( $_POST['label2'] ?? '' );

		$framecode = $this->get_bookacamp_iframe( $attributes );
		$framecode = str_replace( [ "\r", "\n", "\t" ], "", $framecode );

		$allowedposttags['iframe'] = [
			'src'             => true,
			'height'          => true,
			'width'           => true,
			'frameborder'     => true,
			'allowfullscreen' => true
		];
		$allowedposttags['script'] = [
			'type' => true
		];

		echo wp_kses_post( $framecode);

		wp_die();
	}

	/**
	 * Requests iFrame code from bookacamp.de based on selected filters
	 *
	 * @since   1.0.0
	 */
	private function get_bookacamp_iframe( $attributes ) {
		$bookacamp_skey = get_option( 'wp-bookacamp_skey' );

		$url = 'https://bookacamp.de/de/api/v1/integration/code/' . $bookacamp_skey . '/' . $attributes['campType'];
		if ( ( ! empty( $attributes['label1'] ) ) && $attributes['label1'] != 'null' ) {
			$url .= '/' . $attributes['label1'];
		}
		if ( ( ! empty( $attributes['label2'] ) ) && $attributes['label2'] != 'null' ) {
			$url .= '/' . $attributes['label2'];
		}

		return $this->get_bookacamp_resources( $url );
	}
}
