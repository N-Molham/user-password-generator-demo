<?php
/*
Plugin Name: Dashboard User Password Generator
Plugin URI: http://nabeel.molham.me/blog/plugins/user-password-generator-demo
Description: Add a generator button next password field in the dashboard
Version: 1.0
Author: Nabeel Molham
Author URI: http://nabeel.molham.me/
Text Domain: user-password-generator-demo
Domain Path: /languages
License: GNU General Public License, version 2, http://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	die();

/**
 * Constants
 */
define( 'UPGD_MAIN_FILE', __FILE__ );
define( 'UPGD_DIR', plugin_dir_path( UPGD_MAIN_FILE ) );
define( 'UPGD_URI', plugin_dir_url( UPGD_MAIN_FILE ) );
define( 'UPGD_DOMAIN', 'user-password-generator-demo' );
define( 'UPGD_VERSION', '1.0' );

/**
 * User Password Generator plugin Main
 * 
 * @class User_Password_Generator_Demo
 * @version	1.0
 */
class User_Password_Generator_Demo
{
	/**
	 * Plugin Version
	 * 
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * @var User_Password_Generator_Demo The single instance of the class
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Constructor
	 * 
	 * @since 1.0
	 * 
	 * @return User_Password_Generator_Demo
	 */
	public function __construct()
	{
		// load language files
		add_action( 'plugins_loaded', array( &$this, 'load_language' ) );

		// password hint content filter
		add_filter( 'password_hint', array( &$this, 'generator_ui' ) );

		// Dashboard hook for add js and css files
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueues' ) );
	}

	/**
	 * Password Generator UI
	 * 
	 * @since 1.0
	 * 
	 * @param string $hint
	 * @return string
	 */
	public function generator_ui( $hint )
	{
		// close the description p tag
		$hint .= '</p>';

		// generator button
		$generator = '<br class="clear" />'; 
		$generator .= '<button type="button" id="generator-button" class="button">'. __( 'Generate Random Password', UPGD_DOMAIN ) .'</button>';
		$generator .= '&nbsp;&nbsp;'; 
		$generator .= '<input type="text" readonly id="generator-result" class="regular-text" placeholder="'. esc_attr( __( 'Generated password will be shown here to copy', UPGD_DOMAIN ) ) .'" />';

		// generator options START
		// will be closed when printed
		$generator .= '<p class="upgd-generator-options">';

		// length
		$generator .= '<label>'. __( 'Length', UPGD_DOMAIN ) .' <input type="number" id="generator-length" step="1" min="12" class="small-text" value="24" /></label>';

		// use special chars
		$generator .= '&nbsp;&nbsp;';
		$generator .= '<label><input type="checkbox" id="generator-use-special" checked="checked" /> ';
		$generator .= __( 'Use Special Characters', UPGD_DOMAIN ) .' <code>!@#$%^&*()</code>'; 
		$generator .= '</label>';

		// use EXTRA special chars
		$generator .= '&nbsp;&nbsp;'; 
		$generator .= '<label><input type="checkbox" id="generator-use-extra" /> ';
		$generator .= __( 'Use <b>EXTRA</b> Special Characters', UPGD_DOMAIN ) .' <code>-_ []{}<>~`+=,.;:/?|</code>'; 
		$generator .= '</label>';

		// return hint with additional UI layout
		return $hint . $generator;
	}

	/**
	 * Assets Enqueues
	 * 
	 * @since 1.0
	 * 
	 * @return void
	 */
	public function enqueues()
	{
		// current page ID
		$screen_id = get_current_screen()->id;

		// don't do anything if a page other than "adding new user" or "user profile" page
		if ( !in_array( $screen_id, array( 'user', 'profile' ) ) )
			return;

		// load Style file
		wp_enqueue_style( 'upgd-generator-style', UPGD_URI .'assets/css/generator.css' );

		// load Javascript file in footer
		wp_enqueue_script( 'upgd-generator-script', UPGD_URI .'assets/js/generator.js', array( 'jquery' ), false, true );

		// some data the generator depends on
		wp_localize_script( 'upgd-generator-script', 'upgd', array ( 
				'password' => array ( 
						'chars' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
						'special_chars' => '!@#$%^&*()',
						'extra_special_chars' => '-_ []{}<>~`+=,.;:/?|',
				),
		) );
	}

	/**
	 * Language file loading
	 * 
	 * @since 1.0
	 * 
	 * @return void
	 */
	public function load_language()
	{
		load_plugin_textdomain( UPGD_DOMAIN, false, UPGD_DIR . 'languages/' );
	}

	/**
	 * Main User_Password_Generator_Demo Instance
	 *
	 * Ensures only one instance of User_Password_Generator_Demo is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @static
	 * @see UPGP()
	 * 
	 * @return User_Password_Generator_Demo - Main instance
	 */
	public static function instance() 
	{
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();

		return self::$_instance;
	}
}

/**
 * Returns the main instance of the plugin to prevent the need to use globals.
 *
 * @since  1.0
 * 
 * @return User_Password_Generator_Demo
 */
function UPGP()
{
	return User_Password_Generator_Demo::instance();
}

// startup plugin
$GLOBALS['user_password_generator_demo'] = UPGP();

