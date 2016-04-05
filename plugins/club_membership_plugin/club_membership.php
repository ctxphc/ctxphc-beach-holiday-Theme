<?php
/**
 * Plugin Name: Club Membership Management
 * Plugin URI: http://www.clubmembership.com
 * Description: Easily create club membership sites and manage club membership within the WordPress admin.
 * Version: 0.1.9
 * Author: kaosoft
 * Author URI: http://www.kaosfot.com
 * Text Domain: clubmemberships
 * Domain Path: /languages
 *
 * Created by PhpStorm.
 * User: kaptkaos
 * Date: 2/24/2016
 * Time: 7:52 AM
 */


if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! defined( 'CM_CURRENT_PAGE' ) ) {
	define( 'CM_CURRENT_PAGE', basename( $_SERVER['PHP_SELF'] ) );
}

if ( ! defined( 'IS_ADMIN' ) ) {
	define( 'IS_ADMIN', is_admin() );
}

define( 'CM_CURRENT_VIEW', CMClass::get( 'view' ) );
define( 'CM_MIN_WP_VERSION', '3.7' );
define( 'CM_SUPPORTED_WP_VERSION', version_compare( get_bloginfo( 'version' ), CM_MIN_WP_VERSION, '>=' ) );

require_once( plugin_dir_path( __FILE__ ) . 'common.php' );
require_once( plugin_dir_path( __FILE__ ) . 'forms_model.php' );
require_once( plugin_dir_path( __FILE__ ) . 'widget.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/webapi/webapi.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/fields/class-gf-fields.php' );

// CMCommon::$version is deprecated, set it to current version for backwards compat
CMCommon::$version = CM_Class::$version;

add_action( 'init', array( 'CMClass', 'init' ) );
add_action( 'wp', array( 'CMClass', 'maybe_process_form' ), 9 );
add_action( 'admin_init', array( 'CMClass', 'maybe_process_form' ), 9 );
add_action( 'wp', array( 'CMClass', 'process_exterior_pages' ) );
add_filter( 'upgrader_pre_install', array( 'CM_Class', 'validate_upgrade' ), 10, 2 );
add_filter( 'tiny_mce_before_init',  array( 'CM_Class', 'modify_tiny_mce_4' ), 20 );

add_filter( 'user_has_cap', array( 'CMClass', 'user_has_cap' ), 10, 3 );

//Hooks for no-conflict functionality
if ( is_admin() && ( CMClass::is_gravity_page() || CMClass::is_gravity_ajax_action() ) ) {
	add_action( 'wp_print_scripts', array( 'CMClass', 'no_conflict_mode_script' ), 1000 );
	add_action( 'admin_print_footer_scripts', array( 'CMClass', 'no_conflict_mode_script' ), 9 );

	add_action( 'wp_print_styles', array( 'CMClass', 'no_conflict_mode_style' ), 1000 );
	add_action( 'admin_print_styles', array( 'CMClass', 'no_conflict_mode_style' ), 1 );
	add_action( 'admin_print_footer_scripts', array( 'CMClass', 'no_conflict_mode_style' ), 1 );
	add_action( 'admin_footer', array( 'CMClass', 'no_conflict_mode_style' ), 1 );
}

add_action( 'plugins_loaded', array( 'CM_Class', 'loaded' ) );

class CM_Class {

	public static $version = '1.9.9';

	public static function loaded() {

		do_action( 'cmemb_loaded' );

		//initializing Add-Ons if necessary
		if ( class_exists( 'CMAddOn' ) ) {
			CMAddOn::init_addons();
		}
	}

	public static function has_members_plugin() {
		return function_exists( 'members_get_capabilities' );
	}

	//Plugin starting point. Will load appropriate files
	public static function init() {

		// Initializing translations. Translation files in the WP_LANG_DIR folder have a higher priority.
		$locale = apply_filters( 'plugin_locale', get_locale(), 'clubmembership' );
		load_textdomain( 'clubmembership', WP_LANG_DIR . '/clubmembership/clubmembership-' . $locale . '.mo' );
		load_plugin_textdomain( 'clubmembership', false, '/clubmembership/languages' );

		add_filter( 'cmemb_logging_supported', array( 'CMClass', 'set_logging_supported' ) );
		add_action( 'admin_head', array( 'CMCommon', 'maybe_output_cm_vars' ) );

		self::register_scripts();

		//Maybe set up Club Membership: only on admin requests for single site installation and always for multisite
		if ( ( IS_ADMIN && false === ( defined( 'DOING_AJAX' ) && true === DOING_AJAX ) ) || is_multisite() ) {
			self::setup();
		}

		//Plugin update actions
		add_filter( 'transient_update_plugins', array( 'CM_Class', 'check_update' ) );
		add_filter( 'site_transient_update_plugins', array( 'CM_Class', 'check_update' ) );

		add_filter( 'auto_update_plugin', array( 'CM_Class', 'maybe_auto_update' ), 10, 2 );


		if ( IS_ADMIN ) {

			global $current_user;

			//Members plugin integration. Adding Club Membership roles to the checkbox list
			if ( self::has_members_plugin() ) {
				add_filter( 'members_get_capabilities', array( 'CMClass', 'members_get_capabilities' ) );
			}

			if ( is_multisite() ) {
				add_filter( 'wpmu_drop_tables', array( 'CM_ClassModel', 'mu_drop_tables' ) );
			}

			add_action( 'admin_enqueue_scripts', array( 'CM_Class', 'enqueue_admin_scripts' ) );
			add_action( 'print_media_templates', array( 'CM_Class', 'action_print_media_templates' ) );

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				add_action( 'admin_footer', array( 'CM_Class', 'deprecate_add_on_methods' ) );
			}

			//Loading Club Membership if user has access to any functionality
			if ( CMCommon::current_user_can_any( CMCommon::all_caps() ) ) {
				require_once( CMCommon::get_base_path() . '/export.php' );
				GFExport::maybe_export();

				//imports theme forms if configured to be automatic imported
				self::maybe_import_theme_forms();

				//creates the "Forms" left menu
				add_action( 'admin_menu', array( 'CMClass', 'create_menu' ) );

				if ( CM_SUPPORTED_WP_VERSION ) {

					add_action( 'admin_footer', array( 'CMClass', 'check_upload_folder' ) );
					add_action( 'wp_dashboard_setup', array( 'CMClass', 'dashboard_setup' ) );

					// Support modifying the admin page title for settings
					add_filter( 'admin_title', array( __class__, 'modify_admin_title' ), 10, 2 );

					//Adding "embed form" button
					add_action( 'media_buttons', array( 'CMClass', 'add_form_button' ), 20 );

					require_once( CMCommon::get_base_path() . '/includes/locking/locking.php' );

					if ( self::page_supports_add_form_button() ) {
						add_action( 'admin_footer', array( 'CMClass', 'add_mce_popup' ) );
					}

					if ( self::is_gravity_page() ) {
						require_once( CMCommon::get_base_path() . '/tooltips.php' );
					} else if ( CM_CURRENT_PAGE == 'media-upload.php' ) {
						require_once( CMCommon::get_base_path() . '/entry_list.php' );
					} else if ( in_array( CM_CURRENT_PAGE, array( 'admin.php', 'admin-ajax.php' ) ) ) {

						add_action( 'wp_ajax_cm_save_form', array( 'CMClass', 'save_form' ) );
						add_action( 'wp_ajax_cm_change_input_type', array( 'CMClass', 'change_input_type' ) );
						add_action( 'wp_ajax_cm_refresh_field_preview', array( 'CMClass', 'refresh_field_preview' ) );
						add_action( 'wp_ajax_cm_add_field', array( 'CMClass', 'add_field' ) );
						add_action( 'wp_ajax_cm_duplicate_field', array( 'CMClass', 'duplicate_field' ) );
						add_action( 'wp_ajax_cm_delete_field', array( 'CMClass', 'delete_field' ) );
						add_action( 'wp_ajax_cm_delete_file', array( 'CMClass', 'delete_file' ) );
						add_action( 'wp_ajax_cm_select_export_form', array( 'CMClass', 'select_export_form' ) );
						add_action( 'wp_ajax_cm_start_export', array( 'CMClass', 'start_export' ) );
						add_action( 'wp_ajax_cm_upgrade_license', array( 'CMClass', 'upgrade_license' ) );
						add_action( 'wp_ajax_cm_delete_custom_choice', array( 'CMClass', 'delete_custom_choice' ) );
						add_action( 'wp_ajax_cm_save_custom_choice', array( 'CMClass', 'save_custom_choice' ) );
						add_action( 'wp_ajax_cm_get_post_categories', array( 'CMClass', 'get_post_category_values' ) );
						add_action( 'wp_ajax_cm_get_notification_post_categories', array( 'CMClass', 'get_notification_post_category_values' ) );
						add_action( 'wp_ajax_cm_save_confirmation', array( 'CMClass', 'save_confirmation' ) );
						add_action( 'wp_ajax_cm_delete_confirmation', array( 'CMClass', 'delete_confirmation' ) );
						add_action( 'wp_ajax_cm_save_new_form', array( 'CMClass', 'save_new_form' ) );

						//entry list ajax operations
						add_action( 'wp_ajax_cm_update_lead_property', array( 'CMClass', 'update_lead_property' ) );
						add_action( 'wp_ajax_delete-cm_entry', array( 'CMClass', 'update_lead_status' ) );

						//form list ajax operations
						add_action( 'wp_ajax_cm_update_form_active', array( 'CMClass', 'update_form_active' ) );

						//notification list ajax operations
						add_action( 'wp_ajax_cm_update_notification_active', array( 'CMClass', 'update_notification_active' ) );

						//confirmation list ajax operations
						add_action( 'wp_ajax_cm_update_confirmation_active', array( 'CMClass', 'update_confirmation_active' ) );

						//dynamic captcha image
						add_action( 'wp_ajax_cm_captcha_image', array( 'CMClass', 'captcha_image' ) );

						//dashboard message "dismiss upgrade" link
						add_action( 'wp_ajax_cm_dismiss_upgrade', array( 'CMClass', 'dashboard_dismiss_upgrade' ) );

						// entry detail: resend notifications
						add_action( 'wp_ajax_cm_resend_notifications', array( 'CMClass', 'resend_notifications' ) );

						// Shortocde UI
						add_action( 'wp_ajax_cm_do_shortcode',  array( 'CM_Class', 'handle_ajax_do_shortcode' ) );
					}



					add_filter( 'plugins_api', array( 'CMClass', 'get_addon_info' ), 100, 3 );
					add_action( 'after_plugin_row_clubmembership/clubmembership.php', array( 'CMClass', 'plugin_row' ) );
					add_action( 'install_plugins_pre_plugin-information', array( 'CMClass', 'display_changelog' ) );
					add_filter( 'plugin_action_links', array( 'CMClass', 'plugin_settings_link' ), 10, 2 );
				}
			}
			add_action( 'admin_init', array( 'CMClass', 'ajax_parse_request' ), 10 );
		} else {
			add_action( 'wp_enqueue_scripts', array( 'CMClass', 'enqueue_scripts' ), 11 );
			add_action( 'wp', array( 'CMClass', 'ajax_parse_request' ), 10 );
		}

		// Add "Form" to the "New" menu in WP admin bar
		add_action( 'wp_before_admin_bar_render', array( 'CM_Class', 'admin_bar' ) );

		add_shortcode( 'clubmembership', array( 'CMClass', 'parse_shortcode' ) );
		add_shortcode( 'clubmembership', array( 'CMClass', 'parse_shortcode' ) );

		// Push Club Membership to the top of the list of plugins to make sure it's loaded before any add-ons
		add_action( 'activated_plugin', array( 'CM_Class', 'load_first' ) );
	}

	public static function load_first() {
		$plugin_path    = basename( dirname( __FILE__ ) ) . '/clubmembership.php';
		$active_plugins = get_option( 'active_plugins' );
		$key            = array_search( $plugin_path, $active_plugins );
		if ( $key > 0 ) {
			array_splice( $active_plugins, $key, 1 );
			array_unshift( $active_plugins, $plugin_path );
			update_option( 'active_plugins', $active_plugins );
		}
	}

	public static function set_logging_supported( $plugins ) {
		$plugins['clubmembership'] = 'Club Membership Core';

		return $plugins;
	}

	public static function maybe_process_form() {

		$form_id = isset( $_POST['cmemb_submit'] ) ? absint( $_POST['cmemb_submit'] ) : 0;
		if ( $form_id ) {
			$form_info     = CMClassModel::get_form( $form_id );
			$is_valid_form = $form_info && $form_info->is_active;

			if ( $is_valid_form ) {
				require_once( CMCommon::get_base_path() . '/form_display.php' );
				GFFormDisplay::process_form( $form_id );
			}
		} elseif ( isset( $_POST['cmemb_send_resume_link'] ) ) {
			require_once( CMCommon::get_base_path() . '/form_display.php' );
			GFFormDisplay::process_send_resume_link();
		}
	}

	public static function process_exterior_pages() {
		if ( rgempty( 'cm_page', $_GET ) ) {
			return;
		}

		$page = rgget( 'cm_page' );

		$is_legacy_upload_page = $_SERVER['REQUEST_METHOD'] == 'POST' && $page == 'upload';

		if ( $is_legacy_upload_page && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
			_doing_it_wrong( 'cm_page=upload', 'cm_page=upload is now deprecated. Use CMCommon::get_upload_page_slug() instead', '1.9.6.13' );
		}

		$is_upload_page = $_SERVER['REQUEST_METHOD'] == 'POST' && $page == CMCommon::get_upload_page_slug();

		if ( $is_upload_page || $is_legacy_upload_page ) {
			require_once( CMCommon::get_base_path() . '/includes/upload.php' );
			exit();
		}

		//ensure users are logged in
		if ( ! is_user_logged_in() ) {
			auth_redirect();
		}

		switch ( $page ) {
			case 'preview':
				require_once( CMCommon::get_base_path() . '/preview.php' );
				break;

			case 'print-entry' :
				require_once( CMCommon::get_base_path() . '/print-entry.php' );
				break;

			case 'select_columns' :
				require_once( CMCommon::get_base_path() . '/select_columns.php' );
				break;
		}
		exit();
	}

	public static function check_update( $update_plugins_option ) {
		if ( ! class_exists( 'CMCommon' ) ) {
			require_once( 'common.php' );
		}

		return CMCommon::check_update( $update_plugins_option, true );
	}

	//Creates or updates database tables. Will only run when version changes
	public static function setup( $force_setup = false ) {

		$current_version = get_option( 'cm_form_version' );

		if ( $current_version === false ){
			// Turn background updates on by default for all new installations.
			update_option( 'cmemb_enable_background_updates', true );
		}

		$has_version_changed = $current_version != CMCommon::$version;
		if ( $has_version_changed ) {
			//Making sure version has really changed. Gets around aggressive caching issue on some sites that cause setup to run multiple times.
			$has_version_changed = self::get_wp_option( 'cm_form_version' ) != CMCommon::$version;
		}

		if ( $has_version_changed || $force_setup ) {

			$blog_id = get_current_blog_id();

			CMCommon::log_debug( "CM_Class::setup(): Blog {$blog_id} - Beginning of setup. From version " . get_option( 'cm_form_version' ) . ' to version ' . CMCommon::$version );

			//setting up database structure
			self::setup_database();

			//auto-setting and auto-validating license key based on value configured via the CM_LICENSE_KEY constant or the cm_license_key variable
			//auto-populating reCAPTCHA keys base on constant
			self::maybe_populate_keys();

			//Auto-importing forms based on CM_IMPORT_FILE AND CM_THEME_IMPORT_FILE
			self::maybe_import_forms();

			self::add_security_files();

			self::do_self_healing();

			//The format the version info changed to JSON. Make sure the old format is not cached.
			if ( version_compare( get_option( 'cm_form_version' ), '1.8.0.3', '<' ) ) {
				delete_transient( 'cmemb_update_info' );
			}

			update_option( 'cm_form_version', CMCommon::$version );

			CMCommon::log_debug( "CM_Class::setup(): Blog {$blog_id} - End of setup." );

		}
	}


	public static function setup_database() {
		global $wpdb;

		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );

		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}

		//Fixes issue with dbDelta lower-casing table names, which cause problems on case sensitive DB servers.
		add_filter( 'dbdelta_create_queries', array( 'CMClass', 'dbdelta_fix_case' ) );

		/*
		 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
		 * As of 4.2, however, WP core moved to utf8mb4, which uses 4 bytes per character. This means that an index which
		 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
		 */
		$max_index_length = 191;

		//------ FORM -----------------------------------------------
		$form_table_name = CMClassModel::get_form_table_name();
		$sql             = 'CREATE TABLE ' . $form_table_name . " (
              id mediumint(8) unsigned not null auto_increment,
              title varchar(150) not null,
              date_created datetime not null,
              is_active tinyint(1) not null default 1,
              is_trash tinyint(1) not null default 0,
              PRIMARY KEY  (id)
            ) $charset_collate;";
		dbDelta( $sql );

		//droping table that was created by mistake in version 1.6.3.2
		$wpdb->query( 'DROP TABLE IF EXISTS A' . $form_table_name );

		//------ META -----------------------------------------------
		$meta_table_name = CMClassModel::get_meta_table_name();
		$sql             = 'CREATE TABLE ' . $meta_table_name . " (
              form_id mediumint(8) unsigned not null,
              display_meta longtext,
              entries_grid_meta longtext,
              confirmations longtext,
              notifications longtext,
              PRIMARY KEY  (form_id)
            ) $charset_collate;";
		dbDelta( $sql );

		//droping outdated form_id index (if one exists)
		self::drop_index( $meta_table_name, 'form_id' );

		//------ FORM VIEW -----------------------------------------------
		$form_view_table_name = CMClassModel::get_form_view_table_name();
		$sql                  = 'CREATE TABLE ' . $form_view_table_name . " (
              id bigint(20) unsigned not null auto_increment,
              form_id mediumint(8) unsigned not null,
              date_created datetime not null,
              ip char(15),
              count mediumint(8) unsigned not null default 1,
              PRIMARY KEY  (id),
              KEY form_id (form_id)
            ) $charset_collate;";
		dbDelta( $sql );

		//------ LEAD -----------------------------------------------
		$lead_table_name = CMClassModel::get_lead_table_name();
		$sql             = 'CREATE TABLE ' . $lead_table_name . " (
              id int(10) unsigned not null auto_increment,
              form_id mediumint(8) unsigned not null,
              post_id bigint(20) unsigned,
              date_created datetime not null,
              is_starred tinyint(1) not null default 0,
              is_read tinyint(1) not null default 0,
              ip varchar(39) not null,
              source_url varchar(200) not null default '',
              user_agent varchar(250) not null default '',
              currency varchar(5),
              payment_status varchar(15),
              payment_date datetime,
              payment_amount decimal(19,2),
              payment_method varchar(30),
              transaction_id varchar(50),
              is_fulfilled tinyint(1),
              created_by bigint(20) unsigned,
              transaction_type tinyint(1),
              status varchar(20) not null default 'active',
              PRIMARY KEY  (id),
              KEY form_id (form_id),
              KEY status (status)
            ) $charset_collate;";
		dbDelta( $sql );

		//------ LEAD NOTES ------------------------------------------
		$lead_notes_table_name = CMClassModel::get_lead_notes_table_name();
		$sql                   = 'CREATE TABLE ' . $lead_notes_table_name . " (
              id int(10) unsigned not null auto_increment,
              lead_id int(10) unsigned not null,
              user_name varchar(250),
              user_id bigint(20),
              date_created datetime not null,
              value longtext,
              note_type varchar(50),
              PRIMARY KEY  (id),
              KEY lead_id (lead_id),
              KEY lead_user_key (lead_id,user_id)
            ) $charset_collate;";
		dbDelta( $sql );

		//------ LEAD DETAIL -----------------------------------------
		$lead_detail_table_name = CMClassModel::get_lead_details_table_name();
		$sql                    = 'CREATE TABLE ' . $lead_detail_table_name . ' (
              id bigint(20) unsigned not null auto_increment,
              lead_id int(10) unsigned not null,
              form_id mediumint(8) unsigned not null,
              field_number float not null,
              value varchar(' . GFORMS_MAX_FIELD_LENGTH . "),
              PRIMARY KEY  (id),
              KEY form_id (form_id),
              KEY lead_id (lead_id),
              KEY lead_field_number (lead_id,field_number)
            ) $charset_collate;";
		dbDelta( $sql );

		//------ LEAD DETAIL LONG -----------------------------------
		$lead_detail_long_table_name = CMClassModel::get_lead_details_long_table_name();

		$sql = 'CREATE TABLE ' . $lead_detail_long_table_name . " (
              lead_detail_id bigint(20) unsigned not null,
              value longtext,
              PRIMARY KEY  (lead_detail_id)
            ) $charset_collate;";
		dbDelta( $sql );

		// dropping outdated form_id index (if one exists)
		self::drop_index( $lead_detail_long_table_name, 'lead_detail_key' );

		//------ LEAD META ------------------------------------------
		$lead_meta_table_name = CMClassModel::get_lead_meta_table_name();

		// dropping meta_key and form_id_meta_key (if they exist) to prevent duplicate keys error on upgrade
		if ( version_compare( get_option( 'cm_form_version' ), '1.9.8.12', '<' ) ) {
			self::drop_index( $lead_meta_table_name, 'meta_key' );
			self::drop_index( $lead_meta_table_name, 'form_id_meta_key' );
		}

		$sql                  = 'CREATE TABLE ' . $lead_meta_table_name . " (
              id bigint(20) unsigned not null auto_increment,
              form_id mediumint(8) unsigned not null default 0,
              lead_id bigint(20) unsigned not null,
              meta_key varchar(255),
              meta_value longtext,
              PRIMARY KEY  (id),
              KEY meta_key (meta_key($max_index_length)),
              KEY lead_id (lead_id),
              KEY form_id_meta_key (form_id,meta_key($max_index_length))
            ) $charset_collate;";
		dbDelta( $sql );

		//------ INCOMPLETE SUBMISSIONS -------------------------------
		$incomplete_submissions_table_name = CMClassModel::get_incomplete_submissions_table_name();
		$sql                               = 'CREATE TABLE ' . $incomplete_submissions_table_name . " (
              uuid char(32) not null,
              email varchar(255),
              form_id mediumint(8) unsigned not null,
              date_created datetime not null,
              ip varchar(39) not null,
              source_url longtext not null,
              submission longtext not null,
              PRIMARY KEY  (uuid),
              KEY form_id (form_id)
            ) $charset_collate;";
		dbDelta( $sql );

		remove_filter( 'dbdelta_create_queries', array( 'CMClass', 'dbdelta_fix_case' ) );

		//fix form_id value needed to update from version 1.6.11
		self::fix_lead_meta_form_id_values();

		//fix checkbox value. needed for version 1.0 and below but won't hurt for higher versions
		self::fix_checkbox_value();

		//fix leading and trailing spaces in Form objects and entry values
		if ( version_compare( get_option( 'cm_form_version' ), '1.8.3.1', '<' ) ) {
			self::fix_leading_and_trailing_spaces();
		}

	}

	public static function add_security_files() {
		$upload_root = CM_ClassModel::get_upload_root();

		if ( ! is_dir( $upload_root ) ) {
			return;
		}

		CMCommon::recursive_add_index_file( $upload_root );

		CMCommon::add_htaccess_file();
	}

	private static function do_self_healing() {

		$flag_security_alert = self::heal_wp_upload_dir();

		$cm_upload_root = CM_ClassModel::get_upload_root();

		if ( ! is_dir( $cm_upload_root ) ) {
			return;
		}

		$flag_security_alert = self::rename_suspicious_files_recursive( $cm_upload_root, $flag_security_alert );
		if ( $flag_security_alert ) {
			update_option( 'cmemb_security_alert', $flag_security_alert );
		}
	}

	/**
	 * Renames files with a .bak extension if they have a file extension that is not allowed in the Club Membership uploads folder.
	 */
	private static function rename_suspicious_files_recursive( $dir, $flag_security_alert = false ) {
		if ( ! is_dir( $dir ) || is_link( $dir ) ) {
			return;
		}

		if ( ! ( $dir_handle = opendir( $dir ) ) ) {
			return;
		}

		// ignores all errors
		set_error_handler( create_function( '', 'return 0;' ), E_ALL );

		while ( false !== ( $file = readdir( $dir_handle ) ) ) {
			if ( is_dir( $dir . DIRECTORY_SEPARATOR . $file ) && $file != '.' && $file != '..' ) {
				$flag_security_alert = self::rename_suspicious_files_recursive( $dir . DIRECTORY_SEPARATOR . $file, $flag_security_alert );
			} elseif ( CMCommon::file_name_has_disallowed_extension( $file )
			           && ! CMCommon::match_file_extension( $file, array( 'htaccess', 'bak', 'html' ) ) ) {
				$mini_hash = substr( wp_hash( $file ), 0, 6 );
				$newName   = sprintf( '%s/%s.%s.bak', $dir, $file, $mini_hash );
				rename( $dir . '/' . $file, $newName );
				$flag_security_alert = true;
			}
		}

		closedir( $dir_handle );

		return $flag_security_alert;
	}

	private static function heal_wp_upload_dir(){
		$wp_upload_dir = wp_upload_dir();

		$wp_upload_path = $wp_upload_dir['basedir'];

		if ( ! is_dir( $wp_upload_path ) ) {
			return;
		}

		$flag_security_alert = false;

		// ignores all errors
		set_error_handler( create_function( '', 'return 0;' ), E_ALL );

		foreach ( glob( $wp_upload_path . DIRECTORY_SEPARATOR . '*_input_*.{php,php5}', GLOB_BRACE ) as $filename ) {
			$mini_hash = substr( wp_hash( $filename ), 0, 6 );
			$newName   = sprintf( '%s.%s.bak', $filename, $mini_hash );
			rename( $filename, $newName );
			$flag_security_alert = true;
		}

		return $flag_security_alert;
	}

	private static function fix_leading_and_trailing_spaces() {

		global $wpdb;

		$meta_table_name         = CM_ClassModel::get_meta_table_name();
		$lead_details_table      = CM_ClassModel::get_lead_details_table_name();
		$lead_details_long_table = CM_ClassModel::get_lead_details_long_table_name();

		$result = $wpdb->query( "UPDATE $lead_details_table SET value = TRIM(value)" );
		$result = $wpdb->query( "UPDATE $lead_details_long_table SET value = TRIM(value)" );


		$results = $wpdb->get_results( "SELECT form_id, display_meta, confirmations, notifications FROM {$meta_table_name}", ARRAY_A );

		foreach ( $results as &$result ) {
			$form_id = $result['form_id'];

			$form         = CM_ClassModel::unserialize( $result['display_meta'] );
			$form_updated = false;
			$form         = CM_ClassModel::trim_form_meta_values( $form, $form_updated );
			if ( $form_updated ) {
				CM_ClassModel::update_form_meta( $form_id, $form );
			}

			$confirmations         = CM_ClassModel::unserialize( $result['confirmations'] );
			$confirmations_updated = false;
			$confirmations         = CM_ClassModel::trim_conditional_logic_values( $confirmations, $form, $confirmations_updated );
			if ( $confirmations_updated ) {
				CM_ClassModel::update_form_meta( $form_id, $confirmations, 'confirmations' );
			}

			$notifications         = CM_ClassModel::unserialize( $result['notifications'] );
			$notifications_updated = false;
			$notifications         = CM_ClassModel::trim_conditional_logic_values( $notifications, $form, $notifications_updated );
			if ( $notifications_updated ) {
				CM_ClassModel::update_form_meta( $form_id, $notifications, 'notifications' );
			}
		}

		return $results;
	}

	public static function get_wp_option( $option_name ) {
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare( "SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=%s", $option_name ) );
	}

	//Changes form_id values from default value "0" to the correct value. Needed when upgrading users from 1.6.11
	private static function fix_lead_meta_form_id_values() {
		global $wpdb;

		$lead_meta_table_name = CMClassModel::get_lead_meta_table_name();
		$lead_table_name      = CMClassModel::get_lead_table_name();

		$sql = "UPDATE $lead_meta_table_name lm,$lead_table_name l SET lm.form_id = l.form_id
				WHERE lm.form_id=0 AND lm.lead_id = l.id;
				";
		$wpdb->get_results( $sql );

	}

	public static function dbdelta_fix_case( $cqueries ) {
		foreach ( $cqueries as $table => $qry ) {
			$table_name = $table;
			if ( preg_match( "|CREATE TABLE ([^ ]*)|", $qry, $matches ) ) {
				$query_table_name = trim( $matches[1], '`' );

				//fix table names that are different just by their casing
				if ( strtolower( $query_table_name ) == $table ) {
					$table_name = $query_table_name;
				}
			}
			$queries[ $table_name ] = $qry;
		}

		return $queries;
	}

	public static function no_conflict_mode_style() {
		if ( ! get_option( 'cmemb_enable_noconflict' ) ) {
			return;
		}

		global $wp_styles;
		$wp_required_styles = array( 'admin-bar', 'colors', 'ie', 'wp-admin', 'editor-style' );
		$cm_required_styles = array(
			'common'                     => array(),
			'cm_edit_forms'              => array( 'thickbox', 'editor-buttons', 'wp-jquery-ui-dialog', 'media-views', 'buttons', 'wp-pointer' ),
			'cm_edit_forms_notification' => array( 'thickbox', 'editor-buttons', 'wp-jquery-ui-dialog', 'media-views', 'buttons' ),
			'cm_new_form'                => array( 'thickbox' ),
			'cm_entries'                 => array( 'thickbox' ),
			'cm_settings'                => array(),
			'cm_export'                  => array(),
			'cm_help'                    => array()
		);

		self::no_conflict_mode( $wp_styles, $wp_required_styles, $cm_required_styles, 'styles' );
	}


	public static function no_conflict_mode_script() {
		if ( ! get_option( 'cmemb_enable_noconflict' ) ) {
			return;
		}

		global $wp_scripts;

		$wp_required_scripts = array( 'admin-bar', 'common', 'jquery-color', 'utils', 'svg-painter' );
		$cm_required_scripts = array(
			'common'                     => array( 'cmemb_tooltip_init', 'sack' ),
			'cm_edit_forms'              => array( 'backbone', 'editor', 'cmemb_floatmenu', 'cmemb_forms', 'cmemb_form_admin', 'cmemb_form_editor', 'cmemb_clubmembership', 'cmemb_json', 'cmemb_menu', 'cmemb_placeholder', 'jquery-ui-autocomplete', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-tabs', 'json2', 'media-editor', 'media-models', 'media-upload', 'media-views', 'plupload', 'plupload-flash', 'plupload-html4', 'plupload-html5', 'quicktags', 'cm_currency', 'thickbox', 'word-count', 'wp-plupload', 'wpdialogs-popup', 'wplink', 'wp-pointer' ),
			'cm_edit_forms_notification' => array( 'editor', 'word-count', 'quicktags', 'wpdialogs-popup', 'media-upload', 'wplink', 'backbone', 'jquery-ui-sortable', 'json2', 'media-editor', 'media-models', 'media-views', 'plupload', 'plupload-flash', 'plupload-html4', 'plupload-html5', 'plupload-silverlight', 'wp-plupload', 'cmemb_placeholder', 'cmemb_json', 'jquery-ui-autocomplete' ),
			'cm_new_form'                => array( 'thickbox', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-tabs', 'cm_currency', 'cmemb_clubmembership', 'cmemb_json', 'cmemb_form_admin' ),
			'cm_entries'                 => array( 'thickbox', 'cmemb_clubmembership', 'wp-lists', 'cmemb_json', 'cmemb_field_filter', 'plupload-all' ),
			'cm_settings'                => array(),
			'cm_export'                  => array( 'cmemb_form_admin', 'jquery-ui-datepicker', 'cmemb_field_filter' ),
			'cm_help'                    => array(),
		);

		self::no_conflict_mode( $wp_scripts, $wp_required_scripts, $cm_required_scripts, 'scripts' );
	}

	private static function no_conflict_mode( &$wp_objects, $wp_required_objects, $cm_required_objects, $type = 'scripts' ) {

		$current_page = trim( strtolower( rgget( 'page' ) ) );
		if ( empty( $current_page ) ) {
			$current_page = trim( strtolower( rgget( 'cm_page' ) ) );
		}
		if ( empty( $current_page ) ) {
			$current_page = CM_CURRENT_PAGE;
		}

		$view         = rgempty( 'view', $_GET ) ? 'default' : rgget( 'view' );
		$page_objects = isset( $cm_required_objects[ $current_page . '_' . $view ] ) ? $cm_required_objects[ $current_page . '_' . $view ] : rgar( $cm_required_objects, $current_page );

		//disable no-conflict if $page_objects is false
		if ( $page_objects === false ) {
			return;
		}

		if ( ! is_array( $page_objects ) ) {
			$page_objects = array();
		}

		//merging wp scripts with gravity forms scripts
		$required_objects = array_merge( $wp_required_objects, $cm_required_objects['common'], $page_objects );

		//allowing addons or other products to change the list of no conflict scripts
		$required_objects = apply_filters( "cmemb_noconflict_{$type}", $required_objects );

		$queue = array();
		foreach ( $wp_objects->queue as $object ) {
			if ( in_array( $object, $required_objects ) ) {
				$queue[] = $object;
			}
		}
		$wp_objects->queue = $queue;

		$required_objects = self::add_script_dependencies( $wp_objects->registered, $required_objects );

		//unregistering scripts
		$registered = array();
		foreach ( $wp_objects->registered as $script_name => $script_registration ) {
			if ( in_array( $script_name, $required_objects ) ) {
				$registered[ $script_name ] = $script_registration;
			}
		}
		$wp_objects->registered = $registered;
	}

	private static function add_script_dependencies( $registered, $scripts ) {

		//gets all dependent scripts linked to the $scripts array passed
		do {
			$dependents = array();
			foreach ( $scripts as $script ) {
				$deps = isset( $registered[ $script ] ) && is_array( $registered[ $script ]->deps ) ? $registered[ $script ]->deps : array();
				foreach ( $deps as $dep ) {
					if ( ! in_array( $dep, $scripts ) && ! in_array( $dep, $dependents ) ) {
						$dependents[] = $dep;
					}
				}
			}
			$scripts = array_merge( $scripts, $dependents );
		} while ( ! empty( $dependents ) );

		return $scripts;
	}

	
	public static function drop_index( $table, $index ) {
		global $wpdb;

		if ( ! CM_ClassModel::is_valid_table( $table ) || ! CM_ClassModel::is_valid_index( $index ) ) {
			return;
		}

		// check first if the table exists to prevent errors on first install
		$has_table = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
		if ( $has_table ) {

			$has_index = $wpdb->get_var( $wpdb->prepare( "SHOW INDEX FROM {$table} WHERE Key_name=%s", $index ) );

			if ( $has_index ) {
				$wpdb->query( "DROP INDEX {$index} ON {$table}" );
			}
		}


	}

	public static function validate_upgrade( $do_upgrade, $hook_extra ) {

		if ( rgar( $hook_extra, 'plugin' ) == 'clubmembership/clubmembership.php' && ! CM_Class::has_database_permission( $error ) ) {
			return new WP_Error( 'no_db_permission', $error );
		}

		return true;
	}

	private static function has_database_permission( &$error ) {
		global $wpdb;

		$wpdb->hide_errors();

		$has_permission = true;

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cm_test ( col1 int )";
		$wpdb->query( $sql );
		$error = 'Current database user does not have necessary permissions to create tables. Club Membership requires that the database user has CREATE and ALTER permissions. If you need assistance in changing database user permissions, contact your hosting provider.';
		if ( ! empty( $wpdb->last_error ) ) {
			$has_permission = false;
		}

		if ( $has_permission ) {
			$sql = "ALTER TABLE {$wpdb->prefix}cm_test ADD COLUMN a" . uniqid() . ' int';
			$wpdb->query( $sql );
			$error = 'Current database user does not have necessary permissions to modify (ALTER) tables. Club Membership requires that the database user has CREATE and ALTER permissions. If you need assistance in changing database user permissions, contact your hosting provider.';
			if ( ! empty( $wpdb->last_error ) ) {
				$has_permission = false;
			}

			$sql = "DROP TABLE {$wpdb->prefix}cm_test";
			$wpdb->query( $sql );
		}

		$wpdb->show_errors();

		return $has_permission;
	}

	public static function user_has_cap( $all_caps, $cap, $args ) {
		$cm_caps    = CMCommon::all_caps();
		$capability = rgar( $cap, 0 );
		if ( $capability != 'cmemb_full_access' ) {
			return $all_caps;
		}

		if ( ! self::has_members_plugin() ) {
			//give full access to administrators if the members plugin is not installed
			if ( current_user_can( 'administrator' ) || is_super_admin() ) {
				$all_caps['cmemb_full_access'] = true;
			}
		} else if ( current_user_can( 'administrator' ) || is_super_admin() ) {

			//checking if user has any GF permission.
			$has_cm_cap = false;
			foreach ( $cm_caps as $cm_cap ) {
				if ( rgar( $all_caps, $cm_cap ) ) {
					$has_cm_cap = true;
				}
			}

			if ( ! $has_cm_cap ) {
				//give full access to administrators if none of the GF permissions are active by the Members plugin
				$all_caps['cmemb_full_access'] = true;
			}
		}

		return $all_caps;
	}

	//Target of Member plugin filter. Provides the plugin with Club Membership lists of capabilities
	public static function members_get_capabilities( $caps ) {
		return array_merge( $caps, CMCommon::all_caps() );
	}

	//Tests if the upload folder is writable and displays an error message if not
	public static function check_upload_folder() {
		//check if upload folder is writable
		$folder = CMClassModel::get_upload_root();
		if ( empty( $folder ) ) {
			echo "<div class='error'>Upload folder is not writable. Export and file upload features will not be functional.</div>";
		}
	}

	public static function is_gravity_ajax_action() {
		//Club Membership AJAX requests
		$current_action  = self::post( 'action' );
		$cm_ajax_actions = array(
			'cm_save_form', 'cm_change_input_type', 'cm_refresh_field_preview', 'cm_add_field', 'cm_duplicate_field',
			'cm_delete_field', 'cm_select_export_form', 'cm_start_export', 'cm_upgrade_license',
			'cm_delete_custom_choice', 'cm_save_custom_choice', 'cm_get_notification_post_categories',
			'cm_update_lead_property', 'delete-cm_entry', 'cm_update_form_active', 'cm_update_notification_active',
			'cm_update_confirmation_active', 'cm_resend_notifications', 'cm_dismiss_upgrade', 'cm_save_confirmation',
		);

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && in_array( $current_action, $cm_ajax_actions ) ) {
			return true;
		}

		//not a club membership ajax request.
		return false;
	}

	//Returns true if the current page is one of Club Membership pages. Returns false if not
	public static function is_gravity_page() {

		//Club Membership pages
		$current_page = trim( strtolower( self::get( 'page' ) ) );
		$cm_pages     = array( 'cm_edit_forms', 'cm_new_form', 'cm_entries', 'cm_settings', 'cm_export', 'cm_help' );

		return in_array( $current_page, $cm_pages );
	}

	//Creates "Forms" left nav
	public static function create_menu() {

		$has_full_access = current_user_can( 'cmemb_full_access' );
		$min_cap         = CMCommon::current_user_can_which( CMCommon::all_caps() );
		if ( empty( $min_cap ) ) {
			$min_cap = 'cmemb_full_access';
		}

		$addon_menus = array();
		$addon_menus = apply_filters( 'cmemb_addon_navigation', $addon_menus );

		$parent_menu = self::get_parent_menu( $addon_menus );

		// Add a top-level left nav
		$update_icon = CMCommon::has_update() && current_user_can( 'install_plugins' ) ? "<span title='" . esc_attr( __( 'Update Available', 'clubmembership' ) ) . "' class='update-plugins count-1'><span class='update-count'>1</span></span>" : '';

		$admin_icon = self::get_admin_icon_b64( CM_Class::is_gravity_page() ? '#fff' : false );

		add_menu_page( __( 'Forms', 'clubmembership' ), __( 'Forms', 'clubmembership' ) . $update_icon, $has_full_access ? 'cmemb_full_access' : $min_cap, $parent_menu['name'], $parent_menu['callback'], $admin_icon, apply_filters( 'cmemb_menu_position', '16.9' ) );

		// Adding submenu pages
		add_submenu_page( $parent_menu['name'], __( 'Forms', 'clubmembership' ), __( 'Forms', 'clubmembership' ), $has_full_access ? 'cmemb_full_access' : 'clubmembership_edit_forms', 'cm_edit_forms', array( 'CMClass', 'forms' ) );

		add_submenu_page( $parent_menu['name'], __( 'New Form', 'clubmembership' ), __( 'New Form', 'clubmembership' ), $has_full_access ? 'cmemb_full_access' : 'clubmembership_create_form', 'cm_new_form', array( 'CMClass', 'new_form' ) );

		add_submenu_page( $parent_menu['name'], __( 'Entries', 'clubmembership' ), __( 'Entries', 'clubmembership' ), $has_full_access ? 'cmemb_full_access' : 'clubmembership_view_entries', 'cm_entries', array( 'CMClass', 'all_leads_page' ) );

		if ( is_array( $addon_menus ) ) {
			foreach ( $addon_menus as $addon_menu ) {
				add_submenu_page( esc_html( $parent_menu['name'] ), esc_html( $addon_menu['label'] ), esc_html( $addon_menu['label'] ), $has_full_access ? 'cmemb_full_access' : $addon_menu['permission'], esc_html( $addon_menu['name'] ), $addon_menu['callback'] );
			}
		}

		add_submenu_page( $parent_menu['name'], __( 'Settings', 'clubmembership' ), __( 'Settings', 'clubmembership' ), $has_full_access ? 'cmemb_full_access' : 'clubmembership_view_settings', 'cm_settings', array( 'CMClass', 'settings_page' ) );

		add_submenu_page( $parent_menu['name'], __( 'Import/Export', 'clubmembership' ), __( 'Import/Export', 'clubmembership' ), $has_full_access ? 'cmemb_full_access' : 'clubmembership_export_entries', 'cm_export', array( 'CMClass', 'export_page' ) );

		if ( current_user_can( 'install_plugins' ) ) {
			add_submenu_page( $parent_menu['name'], __( 'Updates', 'clubmembership' ), __( 'Updates', 'clubmembership' ), $has_full_access ? 'cmemb_full_access' : 'clubmembership_view_updates', 'cm_update', array( 'CMClass', 'update_page' ) );
			add_submenu_page( $parent_menu['name'], __( 'Add-Ons', 'clubmembership' ), __( 'Add-Ons', 'clubmembership' ), $has_full_access ? 'cmemb_full_access' : 'clubmembership_view_addons', 'cm_addons', array( 'CMClass', 'addons_page' ) );
		}

		add_submenu_page( $parent_menu['name'], __( 'Help', 'clubmembership' ), __( 'Help', 'clubmembership' ), $has_full_access ? 'cmemb_full_access' : $min_cap, 'cm_help', array( 'CMClass', 'help_page' ) );

	}

	public static function get_admin_icon_b64( $color = false ) {

		// replace the hex color (default was #999999) to %s; it will be replaced by the passed $color
		$svg_xml = '<?xml version="1.0" encoding="utf-8"?><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-15 77 581 640" enable-background="new -15 77 581 640" xml:space="preserve"><g id="Layer_2"><path fill="%s" d="M489.5,227L489.5,227L315.9,126.8c-22.1-12.8-58.4-12.8-80.5,0L61.8,227c-22.1,12.8-40.3,44.2-40.3,69.7v200.5c0,25.6,18.1,56.9,40.3,69.7l173.6,100.2c22.1,12.8,58.4,12.8,80.5,0L489.5,567c22.2-12.8,40.3-44.2,40.3-69.7V296.8C529.8,271.2,511.7,239.8,489.5,227z M401,300.4v59.3H241v-59.3H401z M163.3,490.9c-16.4,0-29.6-13.3-29.6-29.6c0-16.4,13.3-29.6,29.6-29.6s29.6,13.3,29.6,29.6C192.9,477.6,179.6,490.9,163.3,490.9z M163.3,359.7c-16.4,0-29.6-13.3-29.6-29.6s13.3-29.6,29.6-29.6s29.6,13.3,29.6,29.6S179.6,359.7,163.3,359.7z M241,490.9v-59.3h160v59.3H241z"/></g></svg>';
		$svg_b64 = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSItMTUgNzcgNTgxIDY0MCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAtMTUgNzcgNTgxIDY0MCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGcgaWQ9IkxheWVyXzIiPjxwYXRoIGZpbGw9IiM5OTk5OTkiIGQ9Ik00ODkuNSwyMjdMNDg5LjUsMjI3TDMxNS45LDEyNi44Yy0yMi4xLTEyLjgtNTguNC0xMi44LTgwLjUsMEw2MS44LDIyN2MtMjIuMSwxMi44LTQwLjMsNDQuMi00MC4zLDY5Ljd2MjAwLjVjMCwyNS42LDE4LjEsNTYuOSw0MC4zLDY5LjdsMTczLjYsMTAwLjJjMjIuMSwxMi44LDU4LjQsMTIuOCw4MC41LDBMNDg5LjUsNTY3YzIyLjItMTIuOCw0MC4zLTQ0LjIsNDAuMy02OS43VjI5Ni44QzUyOS44LDI3MS4yLDUxMS43LDIzOS44LDQ4OS41LDIyN3ogTTQwMSwzMDAuNHY1OS4zSDI0MXYtNTkuM0g0MDF6IE0xNjMuMyw0OTAuOWMtMTYuNCwwLTI5LjYtMTMuMy0yOS42LTI5LjZjMC0xNi40LDEzLjMtMjkuNiwyOS42LTI5LjZzMjkuNiwxMy4zLDI5LjYsMjkuNkMxOTIuOSw0NzcuNiwxNzkuNiw0OTAuOSwxNjMuMyw0OTAuOXogTTE2My4zLDM1OS43Yy0xNi40LDAtMjkuNi0xMy4zLTI5LjYtMjkuNnMxMy4zLTI5LjYsMjkuNi0yOS42czI5LjYsMTMuMywyOS42LDI5LjZTMTc5LjYsMzU5LjcsMTYzLjMsMzU5Ljd6IE0yNDEsNDkwLjl2LTU5LjNoMTYwdjU5LjNIMjQxeiIvPjwvZz48L3N2Zz4=';

		if( $color ) {
			$icon = sprintf( 'data:image/svg+xml;base64,%s', base64_encode( sprintf( $svg_xml, $color ) ) );
		} else {
			$icon = 'data:image/svg+xml;base64,' . $svg_b64;
		}

		return $icon;
	}

	//Returns the parent menu item. It needs to be the same as the first sub-menu (otherwise WP will duplicate the main menu as a sub-menu)
	public static function get_parent_menu( $addon_menus ) {

		if ( CMCommon::current_user_can_any( 'clubmembership_edit_forms' ) ) {
			$parent = array( 'name' => 'cm_edit_forms', 'callback' => array( 'CMClass', 'forms' ) );
		} else if ( CMCommon::current_user_can_any( 'clubmembership_create_form' ) ) {
			$parent = array( 'name' => 'cm_new_form', 'callback' => array( 'CMClass', 'new_form' ) );
		} else if ( CMCommon::current_user_can_any( 'clubmembership_view_entries' ) ) {
			$parent = array( 'name' => 'cm_entries', 'callback' => array( 'CMClass', 'all_leads_page' ) );
		} else if ( is_array( $addon_menus ) && sizeof( $addon_menus ) > 0 ) {
			foreach ( $addon_menus as $addon_menu ) {
				if ( CMCommon::current_user_can_any( $addon_menu['permission'] ) ) {
					$parent = array( 'name' => $addon_menu['name'], 'callback' => $addon_menu['callback'] );
					break;
				}
			}
		} else if ( CMCommon::current_user_can_any( 'clubmembership_view_settings' ) ) {
			$parent = array( 'name' => 'cm_settings', 'callback' => array( 'CMClass', 'settings_page' ) );
		} else if ( CMCommon::current_user_can_any( 'clubmembership_export_entries' ) ) {
			$parent = array( 'name' => 'cm_export', 'callback' => array( 'CMClass', 'export_page' ) );
		} else if ( CMCommon::current_user_can_any( 'clubmembership_view_updates' ) ) {
			$parent = array( 'name' => 'cm_update', 'callback' => array( 'CMClass', 'update_page' ) );
		} else if ( CMCommon::current_user_can_any( 'clubmembership_view_addons' ) ) {
			$parent = array( 'name' => 'cm_addons', 'callback' => array( 'CMClass', 'addons_page' ) );
		} else if ( CMCommon::current_user_can_any( CMCommon::all_caps() ) ) {
			$parent = array( 'name' => 'cm_help', 'callback' => array( 'CMClass', 'help_page' ) );
		}

		return $parent;
	}

	public static function modify_admin_title( $admin_title, $title ) {

		$subview = rgget( 'subview' );
		$form_id = rgget( 'id' );

		if ( ! $form_id || rgget( 'page' ) != 'cm_edit_forms' || rgget( 'view' ) != 'settings' ) {
			return $admin_title;
		}

		require_once( CMCommon::get_base_path() . '/form_settings.php' );

		$setting_tabs = GFFormSettings::get_tabs( $form_id );
		$page_title   = '';

		foreach ( $setting_tabs as $tab ) {
			if ( $tab['name'] == $subview ) {
				$page_title = $tab['label'];
			}
		}

		if ( $page_title ) {
			$admin_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress', 'clubmembership' ), $page_title, $admin_title );
		}

		return $admin_title;
	}

	//Parses the [clubmembership shortcode and returns the front end form UI
	public static function parse_shortcode( $attributes, $content = null ) {

		extract(
			shortcode_atts(
				array(
					'title'        => true,
					'description'  => true,
					'id'           => 0,
					'name'         => '',
					'field_values' => '',
					'ajax'         => false,
					'tabindex'     => 1,
					'action'       => 'form',
				), $attributes, 'clubmembership'
			)
		);

		$shortcode_string = '';

		switch ( $action ) {
			case 'conditional':
				$shortcode_string = CMCommon::conditional_shortcode( $attributes, $content );
				break;

			case 'form' :
				//displaying form
				$title        = strtolower( $title ) == 'false' ? false : true;
				$description  = strtolower( $description ) == 'false' ? false : true;
				$field_values = htmlspecialchars_decode( $field_values );
				$field_values = str_replace( '&#038;', '&', $field_values );

				$ajax = strtolower( $ajax ) == 'true' ? true : false;

				//using name to lookup form if id is not specified
				if ( empty( $id ) ) {
					$id = $name;
				}

				parse_str( $field_values, $field_value_array ); //parsing query string like string for field values and placing them into an associative array
				$field_value_array = stripslashes_deep( $field_value_array );

				$shortcode_string = self::get_form( $id, $title, $description, false, $field_value_array, $ajax, $tabindex );

				break;
		}

		$shortcode_string = apply_filters( "cmemb_shortcode_{$action}", $shortcode_string, $attributes, $content );

		return $shortcode_string;
	}

	public static function include_addon_framework() {
		require_once( CMCommon::get_base_path() . '/includes/addon/class-gf-addon.php' );
	}

	public static function include_feed_addon_framework() {
		require_once( CMCommon::get_base_path() . '/includes/addon/class-gf-feed-addon.php' );
	}

	public static function include_payment_addon_framework() {
		require_once( CMCommon::get_base_path() . '/includes/addon/class-gf-payment-addon.php' );
	}


	//-------------------------------------------------
	//----------- AJAX --------------------------------

	public static function ajax_parse_request( $wp ) {

		if ( isset( $_POST['cmemb_ajax'] ) ) {
			parse_str( $_POST['cmemb_ajax'] );
			$tabindex = isset( $tabindex ) ? absint( $tabindex ) : 1;
			require_once( CMCommon::get_base_path() . '/form_display.php' );

			$result = GFFormDisplay::get_form( $form_id, $title, $description, false, $_POST['cmemb_field_values'], true, $tabindex );
			die( $result );
		}
	}

	//------------------------------------------------------
	//------------- PAGE/POST EDIT PAGE ---------------------

	public static function page_supports_add_form_button() {
		$is_post_edit_page = in_array( CM_CURRENT_PAGE, array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) );

		$display_add_form_button = apply_filters( 'cmemb_display_add_form_button', $is_post_edit_page );

		return $display_add_form_button;
	}

	//Action target that adds the 'Insert Form' button to the post/page edit screen
	public static function add_form_button() {

		$is_add_form_page = self::page_supports_add_form_button();
		if ( ! $is_add_form_page ) {
			return;
		}

		// do a version check for the new 3.5 UI
		$version = get_bloginfo( 'version' );

		if ( $version < 3.5 ) {
			// show button for v 3.4 and below
			$image_btn = CMCommon::get_base_url() . '/images/form-button.png';
			echo '<a href="#TB_inline?width=480&inlineId=select_gravity_form" class="thickbox" id="add_gform" title="' . __( 'Add Gravity Form', 'clubmembership' ) . '"><img src="' . $image_btn . '" alt="' . __( 'Add Gravity Form', 'clubmembership' ) . '" /></a>';
		} else {
			// display button matching new UI
			echo '<style>.cmemb_media_icon{
                    background-position: center center;
				    background-repeat: no-repeat;
				    background-size: 16px auto;
				    float: left;
				    height: 16px;
				    margin: 0;
				    text-align: center;
				    width: 16px;
					padding-top:10px;
                    }
                    .cmemb_media_icon:before{
                    color: #999;
				    padding: 7px 0;
				    transition: all 0.1s ease-in-out 0s;
                    }
                    .wp-core-ui a.cmemb_media_link{
                     padding-left: 0.4em;
                    }
                 </style>
                  <a href="#" class="button cmemb_media_link" id="add_gform" title="' . __( 'Add Gravity Form', 'clubmembership' ) . '"><div class="cmemb_media_icon svg" style="background-image: url(\'' . self::get_admin_icon_b64()  . '\')"><br /></div><div style="padding-left: 20px;">' . __( 'Add Form', 'clubmembership' ) . '</div></a>';
		}
	}


	//Action target that displays the popup to insert a form to a post/page
	public static function add_mce_popup() {
		?>
		<script>
			function InsertForm() {
				var form_id = jQuery("#add_form_id").val();
				if (form_id == "") {
					alert("<?php _e( 'Please select a form', 'clubmembership' ) ?>");
					return;
				}

				var form_name = jQuery("#add_form_id option[value='" + form_id + "']").text().replace(/[\[\]]/g, '');
				var display_title = jQuery("#display_title").is(":checked");
				var display_description = jQuery("#display_description").is(":checked");
				var ajax = jQuery("#cmemb_ajax").is(":checked");
				var title_qs = !display_title ? " title=\"false\"" : "";
				var description_qs = !display_description ? " description=\"false\"" : "";
				var ajax_qs = ajax ? " ajax=\"true\"" : "";

				window.send_to_editor("[clubmembership id=\"" + form_id + "\" name=\"" + form_name + "\"" + title_qs + description_qs + ajax_qs + "]");
			}
		</script>

		<div id="select_gravity_form" style="display:none;">

			<div id="gform-shortcode-ui-wrap" class="wrap <?php echo CMCommon::get_browser_class() ?>">

				<div id="gform-shortcode-ui-container"></div>

			</div>


		</div>

		<?php
	}


	//------------------------------------------------------
	//------------- PLUGINS PAGE ---------------------------
	//------------------------------------------------------

	public static function plugin_settings_link( $links, $file ) {
		if ( $file != plugin_basename( __FILE__ ) ) {
			return $links;
		}

		array_unshift( $links, '<a href="' . admin_url( 'admin.php' ) . '?page=cm_settings">' . __( 'Settings', 'clubmembership' ) . '</a>' );

		return $links;
	}

	//Displays message on Plugin's page
	public static function plugin_row( $plugin_name ) {
		$key          = CMCommon::get_key();
		$version_info = CMCommon::get_version_info();

		if ( ! rgar( $version_info, 'is_valid_key' ) ) {

			$plugin_name = 'clubmembership/clubmembership.php';

			$new_version = version_compare( CMCommon::$version, $version_info['version'], '<' ) ? __( 'There is a new version of Club Membership available.', 'clubmembership' ) . ' <a class="thickbox" title="Club Membership" href="plugin-install.php?tab=plugin-information&plugin=clubmembership&TB_iframe=true&width=640&height=808">' . sprintf( __( 'View version %s Details', 'clubmembership' ), $version_info['version'] ) . '</a>. ' : '';

			echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message">' . $new_version . __( sprintf( '%sRegister%s your copy of Club Membership to receive access to automatic upgrades and support. Need a license key? %sPurchase one now%s.', '<a href="' . admin_url() . 'admin.php?page=cm_settings">', '</a>', '<a href="http://www.clubmembership.com">', '</a>' ), 'clubmembership' ) . '</div></td>';
		}
	}

	//Displays current version details on Plugin's page
	public static function display_changelog() {
		if ( $_REQUEST['plugin'] != 'clubmembership' ) {
			return;
		}

		$page_text = self::get_changelog();
		echo $page_text;

		exit;
	}

	public static function get_changelog() {
		$key                = CMCommon::get_key();
		$body               = "key=$key";
		$options            = array( 'method' => 'POST', 'timeout' => 3, 'body' => $body );
		$options['headers'] = array(
			'Content-Type'   => 'application/x-www-form-urlencoded; charset=' . get_option( 'blog_charset' ),
			'Content-Length' => strlen( $body ),
			'User-Agent'     => 'WordPress/' . get_bloginfo( 'version' ),
			'Referer'        => get_bloginfo( 'url' )
		);

		$raw_response = CMCommon::post_to_manager( 'changelog.php', CMCommon::get_remote_request_params(), $options );

		if ( is_wp_error( $raw_response ) || 200 != $raw_response['response']['code'] ) {
			$page_text = __( "Oops!! Something went wrong.<br/>Please try again or <a href='http://www.clubmembership.com'>contact us</a>.", 'clubmembership' );
		} else {
			$page_text = $raw_response['body'];
			if ( substr( $page_text, 0, 10 ) != '<!--GFM-->' ) {
				$page_text = '';
			}
			else {
				$page_text = '<div style="background-color:white">' . $page_text . '<div>';
			}
		}

		return stripslashes( $page_text );
	}

	//------------------------------------------------------
	//-------------- DASHBOARD PAGE -------------------------

	//Registers the dashboard widget
	public static function dashboard_setup() {
		$dashboard_title = apply_filters( 'cmemb_dashboard_title', __( 'Forms', 'clubmembership' ) );
		wp_add_dashboard_widget( 'cm_forms_dashboard', $dashboard_title, array( 'CMClass', 'dashboard' ) );
	}

	//Displays the dashboard UI
	public static function dashboard() {
		$forms = CMClassModel::get_form_summary();

		if ( sizeof( $forms ) > 0 ) {
			?>
			<table class="widefat cm_dashboard_view" cellspacing="0" style="border:0px;">
				<thead>
				<tr>
					<td class="cm_dashboard_form_title_header" style="text-align:left; padding:8px 18px!important; font-weight:bold;">
						<i><?php _e( 'Title', 'clubmembership' ) ?></i></td>
					<td class="cm_dashboard_entries_unread_header" style="text-align:center; padding:8px 18px!important; font-weight:bold;">
						<i><?php _e( 'Unread', 'clubmembership' ) ?></i></td>
					<td class="cm_dashboard_entries_total_header" style="text-align:center; padding:8px 18px!important; font-weight:bold;">
						<i><?php _e( 'Total', 'clubmembership' ) ?></i></td>
				</tr>
				</thead>

				<tbody class="list:user user-list">
				<?php
				foreach ( $forms as $form ) {
					$date_display = CMCommon::format_date( $form['last_lead_date'] );
					if ( ! empty( $form['total_leads'] ) ) {
						?>
						<tr class='author-self status-inherit' valign="top">
							<td class="cm_dashboard_form_title column-title" style="padding:8px 18px;">
								<a <?php echo $form['unread_count'] > 0 ? "class='form_title_unread' style='font-weight:bold;'" : '' ?> href="admin.php?page=cm_entries&view=entries&id=<?php echo absint( $form['id'] ) ?>" title="<?php echo esc_html( $form['title'] ) ?> : <?php _e( 'View All Entries', 'clubmembership' ) ?>"><?php echo esc_html( $form['title'] ) ?></a>
							</td>
							<td class="cm_dashboard_entries_unread column-date" style="padding:8px 18px; text-align:center;">
								<a <?php echo $form['unread_count'] > 0 ? "class='form_entries_unread' style='font-weight:bold;'" : '' ?> href="admin.php?page=cm_entries&view=entries&filter=unread&id=<?php echo absint( $form['id'] ) ?>" title="<?php printf( __( 'Last Entry: %s', 'clubmembership' ), $date_display ); ?>"><?php echo absint( $form['unread_count'] ) ?></a>
							</td>
							<td class="cm_dashboard_entries_total column-date" style="padding:8px 18px; text-align:center;">
								<a href="admin.php?page=cm_entries&view=entries&id=<?php echo absint( $form['id'] ) ?>" title="<?php _e( 'View All Entries', 'clubmembership' ) ?>"><?php echo absint( $form['total_leads'] ) ?></a>
							</td>
						</tr>
						<?php
					}
				}
				?>
				</tbody>
			</table>

			<?php if ( CMCommon::current_user_can_any( 'clubmembership_edit_forms' ) ) : ?>
				<p class="textright">
				<a class="cm_dashboard_button button" href="admin.php?page=cm_edit_forms"><?php _e( 'View All Forms', 'clubmembership' ) ?></a>
			<?php endif; ?>
			</p>
			<?php
		} else {
			?>
			<div class="cm_dashboard_noforms_notice">
				<?php echo sprintf( __( "You don't have any forms. Let's go %s create one %s!", 'clubmembership' ), '<a href="admin.php?page=cm_new_form">', '</a>' ); ?>
			</div>
			<?php
		}

		if ( CMCommon::current_user_can_any( 'clubmembership_view_updates' ) && ( ! function_exists( 'is_multisite' ) || ! is_multisite() || is_super_admin() ) ) {
			//displaying update message if there is an update and user has permission
			self::dashboard_update_message();
		}
	}

	public static function dashboard_update_message() {
		$version_info = CMCommon::get_version_info();

		//don't display a message if use has dismissed the message for this version
		$ary_dismissed = get_option( 'cm_dismissed_upgrades' );

		$is_dismissed = ! empty( $ary_dismissed ) && in_array( $version_info['version'], $ary_dismissed );

		if ( $is_dismissed ) {
			return;
		}

		if ( version_compare( CMCommon::$version, $version_info['version'], '<' ) ) {
			$auto_upgrade = '';

			/*if($version_info['is_valid_key']){
                $plugin_file = 'clubmembership/clubmembership.php';
                $upgrade_url = wp_nonce_url('update.php?action=upgrade-plugin&amp;plugin=' . urlencode($plugin_file), 'upgrade-plugin_' . $plugin_file);
                $auto_upgrade = sprintf(__(" or %sUpgrade Automatically%s", 'clubmembership'), "<a href='{$upgrade_url}'>", '</a>');
            }*/
			$message = sprintf( __( 'There is an update available for Club Membership. %sView Details%s %s', 'clubmembership' ), "<a href='admin.php?page=cm_update'>", '</a>', $auto_upgrade );
			?>
			<div class='updated' style='padding:15px; position:relative;' id='cm_dashboard_message'><?php echo $message ?>
				<a href="javascript:void(0);" onclick="GFDismissUpgrade();" style='float:right;'><?php _e( 'Dismiss', 'clubmembership' ) ?></a>
			</div>
			<script type="text/javascript">
				function GFDismissUpgrade() {
					jQuery("#cm_dashboard_message").slideUp();
					jQuery.post(ajaxurl, {action: 'cm_dismiss_upgrade', version: "<?php echo $version_info['version'] ?>"});
				}
			</script>
			<?php
		}
	}

	public static function dashboard_dismiss_upgrade() {
		$ary = get_option( 'cm_dismissed_upgrades' );
		if ( ! is_array( $ary ) ) {
			$ary = array();
		}

		$ary[] = $_POST['version'];
		update_option( 'cm_dismissed_upgrades', $ary );
	}


	//------------------------------------------------------
	//--------------- ALL OTHER PAGES ----------------------

	public static function register_scripts() {

		$base_url = CMCommon::get_base_url();
		$version  = CM_Class::$version;

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['cmemb_debug'] ) ? '' : '.min';

		wp_register_script( 'cmemb_chosen', $base_url . '/js/chosen.jquery.min.js', array( 'jquery' ), $version );
		wp_register_script( 'cmemb_conditional_logic', $base_url . "/js/conditional_logic{$min}.js", array( 'jquery' ), $version );
		wp_register_script( 'cmemb_datepicker_init', $base_url . "/js/datepicker{$min}.js", array( 'jquery', 'jquery-ui-datepicker', 'cmemb_clubmembership' ), $version, true );
		wp_register_script( 'cmemb_floatmenu', $base_url . "/js/floatmenu_init{$min}.js", array( 'jquery' ), $version );
		wp_register_script( 'cmemb_form_admin', $base_url . "/js/form_admin{$min}.js", array( 'jquery', 'jquery-ui-autocomplete', 'cmemb_placeholder' ), $version );
		wp_register_script( 'cmemb_form_editor', $base_url . "/js/form_editor{$min}.js", array( 'jquery', 'cmemb_json', 'cmemb_placeholder' ), $version );
		wp_register_script( 'cmemb_forms', $base_url . "/js/forms{$min}.js", array( 'jquery' ), $version );
		wp_register_script( 'cmemb_clubmembership', $base_url . "/js/clubmembership{$min}.js", array( 'jquery', 'cmemb_json' ), $version );
		wp_register_script( 'cmemb_json', $base_url . '/js/jquery.json-1.3.js', array( 'jquery' ), $version, true );
		wp_register_script( 'cmemb_masked_input', $base_url . '/js/jquery.maskedinput.min.js', array( 'jquery' ), $version );
		wp_register_script( 'cmemb_menu', $base_url . "/js/menu{$min}.js", array( 'jquery' ), $version );
		wp_register_script( 'cmemb_placeholder', $base_url . '/js/placeholders.jquery.min.js', array( 'jquery' ), $version );
		wp_register_script( 'cmemb_tooltip_init', $base_url . "/js/tooltip_init{$min}.js", array( 'jquery-ui-tooltip' ), $version );
		wp_register_script( 'cmemb_textarea_counter', $base_url . '/js/jquery.textareaCounter.plugin.js', array( 'jquery' ), $version );
		wp_register_script( 'cmemb_field_filter', $base_url . "/js/cm_field_filter{$min}.js", array( 'jquery' ), $version );
		wp_register_script( 'cmemb_shortcode_ui', $base_url . "/js/shortcode-ui{$min}.js", array( 'jquery', 'wp-backbone' ), $version, true );

		wp_register_style( 'cmemb_shortcode_ui', $base_url . "/css/shortcode-ui{$min}.css", array(), $version );

		// only required for WP versions prior to 3.3
		wp_register_script( 'cm_thickbox', $base_url . '/js/thickbox.js', array(), $version );
		wp_register_style( 'cm_thickbox', $base_url . '/js/thickbox.css', array(), $version );
		wp_localize_script(
			'cm_thickbox', 'thickboxL10n', array(
				'next'             => esc_html__( 'Next >', 'clubmembership' ),
				'prev'             => esc_html__( '< Prev', 'clubmembership' ),
				'image'            => esc_html__( 'Image', 'clubmembership' ),
				'of'               => esc_html__( 'of', 'clubmembership' ),
				'close'            => esc_html__( 'Close', 'clubmembership' ),
				'noiframes'        => esc_html__( 'This feature requires inline frames. You have iframes disabled or your browser does not support them.', 'clubmembership' ),
				'loadingAnimation' => includes_url( 'js/thickbox/loadingAnimation.gif' ),
				'closeImage'       => includes_url( 'js/thickbox/tb-close.png' )
			)
		);
	}

	public static function enqueue_admin_scripts( $hook ) {

		$scripts = array();
		$page    = self::get_page();

		switch ( $page ) {
			case 'new_form' :
			case 'form_list':
				$scripts = array(
					'cmemb_clubmembership',
					'cmemb_json',
					'cmemb_form_admin',
					'thickbox',
					'sack',
				);
				break;

			case 'form_settings':
				$scripts = array(
					'cmemb_clubmembership',
					'cmemb_forms',
					'cmemb_json',
					'cmemb_form_admin',
					'cmemb_placeholder',
					'jquery-ui-datepicker',
					'cmemb_masked_input',
					'jquery-ui-sortable',
					'sack',
				);
				break;

			case 'form_editor':
				$thickbox = ! CMCommon::is_wp_version( '3.3' ) ? 'cm_thickbox' : 'thickbox';
				$scripts  = array(
					$thickbox,
					'jquery-ui-core',
					'jquery-ui-sortable',
					'jquery-ui-draggable',
					'jquery-ui-droppable',
					'jquery-ui-tabs',
					'cmemb_clubmembership',
					'cmemb_forms',
					'cmemb_json',
					'cmemb_form_admin',
					'cmemb_floatmenu',
					'cmemb_menu',
					'cmemb_placeholder',
					'jquery-ui-autocomplete',
					'sack',
				);

				if ( wp_is_mobile() ) {
					$scripts[] = 'jquery-touch-punch';
				}

				break;

			case 'entry_detail':
				$scripts = array(
					'cmemb_json',
					'sack',
				);
				break;

			case 'entry_detail_edit':
				$scripts = array(
					'cmemb_clubmembership',
					'plupload-all',
					'sack',
				);
				break;

			case 'entry_list':
				$scripts = array(
					'wp-lists',
					'wp-ajax-response',
					'thickbox',
					'cmemb_json',
					'thickbox',
					'cmemb_field_filter',
					'sack',
				);
				break;

			case 'notification_list':
				$scripts = array(
					'cmemb_forms',
					'cmemb_json',
					'cmemb_form_admin',
					'sack',
				);
				break;

			case 'notification_new':
			case 'notification_edit':
				$scripts = array(
					'jquery-ui-autocomplete',
					'cmemb_clubmembership',
					'cmemb_placeholder',
					'cmemb_form_admin',
					'cmemb_forms',
					'cmemb_json',
					'sack',
				);
				break;

			case 'confirmation':
				$scripts = array(
					'cmemb_form_admin',
					'cmemb_forms',
					'cmemb_clubmembership',
					'cmemb_placeholder',
					'cmemb_json',
					'wp-pointer',
					'sack',
				);
				break;

			case 'addons':
				$scripts = array(
					'thickbox',
					'sack',
				);
				break;

			case 'export_entry':
				$scripts = array(
					'jquery-ui-datepicker',
					'cmemb_form_admin',
					'cmemb_field_filter',
					'sack',
				);
				break;
			case 'updates' :
				$scripts = array(
					'thickbox',
					'sack',
				);

		}

		if ( self::page_supports_add_form_button() ) {
			require_once( CMCommon::get_base_path() . '/tooltips.php' );
			wp_enqueue_script( 'cmemb_shortcode_ui' );
			wp_enqueue_style( 'cmemb_shortcode_ui' );
			wp_localize_script( 'cmemb_shortcode_ui', 'gfShortcodeUIData', array(
				'shortcodes' => self::get_shortcodes(),
				'previewNonce' => wp_create_nonce( 'gf-shortcode-ui-preview' ),
				'previewDisabled' => apply_filters( 'cmemb_shortcode_preview_disabled', true ),
				'strings' => array(
					'pleaseSelectAForm' => __( 'Please select a form.', 'clubmembership' ),
					'errorLoadingPreview' => __( 'Failed to load the preview for this form.', 'clubmembership' ),
				)
			) );
		}

		if ( empty( $scripts ) ) {
			return;
		}

		foreach ( $scripts as $script ) {
			wp_enqueue_script( $script );
		}


		CMCommon::localize_cmemb_clubmembership_multifile();

	}

	/**
	 * Gets current page name
	 *
	 * @return bool|string Page name or false
	 *   Page names:
	 *
	 *   new_form
	 *   form_list
	 *   form_editor
	 *   form_settings
	 *   confirmation
	 *   notification_list
	 *   notification_new
	 *   notification_edit
	 *   entry_list
	 *   entry_detail
	 *   entry_detail_edit
	 *   settings
	 *   addons
	 *   export_entry
	 *   export_form
	 *   import_form
	 *   updates
	 */
	public static function get_page() {

		if ( rgget( 'page' ) == 'cm_new_form' ) {
			return 'new_form';
		}

		if ( rgget( 'page' ) == 'cm_edit_forms' && ! rgget( 'id' ) ) {
			return 'form_list';
		}

		if ( rgget( 'page' ) == 'cm_edit_forms' && ! rgget( 'view' ) ) {
			return 'form_editor';
		}

		if ( rgget( 'page' ) == 'cm_edit_forms' && rgget( 'view' ) == 'settings' && ( ! rgget( 'subview' ) || rgget( 'subview' ) == 'settings' ) ) {
			return 'form_settings';
		}

		if ( rgget( 'page' ) == 'cm_edit_forms' && rgget( 'view' ) == 'settings' && rgget( 'subview' ) == 'confirmation' ) {
			return 'confirmation';
		}

		if ( rgget( 'page' ) == 'cm_edit_forms' && rgget( 'view' ) == 'settings' && rgget( 'subview' ) == 'notification' && rgget( 'nid' ) ) {
			return 'notification_edit';
		}

		if ( rgget( 'page' ) == 'cm_edit_forms' && rgget( 'view' ) == 'settings' && rgget( 'subview' ) == 'notification' && isset( $_GET['nid'] ) ) {
			return 'notification_edit';
		}

		if ( rgget( 'page' ) == 'cm_edit_forms' && rgget( 'view' ) == 'settings' && rgget( 'subview' ) == 'notification' ) {
			return 'notification_list';
		}

		if ( rgget( 'page' ) == 'cm_entries' && ( ! rgget( 'view' ) || rgget( 'view' ) == 'entries' ) ) {
			return 'entry_list';
		}

		if ( rgget( 'page' ) == 'cm_entries' && rgget( 'view' ) == 'entry' && isset( $_POST['screen_mode'] ) && $_POST['screen_mode'] == 'edit' ) {
			return 'entry_detail_edit';
		}

		if ( rgget( 'page' ) == 'cm_entries' && rgget( 'view' ) == 'entry' ){
			return 'entry_detail';
		}

		if ( rgget( 'page' ) == 'cm_settings' ) {
			return 'settings';
		}

		if ( rgget( 'page' ) == 'cm_addons' ) {
			return 'addons';
		}

		if ( rgget( 'page' ) == 'cm_export' && ( rgget( 'view' ) == 'export_entry' || ! isset( $_GET['view'] ) ) ) {
			return 'export_entry';
		}

		if ( rgget( 'page' ) == 'cm_export' && rgget( 'view' ) == 'export_form' ) {
			return 'export_form';
		}

		if ( rgget( 'page' ) == 'cm_export' && rgget( 'view' ) == 'import_form' ) {
			return 'import_form';
		}

		if ( rgget( 'page' ) == 'cm_update' ) {
			return 'updates';
		}

		return false;
	}

	public static function get_form( $form_id, $display_title = true, $display_description = true, $force_display = false, $field_values = null, $ajax = false, $tabindex = 1 ) {
		require_once( CMCommon::get_base_path() . '/form_display.php' );

		return GFFormDisplay::get_form( $form_id, $display_title, $display_description, $force_display, $field_values, $ajax, $tabindex );
	}

	public static function new_form() {
		self::form_list_page();
	}

	public static function enqueue_scripts() {
		require_once( CMCommon::get_base_path() . '/form_display.php' );
		GFFormDisplay::enqueue_scripts();
	}

	public static function print_form_scripts( $form, $ajax ) {
		require_once( CMCommon::get_base_path() . '/form_display.php' );
		GFFormDisplay::print_form_scripts( $form, $ajax );
	}

	public static function forms_page( $form_id ) {
		require_once( CMCommon::get_base_path() . '/form_detail.php' );
		GFFormDetail::forms_page( $form_id );
	}

	public static function settings_page() {
		require_once( CMCommon::get_base_path() . '/settings.php' );
		GFSettings::settings_page();
	}

	public static function add_settings_page( $name, $handle = '', $icon_path = '' ) {
		require_once( CMCommon::get_base_path() . '/settings.php' );
		GFSettings::add_settings_page( $name, $handle, $icon_path );
	}

	public static function help_page() {
		require_once( CMCommon::get_base_path() . '/help.php' );
		GFHelp::help_page();
	}

	public static function export_page() {
		require_once( CMCommon::get_base_path() . '/export.php' );
		GFExport::export_page();
	}

	public static function update_page() {
		require_once( CMCommon::get_base_path() . '/update.php' );
		GFUpdate::update_page();
	}

	public static function addons_page() {

		wp_print_styles( array( 'thickbox' ) );

		$plugins           = get_plugins();
		$installed_plugins = array();
		foreach ( $plugins as $key => $plugin ) {
			$is_active                            = is_plugin_active( $key );
			$installed_plugin                     = array( 'plugin' => $key, 'name' => $plugin['Name'], 'is_active' => $is_active );
			$installed_plugin['activation_url']   = $is_active ? '' : wp_nonce_url( "plugins.php?action=activate&plugin={$key}", "activate-plugin_{$key}" );
			$installed_plugin['deactivation_url'] = ! $is_active ? '' : wp_nonce_url( "plugins.php?action=deactivate&plugin={$key}", "deactivate-plugin_{$key}" );

			$installed_plugins[] = $installed_plugin;
		}

		$nonces = self::get_addon_nonces();

		$body    = array( 'plugins' => urlencode( serialize( $installed_plugins ) ), 'nonces' => urlencode( serialize( $nonces ) ), 'key' => CMCommon::get_key() );
		$options = array( 'body' => $body, 'headers' => array( 'Referer' => get_bloginfo( 'url' ) ), 'timeout' => 15 );

		$raw_response = CMCommon::post_to_manager( 'api.php', "op=plugin_browser&{$_SERVER['QUERY_STRING']}", $options );

		if ( is_wp_error( $raw_response ) || $raw_response['response']['code'] != 200 ) {
			echo "<div class='error' style='margin-top:50px; padding:20px;'>" . __( 'Add-On browser is currently unavailable. Please try again later.', 'clubmembership' ) . '</div>';
		} else {
			echo CMCommon::get_remote_message();
			echo $raw_response['body'];
		}
	}

	public static function get_addon_info( $api, $action, $args ) {

		if ( $action == 'plugin_information' && empty( $api ) && ( ! rgempty( 'rg', $_GET ) || $args->slug == 'clubmembership' ) ) {
			$key = CMCommon::get_key();
			$raw_response = CMCommon::post_to_manager( 'api.php', "op=get_plugin&slug={$args->slug}&key={$key}", array() );

			if ( is_wp_error( $raw_response ) || $raw_response['response']['code'] != 200 ) {
				return false;
			}

			$plugin = unserialize( $raw_response['body'] );

			$api                = new stdClass();
			$api->name          = $plugin['title'];
			$api->version       = $plugin['version'];
			$api->download_link = $plugin['download_url'];
			$api->tested = '10.0';

		}

		return $api;
	}

	public static function get_addon_nonces() {

		$raw_response = CMCommon::post_to_manager( 'api.php', 'op=get_plugins', array() );

		if ( is_wp_error( $raw_response ) || $raw_response['response']['code'] != 200 ) {
			return false;
		}

		$addons = unserialize( $raw_response['body'] );
		$nonces = array();
		foreach ( $addons as $addon ) {
			$nonces[ $addon['key'] ] = wp_create_nonce( "install-plugin_{$addon['key']}" );
		}

		return $nonces;
	}

	public static function start_export() {
		require_once( CMCommon::get_base_path() . '/export.php' );
		GFExport::start_export();
	}

	public static function get_post_category_values() {
		require_once( CMCommon::get_base_path() . '/form_detail.php' );
		GFFormDetail::get_post_category_values();
	}

	public static function get_notification_post_category_values() {
		require_once( CMCommon::get_base_path() . '/notification.php' );
		GFNotification::get_post_category_values();
	}

	public static function all_leads_page() {

		$view    = rgget( 'view' );
		$lead_id = rgget( 'lid' );

		if ( $view == 'entry' && ( rgget( 'lid' ) || ! rgblank( rgget( 'pos' ) ) ) ) {
			require_once( CMCommon::get_base_path() . '/entry_detail.php' );
			GFEntryDetail::lead_detail_page();
		} else if ( $view == 'entries' || empty( $view ) ) {
			require_once( CMCommon::get_base_path() . '/entry_list.php' );
			GFEntryList::all_leads_page();
		} else {
			$form_id = rgget( 'id' );
			do_action( 'cmemb_entries_view', $view, $form_id, $lead_id );
		}

	}

	public static function form_list_page() {
		require_once( CMCommon::get_base_path() . '/form_list.php' );
		GFFormList::form_list_page();
	}

	public static function forms() {
		if ( ! CMCommon::ensure_wp_version() ) {
			return;
		}

		$id   = CMClass::get( 'id' );
		$view = CMClass::get( 'view' );

		if ( $view == 'entries' ) {
			require_once( CMCommon::get_base_path() . '/entry_list.php' );
			GFEntryList::leads_page( $id );
		} else if ( $view == 'entry' ) {
			require_once( CMCommon::get_base_path() . '/entry_detail.php' );
			GFEntryDetail::lead_detail_page();
		} else if ( $view == 'notification' ) {
			require_once( CMCommon::get_base_path() . '/notification.php' );
			//GFNotification::notification_page($id);
		} else if ( $view == 'settings' ) {
			require_once( CMCommon::get_base_path() . '/form_settings.php' );
			GFFormSettings::form_settings_page( $id );
		} else if ( empty( $view ) ) {
			if ( is_numeric( $id ) ) {
				self::forms_page( $id );
			} else {
				self::form_list_page();
			}
		}

		do_action( 'cmemb_view', $view, $id );

	}

	public static function get( $name, $array = null ) {
		if ( ! isset( $array ) ) {
			$array = $_GET;
		}

		if ( isset( $array[ $name ] ) ) {
			return $array[ $name ];
		}

		return '';
	}

	public static function post( $name, $do_stripslashes = true ) {

		if ( isset( $_POST[ $name ] ) ) {
			return $do_stripslashes ? stripslashes_deep( $_POST[ $name ] ) : $_POST[ $name ];
		}

		return '';
	}

	// AJAX Function
	public static function resend_notifications() {

		check_admin_referer( 'cm_resend_notifications', 'cm_resend_notifications' );
		$form_id = rgpost( 'formId' );
		$leads   = rgpost( 'leadIds' ); // may be a single ID or an array of IDs
		if ( 0 == $leads ) {
			// get all the lead ids for the current filter / search
			$filter = rgpost( 'filter' );
			$search = rgpost( 'search' );
			$star   = $filter == 'star' ? 1 : null;
			$read   = $filter == 'unread' ? 0 : null;
			$status = in_array( $filter, array( 'trash', 'spam' ) ) ? $filter : 'active';

			$search_criteria['status'] = $status;

			if ( $star ) {
				$search_criteria['field_filters'][] = array( 'key' => 'is_starred', 'value' => (bool) $star );
			}
			if ( ! is_null( $read ) ) {
				$search_criteria['field_filters'][] = array( 'key' => 'is_read', 'value' => (bool) $read );
			}

			$search_field_id = rgpost( 'fieldId' );

			if ( isset( $_POST['fieldId'] ) && $_POST['fieldId'] !== '' ) {
				$key            = $search_field_id;
				$val            = $search;
				$strpos_row_key = strpos( $search_field_id, '|' );
				if ( $strpos_row_key !== false ) { //multi-row
					$key_array = explode( '|', $search_field_id );
					$key       = $key_array[0];
					$val       = $key_array[1] . ':' . $val;
				}
				$search_criteria['field_filters'][] = array(
					'key'      => $key,
					'operator' => rgempty( 'operator', $_POST ) ? 'is' : rgpost( 'operator' ),
					'value'    => $val,
				);
			}

			$leads = CM_ClassModel::search_lead_ids( $form_id, $search_criteria );
		} else {
			$leads = ! is_array( $leads ) ? array( $leads ) : $leads;
		}

		$form = apply_filters( "cmemb_before_resend_notifications_{$form_id}", apply_filters( 'cmemb_before_resend_notifications', CMClassModel::get_form_meta( $form_id ), $leads ), $leads );

		if ( empty( $leads ) || empty( $form ) ) {
			_e( 'There was an error while resending the notifications.', 'clubmembership' );
			die();
		};

		$notifications = json_decode( rgpost( 'notifications' ) );
		if ( ! is_array( $notifications ) ) {
			die( __( 'No notifications have been selected. Please select a notification to be sent.', 'clubmembership' ) );
		}

		if ( ! rgempty( 'sendTo', $_POST ) && ! CMCommon::is_valid_email_list( rgpost( 'sendTo' ) ) ) {
			die( __( 'The <strong>Send To</strong> email address provided is not valid.', 'clubmembership' ) );
		}

		foreach ( $leads as $lead_id ) {

			$lead = CMClassModel::get_lead( $lead_id );
			foreach ( $notifications as $notification_id ) {
				$notification = $form['notifications'][ $notification_id ];
				if ( ! $notification ) {
					continue;
				}

				//overriding To email if one was specified
				if ( rgpost( 'sendTo' ) ) {
					$notification['to']     = rgpost( 'sendTo' );
					$notification['toType'] = 'email';
				}

				CMCommon::send_notification( $notification, $form, $lead );
			}
		}

		die();
	}

	//-------------------------------------------------
	//----------- AJAX CALLS --------------------------
	//captcha image

	public static function captcha_image() {
		$field_properties = array( 'type' => 'captcha', 'simpleCaptchaSize' => $_GET['size'], 'simpleCaptchaFontColor' => $_GET['fg'], 'simpleCaptchaBackgroundColor' => $_GET['bg'] );
		/* @var CM_Field_CAPTCHA $field */
		$field = CM_Fields::create( $field_properties );
		if ( $_GET['type'] == 'math' ) {
			$captcha = $field->get_math_captcha( $_GET['pos'] );
		} else {
			$captcha = $field->get_captcha();
		}

		@ini_set( 'memory_limit', '256M' );
		$image = imagecreatefrompng( $captcha['path'] );

		include_once( ABSPATH . 'wp-admin/includes/image-edit.php' );
		wp_stream_image( $image, 'image/png', 0 );
		imagedestroy( $image );
		die();
	}

	public static function update_form_active() {
		check_ajax_referer( 'cm_update_form_active', 'cm_update_form_active' );
		CMClassModel::update_form_active( $_POST['form_id'], $_POST['is_active'] );
	}

	public static function update_notification_active() {
		check_ajax_referer( 'cm_update_notification_active', 'cm_update_notification_active' );
		CMClassModel::update_notification_active( $_POST['form_id'], $_POST['notification_id'], $_POST['is_active'] );
	}

	public static function update_confirmation_active() {
		check_ajax_referer( 'cm_update_confirmation_active', 'cm_update_confirmation_active' );
		CMClassModel::update_confirmation_active( $_POST['form_id'], $_POST['confirmation_id'], $_POST['is_active'] );
	}

	public static function update_lead_property() {
		check_ajax_referer( 'cm_update_lead_property', 'cm_update_lead_property' );
		CMClassModel::update_lead_property( $_POST['lead_id'], $_POST['name'], $_POST['value'] );
	}

	public static function update_lead_status() {
		check_ajax_referer( 'cm_delete_entry' );
		$status  = rgpost( 'status' );
		$lead_id = rgpost( 'entry' );

		switch ( $status ) {
			case 'unspam' :
				CMClassModel::update_lead_property( $lead_id, 'status', 'active' );
				break;

			case 'delete' :
				if ( CMCommon::current_user_can_any( 'clubmembership_delete_entries' ) ) {
					CMClassModel::delete_lead( $lead_id );
				}
				break;

			default :
				CMClassModel::update_lead_property( $lead_id, 'status', $status );
				break;
		}
		header( 'Content-Type: text/xml' );
		echo "<?xml version='1.0' standalone='yes'?><wp_ajax></wp_ajax>";
		exit();

	}

	//settings
	public static function upgrade_license() {
		require_once( CMCommon::get_base_path() . '/settings.php' );
		GFSettings::upgrade_license();
	}

	//form detail
	public static function save_form() {
		require_once( CMCommon::get_base_path() . '/form_detail.php' );
		GFFormDetail::save_form();
	}

	public static function add_field() {
		require_once( CMCommon::get_base_path() . '/form_detail.php' );
		GFFormDetail::add_field();
	}

	public static function duplicate_field() {
		require_once( CMCommon::get_base_path() . '/form_detail.php' );
		GFFormDetail::duplicate_field();
	}

	public static function delete_field() {
		require_once( CMCommon::get_base_path() . '/form_detail.php' );
		GFFormDetail::delete_field();
	}

	public static function change_input_type() {
		require_once( CMCommon::get_base_path() . '/form_detail.php' );
		GFFormDetail::change_input_type();
	}

	public static function refresh_field_preview() {
		require_once( CMCommon::get_base_path() . '/form_detail.php' );
		GFFormDetail::refresh_field_preview();
	}

	public static function delete_custom_choice() {
		require_once( CMCommon::get_base_path() . '/form_detail.php' );
		GFFormDetail::delete_custom_choice();
	}

	public static function save_custom_choice() {
		require_once( CMCommon::get_base_path() . '/form_detail.php' );
		GFFormDetail::save_custom_choice();
	}

	//entry detail
	public static function delete_file() {
		check_ajax_referer( 'cm_delete_file', 'cm_delete_file' );
		$lead_id    = intval( $_POST['lead_id'] );
		$field_id   = intval( $_POST['field_id'] );
		$file_index = intval( $_POST['file_index'] );

		CMClassModel::delete_file( $lead_id, $field_id, $file_index );
		die( "EndDeleteFile($field_id, $file_index);" );
	}

	//export
	public static function select_export_form() {
		check_ajax_referer( 'cm_select_export_form', 'cm_select_export_form' );
		$form_id = intval( $_POST['form_id'] );
		$form    = CMClassModel::get_form_meta( $form_id );

		$form = apply_filters( "cmemb_form_export_page_{$form_id}", apply_filters( 'cmemb_form_export_page', $form ) );

		$filter_settings      = CMCommon::get_field_filter_settings( $form );
		$filter_settings_json = json_encode( $filter_settings );
		$fields               = array();

		$form = GFExport::add_default_export_fields( $form );

		if ( is_array( $form['fields'] ) ) {
			/* @var CM_Field $field */
			foreach ( $form['fields'] as $field ) {
				$inputs = $field->get_entry_inputs();
				if ( is_array( $inputs ) ) {
					foreach ( $inputs as $input ) {
						$fields[] = array( $input['id'], CMCommon::get_label( $field, $input['id'] ) );
					}
				} else if ( ! $field->displayOnly ) {
					$fields[] = array( $field->id, CMCommon::get_label( $field ) );
				}
			}
		}
		$field_json = CMCommon::json_encode( $fields );

		die( "EndSelectExportForm($field_json, $filter_settings_json);" );
	}

	// form settings
	public static function save_confirmation() {
		require_once( CMCommon::get_base_path() . '/form_settings.php' );
		GFFormSettings::save_confirmation();
	}

	public static function delete_confirmation() {
		require_once( CMCommon::get_base_path() . '/form_settings.php' );
		GFFormSettings::delete_confirmation();
	}

	// form list
	public static function save_new_form() {
		require_once( CMCommon::get_base_path() . '/form_list.php' );
		GFFormList::save_new_form();
	}

	public static function top_toolbar() {

		$forms = CMClassModel::get_forms( null, 'title' );
		$id    = rgempty( 'id', $_GET ) ? count( $forms ) > 0 ? $forms[0]->id : '0' : rgget( 'id' );

		?>

		<script type="text/javascript">
			function CM_ReplaceQuery(key, newValue) {
				var new_query = "";
				var query = document.location.search.substring(1);
				var ary = query.split("&");
				var has_key = false;
				for (i = 0; i < ary.length; i++) {
					var key_value = ary[i].split("=");

					if (key_value[0] == key) {
						new_query += key + "=" + newValue + "&";
						has_key = true;
					}
					else if (key_value[0] != "display_settings") {
						new_query += key_value[0] + "=" + key_value[1] + "&";
					}
				}

				if (new_query.length > 0)
					new_query = new_query.substring(0, new_query.length - 1);

				if (!has_key)
					new_query += new_query.length > 0 ? "&" + key + "=" + newValue : "?" + key + "=" + newValue;

				return new_query;
			}

			function CM_RemoveQuery(key, query) {
				var new_query = "";
				if (query == "") {
					query = document.location.search.substring(1);
				}
				var ary = query.split("&");
				for (i = 0; i < ary.length; i++) {
					var key_value = ary[i].split("=");

					if (key_value[0] != key) {
						new_query += key_value[0] + "=" + key_value[1] + "&";
					}
				}

				if (new_query.length > 0)
					new_query = new_query.substring(0, new_query.length - 1);

				return new_query;
			}

			function CM_SwitchForm(id) {
				if (id.length > 0) {
					query = CM_ReplaceQuery("id", id);
					//remove paging from querystring when changing forms
					new_query = CM_RemoveQuery("paged", query);
					new_query = new_query.replace("cm_new_form", "cm_edit_forms");

					//remove filter vars from querystring when changing forms
					new_query = CM_RemoveQuery("s", new_query);
					new_query = CM_RemoveQuery("operator", new_query);
					new_query = CM_RemoveQuery("type", new_query);
					new_query = CM_RemoveQuery("field_id", new_query);

					//When switching forms within any form settings tab, go back to main form settings tab
					var is_form_settings = new_query.indexOf("page=cm_edit_forms") >= 0 && new_query.indexOf("view=settings");
					if (is_form_settings) {
						//going back to main form settings tab
						new_query = "page=cm_edit_forms&view=settings&id=" + id;
					}

					document.location = "?" + new_query;
				}
			}

			function ToggleFormSettings() {
				FieldClick(jQuery('#cmemb_heading')[0]);
			}

			jQuery(document).ready(function () {
				if (document.location.search.indexOf("display_settings") > 0)
					ToggleFormSettings()

				jQuery('a.cm_toolbar_disabled').click(function (event) {
					event.preventDefault();
				});
			});

		</script>

		<div id="cm_form_toolbar">
			<ul id="cm_form_toolbar_links">

				<?php
				$menu_items = apply_filters( 'cmemb_toolbar_menu', self::get_toolbar_menu_items( $id ), $id );
				echo self::format_toolbar_menu_items( $menu_items );
				?>

				<li class="cm_form_switcher">
					<label for="export_form"><?php _e( 'Select A Form', 'clubmembership' ) ?></label>

					<?php
					if ( CM_CURRENT_VIEW != 'entry' ) {
						?>
						<select name="form_switcher" id="form_switcher" onchange="CM_SwitchForm(jQuery(this).val());">
							<option value=""><?php _e( 'Switch Form', 'clubmembership' ) ?></option>
							<?php
							foreach ( $forms as $form_info ) {
								?>
								<option value="<?php echo $form_info->id ?>"><?php echo $form_info->title ?></option>
								<?php
							}
							?>
						</select>
						<?php
					} // end view check
					?>

				</li>
			</ul>
		</div>

		<?php

	}

	public static function format_toolbar_menu_items( $menu_items, $compact = false ) {
		if ( empty( $menu_items ) ) {
			return '';
		}

		$output = '';

		$priorities = array();
		foreach ( $menu_items as $k => $menu_item ) {
			$priorities[ $k ] = rgar( $menu_item, 'priority' );
		}

		array_multisort( $priorities, SORT_DESC, $menu_items );

		$keys     = array_keys( $menu_items );
		$last_key = array_pop( $keys ); // array_pop(array_keys($menu_items)) causes a Strict Standards warning in WP 3.6 on PHP 5.4

		foreach ( $menu_items as $key => $menu_item ) {
			if ( is_array( $menu_item ) ) {
				if ( CMCommon::current_user_can_any( rgar( $menu_item, 'capabilities' ) ) ) {
					$sub_menu_str         = '';
					$count_sub_menu_items = 0;
					$sub_menu_items       = rgar( $menu_item, 'sub_menu_items' );
					if ( is_array( $sub_menu_items ) ) {
						foreach ( $sub_menu_items as $k => $val ) {
							if ( false === CMCommon::current_user_can_any( rgar( $sub_menu_items[ $k ], 'capabilities' ) ) ) {
								unset( $sub_menu_items[ $k ] );
							}
						}
						$sub_menu_items       = array_values( $sub_menu_items ); //reset numeric keys
						$count_sub_menu_items = count( $sub_menu_items );
					}

					$menu_class = rgar( $menu_item, 'menu_class' );

					if ( $count_sub_menu_items == 1 ) {
						$label     = $compact ? rgar( $menu_item, 'label' ) : rgar( $sub_menu_items[0], 'label' );
						$menu_item = $sub_menu_items[0];
					} else {
						$label        = rgar( $menu_item, 'label' );
						$sub_menu_str = self::toolbar_sub_menu_items( $sub_menu_items, $compact );
					}
					$link_class = esc_attr( rgar( $menu_item, 'link_class' ) );
					$icon       = rgar( $menu_item, 'icon' );
					$url        = esc_url( rgar( $menu_item, 'url' ) );
					$title      = esc_attr( rgar( $menu_item, 'title' ) );
					$onclick    = esc_js( rgar( $menu_item, 'onclick' ) );
					$label 		= esc_html( $label );
					$target 	= rgar( $menu_item, 'target' );

					$link   	= "<a class='{$link_class}' onclick='{$onclick}' title='{$title}' href='{$url}' target='{$target}'>{$icon} {$label}</a>" . $sub_menu_str;
					if ( $compact ) {
						if ( $key == 'delete' ) {
							$link = apply_filters( 'cmemb_form_delete_link', $link );
						}
						$divider = $key == $last_key ? '' : ' | ';
						if ( $count_sub_menu_items > 0 ) {
							$menu_class .= ' cm_form_action_has_submenu';
						}
						$output .= '<span class="' . $menu_class . '">' . $link . $divider . '</span>';
					} else {

						$output .= "<li class='{$menu_class}'>{$link}</li>";
					}
				}
			} elseif ( $compact ) {
				//for backwards compatibility <1.7: form actions only
				$divider = $key == $last_key ? '' : ' | ';
				$output .= '<span class="edit">' . $menu_item . $divider . '</span>';
			}
		}

		return $output;
	}

	public static function get_toolbar_menu_items( $form_id, $compact = false ) {
		$menu_items = array();

		$form_id = absint( $form_id );

		//---- Form Editor ----
		$edit_capabilities = array( 'clubmembership_edit_forms' );

		$menu_items['edit'] = array(
			'label'        => $compact ? __( 'Edit', 'clubmembership' ) : __( 'Form Editor', 'clubmembership' ),
			'icon'         => '<i class="fa fa-pencil-square-o fa-lg"></i>',
			'title'        => __( 'Edit this form', 'clubmembership' ),
			'url'          => '?page=cm_edit_forms&id=' . $form_id,
			'menu_class'   => 'cm_form_toolbar_editor',
			'link_class'   => self::toolbar_class( 'editor' ),
			'capabilities' => $edit_capabilities,
			'priority'     => 1000,
		);

		//---- Form Settings ----

		$sub_menu_items = self::get_form_settings_sub_menu_items( $form_id );

		$menu_items['settings'] = array(
			'label'          => $compact ? __( 'Settings', 'clubmembership' ) : __( 'Form Settings', 'clubmembership' ),
			'icon'           => '<i class="fa fa-cogs fa-lg"></i>',
			'title'          => __( 'Edit settings for this form', 'clubmembership' ),
			'url'            => '?page=cm_edit_forms&view=settings&id=' . $form_id,
			'menu_class'     => 'cm_form_toolbar_settings',
			'link_class'     => self::toolbar_class( 'settings' ),
			'sub_menu_items' => $sub_menu_items,
			'capabilities'   => $edit_capabilities,
			'priority'       => 900,
		);


		//---- Entries ----

		$entries_capabilities = array( 'clubmembership_view_entries', 'clubmembership_edit_entries', 'clubmembership_delete_entries' );

		$menu_items['entries'] = array(
			'label'        => __( 'Entries', 'clubmembership' ),
			'icon'         => '<i class="fa fa-comment-o fa-lg"></i>',
			'title'        => __( 'View entries generated by this form', 'clubmembership' ),
			'url'          => '?page=cm_entries&id=' . $form_id,
			'menu_class'   => 'cm_form_toolbar_entries',
			'link_class'   => self::toolbar_class( 'entries' ),
			'capabilities' => $entries_capabilities,
			'priority'     => 800,
		);

		//---- Preview ----

		$preview_capabilities = array( 'clubmembership_edit_forms', 'clubmembership_create_form', 'clubmembership_preview_forms' );

		$menu_items['preview'] = array(
			'label'        => __( 'Preview', 'clubmembership' ),
			'icon'         => '<i class="fa fa-eye fa-lg"></i>',
			'title'        => __( 'Preview this form', 'clubmembership' ),
			'url'          => trailingslashit( site_url() ) . '?cm_page=preview&id=' . $form_id,
			'menu_class'   => 'cm_form_toolbar_preview',
			'link_class'   => self::toolbar_class( 'preview' ),
			'target'       => '_blank',
			'capabilities' => $preview_capabilities,
			'priority'     => 700,
		);


		return $menu_items;
	}

	public static function toolbar_sub_menu_items( $menu_items, $compact = false ) {
		if ( empty( $menu_items ) ) {
			return '';
		}

		$sub_menu_items_string = '';
		foreach ( $menu_items as $menu_item ) {
			if ( CMCommon::current_user_can_any( rgar( $menu_item, 'capabilities' ) ) ) {
				$menu_class = esc_attr( rgar( $menu_item, 'menu_class' ) );
				$link_class = esc_attr( rgar( $menu_item, 'link_class' ) );
				$url        = esc_url( rgar( $menu_item, 'url' ) );
				$label      = esc_html( rgar( $menu_item, 'label' ) );
				$target     = esc_attr( rgar( $menu_item, 'target' ) );
				$sub_menu_items_string .= "<li class='{$menu_class}'><a href='{$url}' class='{$link_class}' target='{$target}'>{$label}</a></li>";
			}
		}
		if ( $compact ) {
			$sub_menu_items_string = '<div class="cm_submenu"><ul>' . $sub_menu_items_string . '</ul></div>';
		} else {
			$sub_menu_items_string = '<div class="cm_submenu"><ul>' . $sub_menu_items_string . '</ul></div>';
		}

		return $sub_menu_items_string;
	}

	public static function get_form_settings_sub_menu_items( $form_id ) {
		require_once( CMCommon::get_base_path() . '/form_settings.php' );

		$sub_menu_items = array();
		$tabs           = GFFormSettings::get_tabs( $form_id );

		foreach ( $tabs as $tab ) {

			if ( $tab['name'] == 'settings' ) {
				$form_setting_menu_item['label'] = 'Settings';
			}

			$sub_menu_items[] = array(
				'url'          => admin_url( "admin.php?page=cm_edit_forms&view=settings&subview={$tab['name']}&id={$form_id}" ),
				'label'        => $tab['label'],
				'capabilities' => array( 'clubmembership_edit_forms' )
			);

		}

		return $sub_menu_items;
	}

	private static function toolbar_class( $item ) {

		switch ( $item ) {

			case 'editor':
				if ( in_array( rgget( 'page' ), array( 'cm_edit_forms', 'cm_new_form' ) ) && rgempty( 'view', $_GET ) ) {
					return 'cm_toolbar_active';
				}
				break;

			case 'settings':
				if ( rgget( 'view' ) == 'settings' ) {
					return 'cm_toolbar_active';
				}
				break;

			case 'notifications' :
				if ( rgget( 'page' ) == 'cm_new_form' ) {
					return 'cm_toolbar_disabled';
				} else if ( rgget( 'page' ) == 'cm_edit_forms' && rgget( 'view' ) == 'notification' ) {
					return 'cm_toolbar_active';
				}
				break;

			case 'entries' :
				if ( rgget( 'page' ) == 'cm_new_form' ) {
					return 'cm_toolbar_disabled';
				} else if ( rgget( 'page' ) == 'cm_entries' && rgempty( 'view', $_GET ) ) {
					return 'cm_toolbar_active';
				}

				break;

			case 'preview' :
				if ( rgget( 'page' ) == 'cm_new_form' ) {
					return 'cm_toolbar_disabled';
				}

				break;
		}

		return '';
	}

	public static function admin_bar() {
		global $wp_admin_bar;

		if ( ! CMCommon::current_user_can_any( 'clubmembership_create_form' ) ) {
			return;
		}

		$wp_admin_bar->add_menu(
			array(
				'id'     => 'clubmembership-new-form',
				'parent' => 'new-content',
				'title'  => esc_attr__( 'Form', 'clubmembership' ),
				'href'   => admin_url( 'admin.php?page="cm_new_form' )
			)
		);
	}

	public static function maybe_auto_update( $update, $item ) {

		if ( isset($item->slug) && $item->slug == 'clubmembership' ) {

			CMCommon::log_debug( 'CM_Class::maybe_auto_update() - Starting auto-update for clubmembership.' );

			$auto_update_disabled = self::is_auto_update_disabled();
			CMCommon::log_debug( 'CM_Class::maybe_auto_update() - $auto_update_disabled: ' . var_export( $auto_update_disabled, true ) );

			if ( $auto_update_disabled || version_compare( CM_Class::$version, $item->new_version, '=>' ) ) {
				CMCommon::log_debug( 'CM_Class::maybe_auto_update() - Aborting update.' );
				return false;
			}

			$current_major = implode( '.', array_slice( preg_split( '/[.-]/', CM_Class::$version ), 0, 1 ) );
			$new_major     = implode( '.', array_slice( preg_split( '/[.-]/', $item->new_version ), 0, 1 ) );

			$current_branch = implode( '.', array_slice( preg_split( '/[.-]/', CM_Class::$version ), 0, 2 ) );
			$new_branch     = implode( '.', array_slice( preg_split( '/[.-]/', $item->new_version ), 0, 2 ) );

			if ( $current_major == $new_major && $current_branch == $new_branch ) {
				CMCommon::log_debug( 'CM_Class::maybe_auto_update() - OK to update.' );
				return true;
			}
		}

		return $update;
	}

	public static function is_auto_update_disabled(){

		// Currently WordPress won't ask Club Membership to update if background updates are disabled.
		// Let's double check anyway.

		// WordPress background updates are disabled if you don't want file changes.
		if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ){
			return true;
		}

		if ( defined( 'WP_INSTALLING' ) ){
			return true;
		}

		$wp_updates_disabled = defined( 'AUTOMATIC_UPDATER_DISABLED' ) && AUTOMATIC_UPDATER_DISABLED;

		$wp_updates_disabled = apply_filters( 'automatic_updater_disabled', $wp_updates_disabled );

		if ( $wp_updates_disabled ) {
			CMCommon::log_debug( __METHOD__ . '() - Background updates are disabled in WordPress.' );
			return true;
		}

		// Now check Club Membership Background Update Settings

		$enabled = get_option( 'cmemb_enable_background_updates' );
		CMCommon::log_debug( 'CM_Class::is_auto_update_disabled() - $enabled: ' . var_export( $enabled, true ) );

		$disabled = apply_filters( 'cmemb_disable_auto_update', ! $enabled );
		CMCommon::log_debug( 'CM_Class::is_auto_update_disabled() - $disabled: ' . var_export( $disabled, true ) );

		if ( ! $disabled ) {
			$disabled = defined( 'GFORM_DISABLE_AUTO_UPDATE' ) && GFORM_DISABLE_AUTO_UPDATE;
			CMCommon::log_debug( 'CM_Class::is_auto_update_disabled() - GFORM_DISABLE_AUTO_UPDATE: ' . var_export( $disabled, true ) );
		}

		return $disabled;
	}

	public static function deprecate_add_on_methods() {
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) ) {
			return;
		}
		$deprecated = CMAddOn::get_all_deprecated_protected_methods();
		if ( ! empty( $deprecated ) ) {
			foreach ( $deprecated as $method ) {
				_deprecated_function( $method, '1.9', 'public access level' );
			}
		}
	}

	/**
	 * Shortcode UI
	 */

	/**
	 * Output a shortcode.
	 * ajax callback for displaying the shortcode in the TinyMCE editor.
	 *
	 * @return null
	 */
	public static function handle_ajax_do_shortcode( ) {

		$shortcode = ! empty( $_POST['shortcode'] ) ? sanitize_text_field( stripslashes( $_POST['shortcode'] ) ) : null;
		$post_id   = ! empty( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : null;

		if ( ! current_user_can( 'edit_post', $post_id ) || ! wp_verify_nonce( $_POST['nonce'], 'gf-shortcode-ui-preview' ) ) {
			echo esc_html__( 'Error', 'clubmembership' );
			exit;
		}

		$form_id   = ! empty( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : null;

		global $post;
		$post = get_post( $post_id );
		setup_postdata( $post );

		self::enqueue_form_scripts( $form_id, true );
		wp_print_scripts();
		wp_print_styles();

		echo do_shortcode( $shortcode );

		// Disable the elements on the form
		?>
		<script type="text/javascript">
			jQuery('.cmemb_wrapper input, .cmemb_wrapper select, .cmemb_wrapper textarea').prop('disabled', true);
			jQuery('a img').each(function () {
				var image = this.src;
				var img = jQuery('<img>', { src: image });
				$(this).parent().replaceWith(img);
			});
			jQuery('a').each(function () {
				jQuery(this).replaceWith(jQuery(this).text());
			});
		</script>
		<?php
		exit;
	}

	public static function action_print_media_templates() {

		echo CM_Class::get_view( 'edit-shortcode-form' );
	}

	public static function get_view( $template ) {

		if ( ! file_exists( $template ) ) {

			$template_dir  = CMCommon::get_base_path() . '/includes/templates/';
			$template = $template_dir . $template . '.tpl.php';

			if ( ! file_exists( $template ) ) {
				return '';
			}
		}

		ob_start();
		include $template;

		return ob_get_clean();
	}

	public static function modify_tiny_mce_4( $init ) {

		// Hack to fix compatibility issue with ACF PRO
		if ( ! isset( $init['content_css'] ) ) {
			return $init;
		}

		$base_url = CMCommon::get_base_url();

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['cmemb_debug'] ) ? '' : '.min';

		$editor_styles = $base_url . "/css/shortcode-ui-editor-styles{$min}.css,";
		$form_styles = $base_url . "/css/formsmain{$min}.css";

		if ( isset( $init['content_css'] ) ) {
			if ( empty( $init['content_css'] ) ) {
				$init['content_css'] = '';
			} elseif ( is_array( $init['content_css'] ) ) {
				$init['content_css'][] = $editor_styles;
				$init['content_css'][] = $form_styles;
				return $init;
			} else {
				$init['content_css'] = $init['content_css'] . ',';
			}
		}

		// Note: Using .= here can trigger a fatal error
		$init['content_css'] = $init['content_css'] . $editor_styles . $form_styles;
		return $init;
	}

	public static function get_shortcodes() {

		$forms             = CMClassModel::get_forms( 1, 'title' );
		$forms_options[''] = __( 'Select a Form', 'clubmembership' );
		foreach ( $forms as $form ) {
			$forms_options[ absint( $form->id ) ] = esc_html( $form->title );
		}

		$default_attrs = array(
			array(
				'label'       => __( 'Select a form below to add it to your post or page.', 'clubmembership' ),
				'tooltip'     => __( 'Select a form from the list to add it to your post or page.', 'clubmembership' ),
				'attr'        => 'id',
				'type'        => 'select',
				'section'     => 'required',
				'description' => __( "Can't find your form? Make sure it is active.", 'clubmembership' ),
				'options'     => $forms_options,
			),
			array(
				'label'   => __( 'Display form title', 'clubmembership' ),
				'attr'    => 'title',
				'default' => 'true',
				'section' => 'standard',
				'type'    => 'checkbox',
				'tooltip' => __( 'Whether or not do display the form title.', 'clubmembership' )
			),
			array(
				'label'   => __( 'Display form description', 'clubmembership' ),
				'attr'    => 'description',
				'default' => 'true',
				'section' => 'standard',
				'type'    => 'checkbox',
				'tooltip' => __( 'Whether or not do display the form description.', 'clubmembership' )
			),
			array(
				'label'   => __( 'Enable AJAX', 'clubmembership' ),
				'attr'    => 'ajax',
				'section' => 'standard',
				'type'    => 'checkbox',
				'tooltip' => __( 'Specify whether or not to use AJAX to submit the form.', 'clubmembership' )
			),
			array(
				'label'   => 'Tabindex',
				'attr'    => 'tabindex',
				'type'    => 'number',
				'tooltip' => __( 'Specify the starting tab index for the fields of this form.', 'clubmembership' )
			),

		);

		$add_on_actions = apply_filters( 'cmemb_shortcode_builder_actions', array() );

		if ( ! empty( $add_on_actions ) ) {
			$action_options = array( '' => __( 'Select an action', 'clubmembership' ) );
			foreach ( $add_on_actions as $add_on_action ) {
				foreach ( $add_on_action as $key => $array ) {
					$action_options[ $key ] = $array['label'];
				}
			}

			$default_attrs[] = array(
				'label'   => 'Action',
				'attr'    => 'action',
				'type'    => 'select',
				'options' => $action_options,
				'tooltip' => __( 'Select an action for this shortcode. Actions are added by some add-ons.', 'clubmembership' )
			);
		}

		$shortcode = array(
			'shortcode_tag' => 'clubmembership',
			'action_tag' => '',
			'label'         => 'Club Membership',
			'attrs'         => $default_attrs,
		);

		$shortcodes[] = $shortcode;

		if ( ! empty( $add_on_actions ) ) {
			foreach ( $add_on_actions as $add_on_action ) {
				foreach ( $add_on_action as $key => $array ) {
					$attrs     = array_merge( $default_attrs, $array['attrs'] );
					$shortcode = array(
						'shortcode_tag' => 'clubmembership',
						'action_tag' => $key,
						'label'         => rgar( $array, 'label' ),
						'attrs'         => $attrs,
					);
				}
			}
			$shortcodes[] = $shortcode;
		}

		return $shortcodes;
	}

	public static function enqueue_form_scripts( $form_id, $is_ajax = false ) {
		require_once( CMCommon::get_base_path() . '/form_display.php' );
		$form = CMClassModel::get_form_meta( $form_id );
		GFFormDisplay::enqueue_form_scripts( $form, $is_ajax );
		$addons = CMAddOn::get_registered_addons();
		foreach ( $addons as $addon ) {
			$a = call_user_func( array( $addon, 'get_instance' ) );
			$a->enqueue_scripts( $form, $is_ajax );
		}
	}

}

class CMClass extends CM_Class {
}

//Main function call. Should be used to insert a Gravity Form from code.
function gravity_form( $id, $display_title = true, $display_description = true, $display_inactive = false, $field_values = null, $ajax = false, $tabindex = 1, $echo = true ) {
	if ( ! $echo ) {
		return CMClass::get_form( $id, $display_title, $display_description, $display_inactive, $field_values, $ajax, $tabindex );
	}

	echo CMClass::get_form( $id, $display_title, $display_description, $display_inactive, $field_values, $ajax, $tabindex );
}

//Enqueues the appropriate scripts for the specified form
function gravity_form_enqueue_scripts( $form_id, $is_ajax = false ) {
	CM_Class::enqueue_form_scripts( $form_id, $is_ajax );
}

if ( ! function_exists( 'rgget' ) ) {
	function rgget( $name, $array = null ) {
		if ( ! isset( $array ) ) {
			$array = $_GET;
		}

		if ( isset( $array[ $name ] ) ) {
			return $array[ $name ];
		}

		return '';
	}
}

if ( ! function_exists( 'rgpost' ) ) {
	function rgpost( $name, $do_stripslashes = true ) {
		if ( isset( $_POST[ $name ] ) ) {
			return $do_stripslashes ? stripslashes_deep( $_POST[ $name ] ) : $_POST[ $name ];
		}

		return '';
	}
}

if ( ! function_exists( 'rgar' ) ) {
	function rgar( $array, $name ) {
		if ( isset( $array[ $name ] ) ) {
			return $array[ $name ];
		}

		return '';
	}
}

if ( ! function_exists( 'rgars' ) ) {
	function rgars( $array, $name ) {
		$names = explode( '/', $name );
		$val   = $array;
		foreach ( $names as $current_name ) {
			$val = rgar( $val, $current_name );
		}

		return $val;
	}
}

if ( ! function_exists( 'rgempty' ) ) {
	function rgempty( $name, $array = null ) {

		if ( is_array( $name ) ) {
			return empty( $name );
		}

		if ( ! $array ) {
			$array = $_POST;
		}

		$val = rgar( $array, $name );

		return empty( $val );
	}
}

if ( ! function_exists( 'rgblank' ) ) {
	function rgblank( $text ) {
		return empty( $text ) && strval( $text ) != '0';
	}
}

if ( ! function_exists( 'rgobj' ) ) {
	function rgobj( $obj, $name ) {
		if ( isset( $obj->$name ) ) {
			return $obj->$name;
		}

		return '';
	}
}
if ( ! function_exists( 'rgexplode' ) ) {
	function rgexplode( $sep, $string, $count ) {
		$ary = explode( $sep, $string );
		while ( count( $ary ) < $count ) {
			$ary[] = '';
		}

		return $ary;
	}
}

if( ! function_exists( 'cm_apply_filters' ) ) {
	function cm_apply_filters( $filter, $modifiers, $value ) {

		if( ! is_array( $modifiers ) ) {
			$modifiers = array( $modifiers );
		}

		// add an empty modifier so the base filter will be applied as well
		array_unshift( $modifiers, '' );

		$args = array_slice( func_get_args(), 3 );
		$args = array_pad( $args, 10, null );

		// apply modified versions of filter
		foreach( $modifiers as $modifier ) {
			$modifier = empty( $modifier ) ? '' : sprintf( '_%s', $modifier );
			$filter  .= $modifier;
			$value    = apply_filters( $filter, $value, $args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8], $args[9] );
		}

		return $value;
	}
}
