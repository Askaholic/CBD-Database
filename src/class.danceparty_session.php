<?php
// Code is based on class.edd_session.php from http://edd.wp-a2z.org/oik-plugins/easy-digital-downloads/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DanceParty_Session {
	
	private $session;	//Holds session data
	private $prefix = '';	// Session index prefix

	/**
	 * Get things started
	 *
	 * Defines our WP_Session constants, includes the necessary libraries and
	 * retrieves the WP Session instance
	 */
	public function __construct() {
		
		if( ! $this->should_start_session() ) {
			return;
		}
		if ( ! defined( 'WP_SESSION_COOKIE' ) ) {
			define( 'WP_SESSION_COOKIE', 'danceparty_wp_session' );
		}
		if ( ! class_exists( 'Recursive_ArrayAccess' ) ) {
			require_once(plugin_dir_path(__FILE__) . 'libraries/class-recursive-arrayaccess.php';
		}

		if ( ! class_exists( 'WP_Session' ) ) {
			require_once(plugin_dir_path(__FILE__) . 'libraries/class-wp-session.php');
			require_once(plugin_dir_path(__FILE__) . 'libraries/wp-session.php';
		}
		
		add_filter( 'wp_session_expiration_variant', array( $this, 'set_expiration_variant_time' ), 99999 );
		add_filter( 'wp_session_expiration', array( $this, 'set_expiration_time' ), 99999 );
		
		if ( empty( $this->session ) ) {
			add_action( 'plugins_loaded', array( $this, 'init' ), -1 );
		} else {
			add_action( 'init', array( $this, 'init' ), -1 );
		}
	}
	
	/**
	 * Setup the WP_Session instance
	 *
	 * @return void
	 */
	public function init() {
		$this->session = WP_Session::get_instance();
		return $this->session;
	}
	
	/**
	 * Retrieve session ID
	 *
	 * @return string Session ID
	 */
	public function get_id() {
		return $this->session->session_id;
	}
	
	/**
	 * Retrieve a session variable
	 *
	 * @access public
	 * @param string $key Session key
	 * @return mixed Session variable
	 */
	public function get( $key ) {
		$key    = sanitize_key( $key );
		$return = false;
		if ( isset( $this->session[ $key ] ) && ! empty( $this->session[ $key ] ) ) {
			preg_match( '/[oO]\s*:\s*\d+\s*:\s*"\s*(?!(?i)(stdClass))/', $this->session[ $key ], $matches );
			if ( ! empty( $matches ) ) {
				$this->set( $key, null );
				return false;
			}
			
			if ( is_numeric( $this->session[ $key ] ) ) {
				$return = $this->session[ $key ];
			} else {
				$maybe_json = json_decode( $this->session[ $key ] );
				if ( is_null( $maybe_json ) ) {
					$is_serialized = is_serialized( $this->session[ $key ] );
					if ( $is_serialized ) {
						$value = @unserialize( $this->session[ $key ] );
						$this->set( $key, (array) $value );
						$return = $value;
					} else {
						$return = $this->session[ $key ];
					}
				} else {
					$return = json_decode( $this->session[ $key ], true );
				}
			}
		}
		return $return;
	}
	
	/**
	 * Set a session variable
	 *
	 * @param string $key Session key
	 * @param int|string|array $value Session variable
	 * @return mixed Session variable
	 */
	public function set( $key, $value ) {
		$key = sanitize_key( $key );
		if ( is_array( $value ) ) {
			$this->session[ $key ] = wp_json_encode( $value );
		} else {
			$this->session[ $key ] = esc_attr( $value );
		}
		return $this->session[ $key ];
	}


	/**
	 * Force the cookie expiration variant time to 23 hours
	 *
	 * @access public
	 * @since 2.0
	 * @param int $exp Default expiration (1 hour)
	 * @return int
	 */
	public function set_expiration_variant_time( $exp ) {
		return ( 30 * 60 * 23 );
	}

	/**
	 * Force the cookie expiration time to 24 hours
	 *
	 * @access public
	 * @since 1.9
	 * @param int $exp Default expiration (1 hour)
	 * @return int Cookie expiration time
	 */
	public function set_expiration_time( $exp ) {
		return ( 30 * 60 * 24 );
	}
	
	/**
	 * Determines if we should start sessions
	 *
	 * @return bool
	 */
	public function should_start_session() {
		$start_session = true;
		if( ! empty( $_SERVER[ 'REQUEST_URI' ] ) ) {
			$uri = ltrim( $_SERVER[ 'REQUEST_URI' ], '/' );
			$uri = untrailingslashit( $uri );
			if( false !== strpos( $uri, 'feed=' ) ) {
				$start_session = false;
			}
			if( is_admin() && false === strpos( $uri, 'wp-admin/admin-ajax.php' ) ) {
				// We do not want to start sessions in the admin unless we're processing an ajax request
				$start_session = false;
			}
			if( false !== strpos( $uri, 'wp_scrape_key' ) ) {
				// Starting sessions while saving the file editor can break the save process, so don't start
				$start_session = false;
			}
		}
		return apply_filters( 'danceparty_start_session', $start_session );
	}
	
	/**
	 * Starts a new session if one hasn't started yet.
	 */
	public function maybe_start_session() {
		if( ! $this->should_start_session() ) {
			return;
		}
		if( ! session_id() && ! headers_sent() ) {
			session_start();
		}
	}
}