<?php
$debug = false;

if ( $debug ) {
	ini_set( "display_startup_errors", 1 );
	ini_set( "display_errors", 1 );
	error_reporting( - 1 );
} else {
	ini_set( "display_errors", 0 );
}

set_error_handler( "kaosWarning", E_USER_WARNING );

$user_error_type = array(
	'notice'  => 1024,
	'warning' => 512,
	'error'   => 256,
);

$memb_error = new WP_Error();


/* preset FGC setting*/
if ( file_exists( TEMPLATEPATH . "/includes/options-init.php" ) ) {
	/* @noinspection PhpIncludeInspection */
	require_once TEMPLATEPATH . "/includes/options-init.php";
}

/* Designed by TemplateLite.com */
$tpinfo[ 'themename' ] = 'Beach Holiday';

//for options. e.g. all templatelite themes should use "templatelite" for general options (feed url, twitter id, analytics)
$tpinfo[ 'prefix' ] = 'templatelite';

//for options. theme base prefix
$tpinfo[ 'tb_prefix' ] = 'templatelite_beachholiday';


if ( function_exists( 'register_sidebar' ) ) {
	register_sidebar( array(
		'before_widget' => '<li><div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div></li>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
}

/* @noinspection PhpIncludeInspection */
include( TEMPLATEPATH . '/includes/theme-options.php' );
/** @noinspection PhpIncludeInspection */
include( TEMPLATEPATH . '/includes/theme-setup.php' );
/** @noinspection PhpIncludeInspection */
include( TEMPLATEPATH . '/includes/theme-functions.php' );
/** @noinspection PhpIncludeInspection */
//include( TEMPLATEPATH . '/includes/ctxphc-functions.php' );
/** @noinspection PhpIncludeInspection */
//include( TEMPLATEPATH . '/includes/pb_reg_functions.php' );
/** @noinspection PhpIncludeInspection */
include( TEMPLATEPATH . '/template.php' );


//remove_action( 'woocommerce_before_shop_loop', '', 20);

//remove_action( 'woocommerce_pagination', 'woocommerce_catalog_ordering', 20 );

/**
 *  Load custom scripts:
 */
function reg_custom_scripts_and_styles() {

	wp_register_script( 'mp-validation-script', get_template_directory_uri() . '/includes/js/mp-validation-script.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'mp-validation-script' );

	//Register JQuery Input Validation Rules in English
	wp_register_script( 'validation-local', get_template_directory_uri() . '/includes/js/languages/jquery.validationEngine-en.js', '', true );
	wp_enqueue_script( 'validation-local' );

	//Register jQuery Input Validation Engine
	wp_register_script( 'validation-engine', get_template_directory_uri() . '/includes/js/jquery.validationEngine.js', '', true );
	wp_enqueue_script( 'validation-engine' );

	//Register jQuery Input Validation CSS Stylesheet
	wp_register_style( 'validation-style', get_template_directory_uri() . '/includes/css/validationEngine.jquery.css' );

	wp_register_script( 'ctxphc-scripts', get_template_directory_uri() . '/includes/js/ctxphc-scripts.js', array( 'jquery' ), '', true );
	if ( is_page( 'membership' ) ) {
		wp_enqueue_script( 'ctxphc-scripts' );
		wp_enqueue_style( 'validation-style' );
	}

	wp_register_script( 'ctxphc-pb-script', get_template_directory_uri() . '/includes/js/ctxphc-pb-script.js', array( 'jquery' ), '', true );

	if ( is_page( 'pirates-ball-members-only-early-registration' ) || is_page( 'pirates-ball-early-registration' ) || is_page(
			'pirates-ball-registration' ) || is_page( 'pirates-ball-private-registration' )
	) {
		wp_enqueue_script( 'ctxphc-pb-script' );
		wp_enqueue_style( 'validation-style' );
	}
	
	//Register CTXPHC Custom CSS Stylesheet
	wp_register_style( 'ctxphc-custom-style', get_template_directory_uri() . '/includes/css/ctxphc-style.css' );
	wp_enqueue_style( 'ctxphc-custom-style' );

	//Register CTXPHC CSS Print Stylesheet
	wp_register_style( 'ctxphc-print-style', get_template_directory_uri() . '/includes/css/ctxphc-print-style.css', '', '', "print" );
	wp_enqueue_style( 'ctxphc-print-style' );

	//Register CTXPHC Pirates Ball Registration Custom CSS Stylesheet
	wp_register_style( 'pb_reg_styles', get_stylesheet_directory_uri() . '/includes/css/pb_reg_styles.css', array(), '1.0' );
	if ( is_page( 'pirates-ball-members-only-early-registration' ) || is_page( 'pirates-ball-early-registration' ) || is_page( 'pirates-ball-registration' ) || is_page( 'pirates-ball-private-registration' ) ) {
		wp_enqueue_style( 'pb_reg_styles' );
	}
}

add_action( 'wp_enqueue_scripts', 'reg_custom_scripts_and_styles' );

add_filter( 'wpmem_admin_style_list', 'kaos_styles' );

/**
 * woocommerce hooks
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

function my_theme_wrapper_start() {
	echo '<div id="content"><div class="spacer"></div>';
}

function my_theme_wrapper_end() {
	echo '</div> <!-- content -->';
}

add_filter( 'woocommerce_enqueue_styles', 'kaos_dequeue_styles' );
function kaos_dequeue_styles( $enqueue_styles ) {
	//unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
	unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
	//unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
	return $enqueue_styles;
}

function wp_enqueue_woocommerce_style(){
	wp_register_style( 'beach-holiday-woocommerce', get_template_directory_uri() . '/includes/css/beach-holiday-woocommerce.css' );

	if ( class_exists( 'woocommerce' ) ) {
		wp_enqueue_style( 'beach-holiday-woocommerce' );
	}
}
add_action( 'wp_enqueue_scripts', 'wp_enqueue_woocommerce_style' );

// Or just remove them all in one line
//add_filter( 'woocommerce_enqueue_styles', '__return_false' );

//add_action( 'wp_enqueue_scripts', 'reg_custom_scripts_and_styles' );

// Change number or products per row to 2
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 2; // 2 products per row
	}
}

add_theme_support( 'post-thumbnails' );

/**
 * @param $list
 *
 * @return array
 */
function kaos_styles( $list ) {
	/**
	 * Create an array for the stylesheets you want to add.
	 * The key is what will show in the dropdown, the value
	 * is the path/url to the stylesheet.
	 */
	$new_style = array(
		'CTXPHC Stylesheet' => '/wp-content/themes/beach-house/style.css',
		//'Some Style' => '/wp-content/some-directory/some-other-sheet.css'
	);

	/**
	 * Use array_merge to merge the new styles array $new_style
	 * into the dropdown array $list.
	 */
	$new_list = array_merge( $list, $new_style );

	/**
	 * Return your new list array
	 */
	return $new_list;
}


function redirect_after_logout() {
	wp_logout_url( home_url() );
}

add_filter( 'allowed_redirect_hosts', 'redirect_after_logout' );


function memb_lower_case_user_name( $name ) {
	// might be turned off
	if ( function_exists( 'sp_strtolower' ) ) {
		return mb_strtolower( $name );
	}

	return strtolower( $name );
}

add_filter( 'sanitize_user', 'memb_lower_case_user_name' );


//TODO:  Create IPN Web Accept handler Class

//Main IPN Web Accept Txn Type processing calls Membership Paypal processing class
/**
 * @param $data
 */
/**
 * function cm_ipn_web_accept_payment_processing( $data ) {
 * $payment_processing = new membershipPayPalPaymentProcessing( $data );
 * }
 *
 * //add_action( 'paypal-paypal_ipn_for_wordpress_txn_type_web_accept', 'cm_ipn_web_accept_payment_processing' );
 *
 * function my_ipn_web_failed_payment_processing( $data ) {
 * $payment_processing = new membershipPayPalPaymentProcessing( $data );
 * }
 *
 * add_action( 'paypal-web_accept_failed', 'my_ipn_web_failed_payment_processing' );
 *
 * /**
 * @param bool $renewing
 *
 * @return string
 */
function get_renewal_date( $renewing = false ) {
	$current_date          = is_date_safe( date( "Y-m-d" ) );
	$curr_year             = intval( date( 'Y' ) );
	$renewal_year          = $curr_year + 1;
	$extended_renewal_year = $renewal_year + 1;
	$extend_renewal_date   = $curr_year . '-09-01';

	if ( $renewing ) {
		//process renewing member  May be able to delete complete section.

	}
	//todo: update this to use unix timestamps
	if ( $current_date > $extend_renewal_date ) {
		$renewal_date = $extended_renewal_year . '-01-01';
	} else {
		$renewal_date = $renewal_year . '-01-01';
	}

	return $renewal_date;
}

/**
 * @return wpdb
 * @internal param $rel_id
 *
 */
function get_membership_pricing() {
	/** @var wpdb $wpdb */
	global $wpdb;
	$pricing    = array();
	$costs      = $wpdb->get_results( "SELECT cost FROM ctxphc_membership_pricing" );
	$type_count = count( $costs );

	for ( $x = 1, $y = 0; $x <= $type_count; $x ++, $y ++ ) {
		$pricing[ $x ] = $costs[ $y ];
	}

	return $pricing;
}

/**
 * @return mixed
 */
function get_membership_types() {
	global $wpdb;
	$memberships = new stdClass();

	$memb_info = $wpdb->get_results( "SELECT * FROM  ctxphc_membership_types" );

	foreach ( $memb_info as $membkey => $membvalue ) {
		$obj_key               = $membvalue->ID;
		$memberships->$obj_key = $membvalue;
	}

	return $memberships;
}

/**
 * @param $userInfo
 *
 * @return stdClass
 */
function load_current_member_data( $userInfo ) {

	$memb_data = new stdClass();

	If ( $userInfo->membUserId ) {
		//This is a spouse/partner.
		$userInfo = get_userdata( $userInfo->membUserID );
	}


	//$user_id                                           = $memb_data->id = $userInfo->ID;
	/** @noinspection PhpUndefinedFieldInspection */
	$ctxphc_memb_meta_data[ 'ctxphc_memb_type' ]       = $memb_data->type = $userInfo->membership_id;
	$ctxphc_memb_meta_data[ 'ctxphc_memb_first_name' ] = $memb_data->fname = $userInfo->first_name;
	$ctxphc_memb_meta_data[ 'ctxphc_memb_last_name' ]  = $memb_data->lname = $userInfo->last_name;

	if ( isset( $userInfo->phone ) && ! empty( $userInfo->phone ) ) {
		$ctxphc_memb_meta_data[ 'ctxphc_memb_phone' ] = $memb_data->phone = $userInfo->phone;
	} elseif ( isset( $userInfo->user_phone ) && ! empty( $userInfo->user_phone ) ) {
		$ctxphc_memb_meta_data[ 'ctxphc_memb_phone' ] = $memb_data->phone = $userInfo->user_phone;
		update_user_meta( $memb_data->id, 'phone', $memb_data->phone );
	}


	if ( isset( $userInfo->email ) && ! empty( $userInfo->email ) ) {
		$ctxphc_memb_meta_data[ 'ctxphc_memb_email' ] = $memb_data->email = $userInfo->email;
	} elseif ( isset( $userInfo->user_email ) && ! empty( $userInfo->user_email ) ) {
		$ctxphc_memb_meta_data[ 'ctxphc_memb_email' ] = $memb_data->email = $userInfo->user_email;
	}

	$ctxphc_memb_meta_data[ 'ctxphc_memb_addr1' ]      = $memb_data->addr1 = $userInfo->addr1;
	$ctxphc_memb_meta_data[ 'ctxphc_memb_addr2' ]      = $memb_data->addr2 = $userInfo->addr2;
	$ctxphc_memb_meta_data[ 'ctxphc_memb_city' ]       = $memb_data->city = $userInfo->city;
	$ctxphc_memb_meta_data[ 'ctxphc_memb_state' ]      = $memb_data->state = $userInfo->state;
	$ctxphc_memb_meta_data[ 'ctxphc_memb_zip' ]        = $memb_data->zip = $userInfo->zip;
	$ctxphc_memb_meta_data[ 'ctxphc_memb_birthdate' ]  = $memb_data->birthday = $userInfo->birthday;
	$ctxphc_memb_meta_data[ 'ctxphc_memb_occupation' ] = $memb_data->occup = $userInfo->occupation;


	$memb_data->phone1 = substr( $memb_data->phone, 0, 3 );
	$memb_data->phone2 = substr( $memb_data->phone, 4, 3 );
	$memb_data->phone3 = substr( $memb_data->phone, 8, 4 );

	//todo:  Check if current user is the spouse or child of primary user.
	//       If it is get the primary users ID and use this to get all other data
	//       associated with the user

	foreach ( $ctxphc_memb_meta_data as $meta_key => $meta_value ) {
		//update_user_meta( $user_id, $meta_key, $meta_value );
	}


	return $memb_data;
}

function load_spouse_data( $userInfo ) {

	$spouses_data        = new stdClass();
	$ctxphc_sp_meta_data = array();

	if ( isset( $userInfo->fam_memb_id_1 ) && ( $userInfo->fam_memb_id_1 != $userInfo->id ) ) {
		$sp_id  = $userInfo->fam_memb_id_1;
		$sp_rec = get_userdata( $sp_id );

		$spouses_data->id                                = $sp_id;
		$ctxphc_sp_meta_data[ 'ctxphc_sp_first_name' ]   = $spouses_data->fname = $sp_rec->first_name;
		$ctxphc_sp_meta_data[ 'ctxphc_sp_last_name' ]    = $spouses_data->lname = $sp_rec->last_name;
		$ctxphc_sp_meta_data[ 'ctxphc_sp_phone' ]        = $spouses_data->phone = $sp_rec->phone;
		$ctxphc_sp_meta_data[ 'ctxphc_sp_email' ]        = $spouses_data->email = $sp_rec->email;
		$ctxphc_sp_meta_data[ 'ctxphc_sp_relationship' ] = $spouses_data->rel_id = $sp_rec->relationship_id;
		$ctxphc_sp_meta_data[ 'ctxphc_sp_birthdate' ]    = $spouses_data->birthday = $sp_rec->birthday;

		if ( ! empty( $spouses_data->phone ) ) {
			$ctxphc_sp_meta_data[ 'ctxphc_sp_first_name' ] = $spouses_data->phone = $sp_rec->user_phone;

			if ( ! empty( $spouses_data->phone ) ) {
				update_user_meta( $sp_id, 'phone', $spouses_data->phone );
			}
		}

		$spouses_data->phone1 = substr( $spouses_data->phone, 0, 3 );
		$spouses_data->phone2 = substr( $spouses_data->phone, 4, 3 );
		$spouses_data->phone3 = substr( $spouses_data->phone, 8, 4 );

	} elseif ( isset( $userInfo->sp_first_name ) && ( ! empty( $userInfo->sp_first_name ) ) ) {
		$ctxphc_sp_meta_data[ 'ctxphc_sp_first_name' ]   = $spouses_data->fname = $userInfo->sp_first_name;
		$ctxphc_sp_meta_data[ 'ctxphc_sp_last_name' ]    = $spouses_data->lname = $userInfo->sp_last_name;
		$ctxphc_sp_meta_data[ 'ctxphc_sp_phone' ]        = $spouses_data->phone = $userInfo->sp_phone;
		$ctxphc_sp_meta_data[ 'ctxphc_sp_relationship' ] = $spouses_data->rel_id = $userInfo->sp_relationship_id;
		$ctxphc_sp_meta_data[ 'ctxphc_sp_birthdate' ]    = $spouses_data->birthday = $userInfo->sp_birthday;
	}

	foreach ( $ctxphc_sp_meta_data as $meta_key => $meta_value ) {
		//update_user_meta( $sp_id, $meta_key, $meta_value );
	}

	return $spouses_data;
}

function load_child_data( $userInfo ) {

	$childs_data = new stdClass();

	$x = 1;
	while ( $x <= 4 ) {
		if ( isset( ${"userInfo->c" . $x . "_id"} ) && ${"userInfo->c" . $x . "_id"} !== $userInfo->ID ) {  //current registration

			$sub_memb_id   = ${"userInfo->c" . $x . "_id"};
			$sub_memb_data = get_userdata( $sub_memb_id );

			$childs_data->{"c" . $x . "_id"}              = $sub_memb_id;
			$childs_data->{"c" . $x . "_fname"}           = $sub_memb_data->first_name;
			$childs_data->{"c" . $x . "_lname"}           = $sub_memb_data->last_name;
			$childs_data->{"c" . $x . "_bday"}            = $sub_memb_data->birthday;
			$childs_data->{"c" . $x . "_phone"}           = $sub_memb_data->phone;
			$childs_data->{"c" . $x . "_email"}           = $sub_memb_data->email;
			$childs_data->{"c" . $x . "_relationship_id"} = $sub_memb_data->relationship_id;

		} elseif ( isset( ${"userInfo->fam_memb_id_" . $x} ) && ${"fam_memb_id_" . $x} !== $userInfo->ID ) {  //todo: correct to registration standard
			$sub_memb_id   = ${"userInfo->fam_" . $x . "_wp_user_id"};
			$sub_memb_data = get_userdata( $sub_memb_id );

			$childs_data->{"c" . $x . "_id"}              = $sub_memb_id;
			$childs_data->{"c" . $x . "_fname"}           = $sub_memb_data->first_name;
			$childs_data->{"c" . $x . "_lname"}           = $sub_memb_data->last_name;
			$childs_data->{"c" . $x . "_bday"}            = $sub_memb_data->birthday;
			$childs_data->{"c" . $x . "_phone"}           = $sub_memb_data->phone;
			$childs_data->{"c" . $x . "_email"}           = $sub_memb_data->email;
			$childs_data->{"c" . $x . "_relationship_id"} = $sub_memb_data->relationship_id;

		} elseif ( isset( $userInfo->{"fam_" . $x . "_first_name"} ) && ( ! empty( $userInfo->{"fam_" . $x . "_first_name"} ) ) ) {  //todo:
			// correct to
			// registration standard

			$childs_data->{"c" . $x . "_fname"}           = $userInfo->${"fam_" . $x . "_first_name"};
			$childs_data->{"c" . $x . "_lname"}           = $userInfo->${"fam_" . $x . "_last_name"};
			$childs_data->{"c" . $x . "_bday"}            = $userInfo->${"fam_" . $x . "_birthday"};
			$childs_data->{"c" . $x . "_phone"}           = $userInfo->${"fam_" . $x . "_phone"};
			$childs_data->{"c" . $x . "_email"}           = $userInfo->${"fam_" . $x . "_email"};
			$childs_data->{"c" . $x . "_relationship_id"} = $userInfo->${"fam_" . $x . "_relationship_id"};

		} elseif ( $userInfo->{"c" . $x . "_first_name"} && ( ! empty( $userInfo->{"c" . $x . "_first_name"} ) ) ) {  //current registration

			$childs_data->{"c" . $x . "_fname"}  = $userInfo->${"c" . $x . "_first_name"};
			$childs_data->{"c" . $x . "_lname"}  = $userInfo->${"c" . $x . "_last_name"};
			$childs_data->{"c" . $x . "_bday"}   = $userInfo->${"c" . $x . "_birthday"};
			$childs_data->{"c" . $x . "_phone"}  = $userInfo->${"c" . $x . "_phone"};
			$childs_data->{"c" . $x . "_email"}  = $userInfo->${"c" . $x . "_email"};
			$childs_data->{"c" . $x . "_rel_id"} = $userInfo->${"c" . $x . "_relationship_id"};
		}
		$x ++;
	}

	return $childs_data;
}

function format_save_phone( $phone_number ) {
	return preg_replace( '/[^0-9]/', '', $phone_number );
}

function formatPhoneNumber( $phoneNumber ) {
	$phoneNumber = preg_replace( '/[^0-9]/', '', $phoneNumber );

	if ( strlen( $phoneNumber ) > 10 ) {
		$countryCode = substr( $phoneNumber, 0, strlen( $phoneNumber ) - 10 );
		$areaCode    = substr( $phoneNumber, - 10, 3 );
		$nextThree   = substr( $phoneNumber, - 7, 3 );
		$lastFour    = substr( $phoneNumber, - 4, 4 );

		$phoneNumber = '+' . $countryCode . ' (' . $areaCode . ') ' . $nextThree . '-' . $lastFour;
	} else if ( strlen( $phoneNumber ) == 10 ) {
		$areaCode  = substr( $phoneNumber, 0, 3 );
		$nextThree = substr( $phoneNumber, 3, 3 );
		$lastFour  = substr( $phoneNumber, 6, 4 );

		$phoneNumber = '(' . $areaCode . ') ' . $nextThree . '-' . $lastFour;
	} else if ( strlen( $phoneNumber ) == 7 ) {
		$nextThree = substr( $phoneNumber, 0, 3 );
		$lastFour  = substr( $phoneNumber, 3, 4 );

		$phoneNumber = $nextThree . '-' . $lastFour;
	}

	return $phoneNumber;
}

/**
 * @param $date
 *
 * @return string
 */
function is_date_safe( $date ) {
	global $memb_error;

	$date        = new DateTime( $date );
	$fixed_date  = $date->format( 'Y-m-d' );
	$date_fields = explode( '-', $fixed_date );

	$safe_date = checkdate( $date_fields[ 1 ], $date_fields[ 2 ], $date_fields[ 0 ] );
	if ( $safe_date ) {
		$fixed_safe_date = sprintf( "%s-%02s-%02s", $date_fields[ 0 ], $date_fields[ 1 ], $date_fields[ 2 ] );
	} else {
		$memb_error->add( 'date', "The date was not valid.  This needs to be checked out.  The data will be stored for further review in the failed_registration table." );
		$fixed_safe_date = $memb_error->get_error_message( 'date' );
	}

	return $fixed_safe_date;
}

/**
 * @return mixed
 */
function process_registration() {
	global $memb_error, $prime_members_id, $user_error_type;

	$current_date = date( "Y-m-d" );
	$renewal_date = get_renewal_date();

	$cleaned_userdata = get_clean_POST_userdata();
	$cleaned_usermeta = get_clean_POST_usermeta();


	foreach ( $cleaned_userdata as $memb_user_key => $memb_data_obj ) {
		$memb_id = add_wordpress_user( $memb_data_obj );

		if ( is_wp_error( $memb_id ) ) {
			error_log_message( $memb_id->get_error_message(), $user_error_type[ 'warning' ] );
			$memb_id                                        = null;
			$cleaned_usermeta->$memb_user_key->hatch_date   = is_date_safe( $current_date );
			$cleaned_usermeta->$memb_user_key->renewal_date = is_date_safe( $renewal_date );
			$cleaned_usermeta->$memb_user_key->status_id    = 0; // new member registration defaults to pending
		} else {
			$cleaned_userdata->$memb_user_key->wp_id        = $memb_id;
			$cleaned_usermeta->$memb_user_key->hatch_date   = is_date_safe( $current_date );
			$cleaned_usermeta->$memb_user_key->renewal_date = is_date_safe( $renewal_date );
			$cleaned_usermeta->$memb_user_key->status_id    = 0; // new member registration defaults to pending
		}

		if ( 'mb' == $memb_user_key ) {
			if ( is_null( $prime_members_id ) ) {
				$prime_members_id = $memb_id;
			}
		} else {
			// Add primary Member's Wordpress user id to each family member with a Wordpress user id.
			$cleaned_usermeta->$memb_user_key->mb_id = $prime_members_id;
		}
	}

	//$i = 0;
	foreach ( $cleaned_usermeta as $member_key => $member_obj ) {
		$user_id   = $member_obj->wp_id;
		$result_am = add_members_metadata( $member_obj, $user_id );
		if ( is_wp_error( $result_am ) ) {
			error_log_message( $result_am->get_error_message(), $user_error_type[ 'warning' ] );
		}
	}

	return $cleaned_usermeta;
}


/**
 * @param $current_memb_id
 *
 * @return int|WP_Error
 */
/** @noinspection PhpInconsistentReturnPointsInspection */
function process_update_metadata() {
	global $memb_error;
	$cleaned_userdata = get_clean_POST_userdata();
	$cleaned_usermeta = get_clean_POST_usermeta();


	//check form data against existing metadata to determine if data needs to be updated
	foreach ( $cleaned_usermeta as $member_key => $member_meta_array ) {
		$current_memb_id = $_POST[ $member_key . '_id' ];
		foreach ( $member_meta_array as $member_meta_key => $member_meta_value ) {
			compare_form_data_value( $current_memb_id, $member_meta_key, $member_meta_value );
		}
		$clean_form_data[ 'usermeta' ] = $cleaned_usermeta;
	}

	foreach ( $cleaned_userdata as $member_key => $member_user_obj ) {
		$current_memb_id = $_POST[ $member_key . '_id' ];

		//Get existing Wordpress user data
		$user_info = get_userdata( $current_memb_id );
		if ( ! $user_info ) {
			$memb_error->add( 'get userdata failed', "There was a failure when getting userdata for: $current_memb_id" );
			error_log_message( $memb_error->get_error_message(), $user_error_type( 'warning' ) );
		} else {
			foreach ( $member_user_obj as $user_data_key => $user_data_value ) {
				compare_form_userdata( $user_data_key, $user_data_value, $user_info, $current_memb_id );
			}

			$clean_form_data[ 'userdata' ] = $cleaned_userdata;
		}
	}

	return $clean_form_data;
}

function compare_form_userdata( $user_data_key, $user_data_value, $user_info, $current_memb_id ) {
	global $memb_error;

	$keyval     = null;
	$key_fields = explode( '_', $user_data_key );
	if ( is_array( $key_fields ) ) {
		$i = 0;
		while ( $i < count( $key_fields ) ) {
			$keyval .= $key_fields[ $i ++ ];
		}
	}
	$user_key = 'user_' . $keyval;
	if ( $user_data_value != $user_info->$user_key ) {
		$result = wp_update_user( array( 'ID' => $current_memb_id, $user_key => $user_data_value ) );

		if ( is_wp_error( $result ) ) {
			$memb_error->add( 'user_data_update', "There was a failure when getting userdata for: $current_memb_id" );
			error_log_message( $memb_error->get_error_message(), $user_error_type( 'warning' ) );
		}
	}
}


function get_clean_POST_userdata() {
	/**
	 * Clean up input data for insertion into the Wordpress User Meetadata table.
	 *
	 * @var $cm_metadata
	 * @var $cm_userdata
	 *
	 **/
	global /** @noinspection PhpUnusedLocalVariableInspection */
	$user_error_type;
	if ( ! isset( $cm_metadata ) ) {
		$cm_userdata = new stdClass();
	} else {
		$cm_userdata = reset_Object( $cm_userdata );
	}

	$clean_userdata = new stdClass();

	$cm_userdata->first_name = $_POST[ 'mb_first_name' ];
	$cm_userdata->last_name  = $_POST[ 'mb_last_name' ];
	$cm_userdata->email      = $_POST[ 'mb_email' ];

	$clean_userdata->mb = clean_user_data( $cm_userdata );

	if ( isset( $_POST[ 'sp_first_name' ] ) && ! empty( $_POST[ 'sp_first_name' ] ) ) {
		$cm_userdata->first_name = $_POST[ 'sp_first_name' ];
		$cm_userdata->last_name  = $_POST[ 'sp_last_name' ];
		$cm_userdata->email      = $_POST[ 'sp_email' ];

		$clean_userdata->sp = clean_user_data( $cm_userdata );
	}

	if ( isset( $_POST[ 'c1_first_name' ] ) && ! empty( $_POST[ 'c1_first_name' ] ) ) {
		$x = 1;
		while ( $x <= 4 ) {
			$c_key = 'c' . $x;
			$x ++;
			$cm_userdata->first_name = $_POST[ $c_key . '_first_name' ];
			$cm_userdata->last_name  = $_POST[ $c_key . '_last_name' ];
			$cm_userdata->email      = $_POST[ $c_key . '_email' ];

			$clean_userdata->$c_key = clean_user_data( $cm_userdata );
		}
	}

	return $clean_userdata;
}

function clean_user_data( $cm_userdata ) {

	reset_Object( $cm_userdata );

	$clean_userdata = new stdClass();

	return $clean_userdata;
}

function get_clean_POST_usermeta() {
	/**
	 * Clean up input data for insertion into the Wordpress User Meetadata table.
	 *
	 * @var $member_data
	 *
	 **/
	$current_date = date( "Y-m-d" );
	$renewal_date = get_renewal_date();

	$cleaned_metadata = new stdClass();

	if ( ! isset( $cm_metadata ) ) {
		$cm_metadata = new stdClass();
	} else {
		$cm_metadata = reset_Object( $cm_metadata );
	}


	$cm_metadata->phone        = $_POST[ 'mb_phone' ];
	$cm_metadata->birthday     = $_POST[ 'mb_birthday' ];
	$cm_metadata->occupation   = $_POST[ 'mb_occupation' ];
	$cm_metadata->rel_id       = $_POST[ 'mb_relationship' ];
	$cm_metadata->memb_type    = $_POST[ 'memb_type' ];
	$cm_metadata->addr1        = $_POST[ 'mb_addr1' ];
	$cm_metadata->addr2        = $_POST[ 'mb_addr2' ];
	$cm_metadata->city         = $_POST[ 'mb_city' ];
	$cm_metadata->state        = $_POST[ 'mb_state' ];
	$cm_metadata->zip          = $_POST[ 'mb_zip' ];
	$cm_metadata->hatch_date   = is_date_safe( $current_date );
	$cm_metadata->renewal_date = is_date_safe( $renewal_date );
	$cm_metadata->status_id    = 0; // new member registration defaults to pending

	$cleaned_metadata->mb = clean_meta_data( $cm_metadata );

	if ( isset( $_POST[ 'sp_first_name' ] ) && ! empty( $_POST[ 'sp_first_name' ] ) ) {
		$cm_metadata->phone    = $_POST[ 'sp_phone' ];
		$cm_metadata->birthday = is_date_safe( $_POST[ 'sp_birthday' ] );
		$cm_metadata->rel_id   = ( isset( $_POST[ 'mb_relationship' ] ) ? intval( $_POST[ 'mb_relationship' ] ) : 1 );

		$cleaned_metadata->sp = clean_meta_data( $cm_metadata );
	}

	if ( isset( $_POST[ 'c1_first_name' ] ) && ! empty( $_POST[ 'c1_first_name' ] ) ) {
		$x = 1;
		while ( $x <= 4 ) {
			$c_key = $x;
			$x ++;
			$cm_metadata->phone    = $_POST[ $c_key . '_phone' ];
			$cm_metadata->birthday = is_date_safe( $_POST[ $c_key . '_birthday' ] );
			$cm_metadata->rel_id   = ( isset( $_POST[ $c_key . '_relationship' ] ) ? intval( $_POST[ $c_key . '_relationship' ] ) : 1 );

			$cleaned_metadata->c{$x} = clean_meta_data( $cm_metadata );
		}
	}

	return $cm_metadata;
}

function clean_meta_data( $data ) {
	$count = count( get_object_vars( $data ) );
	if ( ! isset( $clean_metadata ) ) {
		$clean_metadata = new stdClass();
	} else {
		$clean_metadata = reset_Object( $clean_metadata );
	}


	$clean_metadata->phone        = $data->phone;
	$clean_metadata->birthday     = is_date_safe( $data->birthday );
	$clean_metadata->rel_id       = ( isset( $data->rel_id ) ? intval( $data->rel_id ) : 1 );
	$clean_metadata->hatch_date   = is_date_safe( $data->hatch_date );
	$clean_metadata->renewal_date = is_date_safe( $data->renewal_date );

	if ( $count > 5 ) {
		$clean_metadata->occupation      = sanitize_text_field( $data->occupation );
		$clean_metadata->membership_type = intval( $data->memb_type );
		$clean_metadata->addr1           = sanitize_text_field( $data->addr1 );
		$clean_metadata->addr2           = sanitize_text_field( $data->addr2 );
		$clean_metadata->city            = sanitize_text_field( $data->city );
		$clean_metadata->state           = sanitize_text_field( $data->state );
		$clean_metadata->zip             = sanitize_text_field( $data->zip );
	}
	reset_Object( $data );

	return $clean_metadata;
}

function reset_Object( $obj ) {
	foreach ( $obj as $key => $value ) {
		unset( $obj->$key );
	}

	return $obj;
}

/**
 * @param $member
 * @param $user_id
 *
 * @return bool|int|null|string
 */
function add_members_metadata( $member, $user_id ) {
	global $memb_error;
	$result = null;
	foreach ( $member as $meta_key => $meta_value ) {
		$result = update_user_meta( $user_id, $meta_key, $meta_value );

		if ( is_wp_error( $result ) ) {
			error_log_message( $memb_error->get_error_message(), $user_error_type[ 'warning' ] );
			$result = $memb_error->get_error_message();
		}
	}

	return $result;
}

function update_members_metadata( $user_id, $meta_key, $meta_value, $prev_value ) {
	global $memb_error;
	$result = update_user_meta( $user_id, $meta_key, $meta_value, $prev_value );

	if ( false == $result ) {
		$memb_error->add( 'meta_update', "Metadata update failed:  $user_id, $meta_key, $meta_value, $prev_value" );
		error_log_message( $memb_error->get_error_message(), $user_error_type[ 'warning' ] );
	} elseif ( true == $result ) {
		$memb_error->add( 'meta_update', 'The metadata was updated! ' );
		error_log_message( $memb_error->get_error_message(), $user_error_type[ 'warning' ] );
	} else {
		$memb_error->add( 'meta_update', 'The metadata did not exist and was added.' );
		error_log_message( $memb_error->get_error_message(), $user_error_type[ 'warning' ] );
	}

	return $result;
}


function compare_form_data_value( $user_id, $meta_key, $form_value ) {
	global $memb_error, $user_error_type;
	// Verify the stored value matches new or updated value
	$verify_value = get_user_meta( $user_id, $meta_key, true );
	if ( $verify_value != $form_value ) {
		$memb_error->add( 'compare_data', "The form data did not match the metadata: $user_id, $meta_key, $form_value, $verify_value" );
		error_log_message( $memb_error->get_error_message(), $user_error_type[ 'warning' ] );
		$result = update_members_metadata( $user_id, $meta_key, $form_value, $verify_value );
		if ( $result != false ) {
			$result = true;
		}

	} else {
		$result = true;
	}

	return $result;
}

function verify_userdata_value( $user_id, $meta_key, $meta_value ) {
	$result = null;
	global $memb_error;
	// Verify the stored value matches new or updated value
	$user_info = get_userdata( $user_id );
	if ( ! $user_info ) {
		$memb_error->add( 'get userdata failed', "There was a failure when getting userdata for: $user_id" );
		$result = $memb_error;
	}

	$existing_value = $user_info->$meta_key;

	if ( $meta_value == $existing_value ) {
		$result = false;
	}

	return $result;
}

/**
 * @param $member
 *
 * @return int|string
 */
function add_wordpress_user( $member ) {
	global /** @noinspection PhpUnusedLocalVariableInspection */
	$memb_error, $user_error_type;

	if ( ! empty( $member->email ) ) {
		foreach ( $member as $mkey => $mval ) {
			$emessage = $mkey . ':->:' . $mval;
			error_log_message( $emessage, $user );
		}
		$userdata = array(
			'first_name'      => $member->first_name,
			'last_name'       => $member->last_name,
			'user_email'      => $member->email,
			'user_login'      => ( isset( $member->username ) ? $member->username : mb_strtolower( substr( $member->first_name, 0, 3 ) . substr( $member->last_name, 0, 4 ) ) ),
			'user_pass'       => ( isset( $member->pass ) ? $member->pass : wp_generate_password( $length = 12, $include_standard_special_chars = false ) ),
			'nickname'        => $member->first_name . ' ' . $member->last_name,
			'display_name'    => $member->first_name . ' ' . $member->last_name,
			'user_nicename'   => $member->first_name . '-' . $member->last_name,
			'user_registered' => $member->reg_date,
		);

		$memb_id = wp_insert_user( $userdata );
	} else {
		$memb_error->add( 'no_email', 'Without an email address we cannot create a wordpress user account.' );
		$memb_id = $memb_error;
	}

	return $memb_id;
}

function get_clean_usermeta_data( $user_id ) {
	$clean_user_metadata = array_map( function ( $a ) {
		return $a[ 0 ];
	}, get_user_meta( $user_id ) );

	return $clean_user_metadata;
}

/**
 * @param $message
 * @param string $error_type
 * @param bool $debug
 */
function debug_log_message( $message, $error_type = 'E_USER_NOTICE', $debug = false ) {
	if ( $debug ) {
		user_error( "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!", $error_type );
		user_error( "!!!!!!   $message   !!!!!!", $error_type );
		user_error( "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!", $error_type );
	}
}

/* Error Handling Functions */
function kaosWarning( $errNo, $errStr, $errFile, $errLine ) {
	echo( "Custom PHP Warning : " . $errNo . "<br>" );
	echo( "Message : " . $errStr . "<br>" );
	echo( "Location : " . $errFile . "<br>" );
	echo( "Line Number : " . $errLine . "<br>" );

	error_log( "Message : " . $errStr );
	error_log( "Location : " . $errFile . " | Line Number : " . $errLine );
	error_log( "  " );
}

function kaosError( $errNo, $errStr, $errFile, $errLine ) {
	echo( "Custom PHP Warning : " . $errNo . "<br>" );
	echo( "Message : " . $errStr . "<br>" );
	echo( "Location : " . $errFile . "<br>" );
	echo( "Line Number : " . $errLine . "<br>" );
}


/**
 * @param $message
 *
 * @param string $error_type
 *
 * @internal param $debug
 */
function error_log_message( $message, $error_type = 256 ) {

	$e_log_separator = "#############################################################";

	error_log( $e_log_separator, 0 );
	error_log( "######   $message   ######", 0 );
	error_log( $e_log_separator, 0 );
}


//Shortcodes to be used in pages, posts, widgets etc.
//To be added to a paypal canceled payment return page
function canceled_paypal_payment() {
	//todo: create code to do something with the user data if they cancel the registration or renewal at the paypal payment screen.
}


function new_member_paypal_welcome_processing() {
	//todo: create code needed to activate a new members registration. Including creating the wordpress user account an adding the registration data to the user_metadata
}

function register_membership_shortcodes() {
	add_shortcode( 'payment_canceled', 'canceled_paypal_payment' );
	add_shortcode( 'cloak', 'email_cloaking_shortcode' );
	add_shortcode( 'payment_completed', 'new_member_paypal_welcome_processing' );
}

add_action( 'init', 'register_membership_shortcodes' );

function list_active_members() {
	// WP_User_Query arguments
	$args = array(
		'role'       => 'Subscriber',
		'number'     => '25',
		'order'      => 'ASC',
		'orderby'    => 'user_login',
		'meta_query' => array(
			array(
				'key'     => 'hatch_date',
				'compare' => 'EXISTS',
				'type'    => 'DATETIME',
			),
		),
		'fields'     => array( 'first_name', 'last_name', 'email', 'phone', 'addr1', 'addr2', 'city', 'state', 'zip' ),
	);

	// The User Query
	$user_query = new WP_User_Query( $args );

	return $user_query;
}

add_shortcode( 'list_members', 'list_active_members' );

// Register User Contact Methods that are displayed in a users profile.
function member_contact_methods( $member_contact_method ) {

	$member_contact_method[ 'address' ]  = __( 'Address', 'text_domain' );
	$member_contact_method[ 'city' ]     = __( 'City', 'text_domain' );
	$member_contact_method[ 'state' ]    = __( 'State', 'text_domain' );
	$member_contact_method[ 'zip' ]      = __( 'Zip', 'text_domain' );
	$member_contact_method[ 'Phone' ]    = __( 'Phone', 'text_domain' );
	$member_contact_method[ 'twitter' ]  = __( 'Twitter Username' );
	$member_contact_method[ 'facebook' ] = __( 'Facebook Username' );
	$member_contact_method[ 'yahoo' ]    = __( 'YAHOO Groups Username' );

	return $member_contact_method;
}

// Hook into the 'user_contactmethods' filter
add_filter( 'user_contactmethods', 'member_contact_methods' );

function load_states_array() {
	$states_arr = array(
		'AL' => "Alabama",
		'AK' => "Alaska",
		'AZ' => "Arizona",
		'AR' => "Arkansas",
		'CA' => "California",
		'CO' => "Colorado",
		'CT' => "Connecticut",
		'DE' => "Delaware",
		'DC' => "District Of Columbia",
		'FL' => "Florida",
		'GA' => "Georgia",
		'HI' => "Hawaii",
		'ID' => "Idaho",
		'IL' => "Illinois",
		'IN' => "Indiana",
		'IA' => "Iowa",
		'KS' => "Kansas",
		'KY' => "Kentucky",
		'LA' => "Louisiana",
		'ME' => "Maine",
		'MD' => "Maryland",
		'MA' => "Massachusetts",
		'MI' => "Michigan",
		'MN' => "Minnesota",
		'MS' => "Mississippi",
		'MO' => "Missouri",
		'MT' => "Montana",
		'NE' => "Nebraska",
		'NV' => "Nevada",
		'NH' => "New Hampshire",
		'NJ' => "New Jersey",
		'NM' => "New Mexico",
		'NY' => "New York",
		'NC' => "North Carolina",
		'ND' => "North Dakota",
		'OH' => "Ohio",
		'OK' => "Oklahoma",
		'OR' => "Oregon",
		'PA' => "Pennsylvania",
		'RI' => "Rhode Island",
		'SC' => "South Carolina",
		'SD' => "South Dakota",
		'TN' => "Tennessee",
		'TX' => "Texas",
		'UT' => "Utah",
		'VT' => "Vermont",
		'VA' => "Virginia",
		'WA' => "Washington",
		'WV' => "West Virginia",
		'WI' => "Wisconsin",
		'WY' => "Wyoming",
	);

	return $states_arr;
}

function load_states_array_by_num() {
	$states_arr = array(
		'AL' => "Alabama",
		'AK' => "Alaska",
		'AZ' => "Arizona",
		'AR' => "Arkansas",
		'CA' => "California",
		'CO' => "Colorado",
		'CT' => "Connecticut",
		'DE' => "Delaware",
		'DC' => "District Of Columbia",
		'FL' => "Florida",
		'GA' => "Georgia",
		'HI' => "Hawaii",
		'ID' => "Idaho",
		'IL' => "Illinois",
		'IN' => "Indiana",
		'IA' => "Iowa",
		'KS' => "Kansas",
		'KY' => "Kentucky",
		'LA' => "Louisiana",
		'ME' => "Maine",
		'MD' => "Maryland",
		'MA' => "Massachusetts",
		'MI' => "Michigan",
		'MN' => "Minnesota",
		'MS' => "Mississippi",
		'MO' => "Missouri",
		'MT' => "Montana",
		'NE' => "Nebraska",
		'NV' => "Nevada",
		'NH' => "New Hampshire",
		'NJ' => "New Jersey",
		'NM' => "New Mexico",
		'NY' => "New York",
		'NC' => "North Carolina",
		'ND' => "North Dakota",
		'OH' => "Ohio",
		'OK' => "Oklahoma",
		'OR' => "Oregon",
		'PA' => "Pennsylvania",
		'RI' => "Rhode Island",
		'SC' => "South Carolina",
		'SD' => "South Dakota",
		'TN' => "Tennessee",
		'TX' => "Texas",
		'UT' => "Utah",
		'VT' => "Vermont",
		'VA' => "Virginia",
		'WA' => "Washington",
		'WV' => "West Virginia",
		'WI' => "Wisconsin",
		'WY' => "Wyoming",
	);

	return $states_arr;
}

function load_relationships_array() {
	$relationship_arr = array( '2' => "Spouse", '3' => "Partner", '4' => "Child", '5' => "Other" );

	return $relationship_arr;
}

add_filter( 'body_class', 'browser_body_class' );
/**
 * @param $classes
 *
 * @return array
 */
function browser_body_class( $classes ) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	if ( $is_lynx ) {
		$classes[] = 'lynx';
	} elseif ( $is_gecko ) {
		$classes[] = 'gecko';
	} elseif ( $is_opera ) {
		$classes[] = 'opera';
	} elseif ( $is_NS4 ) {
		$classes[] = 'ns4';
	} elseif ( $is_safari ) {
		$classes[] = 'safari';
	} elseif ( $is_chrome ) {
		$classes[] = 'chrome';
	} elseif ( $is_IE ) {
		$classes[] = 'ie';
	} else {
		$classes[] = 'unknown';
	}

	if ( $is_iphone ) {
		$classes[] = 'iphone';
	}

	return $classes;
}

function load_new_form_fields( $curr_memb, $spouse_data, $child_data ) {
	global $user_error_type;
	if ( is_object( $curr_memb ) ) {
		$emessage = 'Begin Loading Updating and loading new Member data';
		error_log_message( $emessage, $user_error_type[ 'warning' ] );
		foreach ( $curr_memb as $curr_memb_key => $curr_memb_value ) {
			$emessage = $curr_memb_key . ': => :' . $curr_memb_value;
			error_log_message( $emessage, $user_error_type[ 'warning' ] );
		}
	}

	if ( is_object( $spouse_data ) ) {
		$emessage = 'Begin Loading Updating and loading new Spouse data';
		error_log_message( $emessage, $user_error_type[ 'warning' ] );

		foreach ( $spouse_data as $sp_data_key => $sp_data_value ) {
			$emessage = $sp_data_key . ': => :' . $sp_data_value;
			error_log_message( $emessage, $user_error_type[ 'warning' ] );
		}
	}

	if ( is_object( $child_data ) ) {
		$emessage = 'Begin Loading Updating and loading new Child data';
		error_log_message( $emessage, $user_error_type[ 'warning' ] );

		foreach ( $child_data as $child_data_key => $child_data_value ) {
			$emessage = $child_data_key . ': => :' . $child_data_value;
			error_log_message( $emessage, $user_error_type[ 'warning' ] );
		}
	}
}


//Used for testing only
if ( $debug ) {
	add_filter( 'gform_pre_render_3', 'walk_through_form_fields' );
	add_action( 'gform_after_submission', 'get_current_entry_data', 10, 2 );
}

add_filter( 'ws_plugin__s2member_lock_roles_caps', '__return_true' );

function walk_through_form_fields( $form ) {

	foreach ( $form[ 'fields' ] as &$field ) {

		$field_id = $field->id;
		error_log_message( 'id: ' . $field_id . ' -> label: ' . $field->label );
		error_log_message( 'id: ' . $field_id . ' -> name: ' . $field->name );
		error_log_message( 'id: ' . $field_id . ' -> inputName: ' . $field->inputName );
		error_log_message( 'id: ' . $field_id . ' -> text: ' . $field->text );
		error_log_message( 'id: ' . $field_id . ' -> value: ' . $field->value );

		$inputs = $field->inputs;
		if ( ! empty( $inputs ) ) {

			for ( $i = 0, $input_count = count( $inputs ); $i < $input_count; $i ++ ) {
				$input_fields = $inputs[ $i ];
				$infield_id   = $input_fields->id;

				for ( $ii = 0, $infield_count = count( $input_fields[ $i ] ); $ii < $infield_count; $ii ++ ) {
					$infield = $input_fields[ $i ];
					error_log_message( 'id: ' . $infield_id . ' -> label: ' . $infield->label );
				}
			}
		}
	}

	return $form;
}

function get_current_entry_data( $form ) {
	if ( $submit = 'registration' ) {
		$form_id = 1;
	} else {
		$form_id = 5;
	}


	//Get all entries for registration or renewal forms
	$entries = GFAPI::get_entries( $form_id );

	//TODO: get the most recent entry_id
	foreach ( $entries as $entry ) {

	}
	//Get the most recent entry
	$entry = GFAPI::get_entry( $entry_id );

	//get all entry data
	$field_data = array(
		$date_created => rgar( $entry, 'date_created' ), // returns the entry date
		$field_1      => rgar( $entry, '1' ),    // returns the value associated with field 1
		$field_2      => rgar( $entry, '1.3' ),  // returns the value associated with the first name portion of a simple name field 1
		$field_3      => rgar( $entry, '1.6' ),  // returns the value associated with the last name portion of a simple name field 1
		$field_4      => rgar( $entry, '2.4' ),  // returns the value associated with the state input for the address field 2
	);

}

function kaos_get_meta_names( $form ) {
	$meta_names = array();
	/** @noinspection PhpUnusedLocalVariableInspection */
	foreach ( $form[ 'fields' ] as &$field ) {

		$meta_names[ 'member' ] = array(
			'ctxphc_memb_type',
			'ctxphc_memb_first_name',
			'ctxphc_memb_last_name',
			'ctxphc_memb_username',
			'ctxphc_memb_email',
			'ctxphc_memb_phone',
			'ctxphc_memb_occupation',
			'ctxphc_memb_birthdate',
			'ctxphc_memb_relationship',
			'ctxphc_memb_addr1',
			'ctxphc_memb_addr2',
			'ctxphc_memb_city',
			'ctxphc_memb_state',
			'ctxphc_memb_zip',
		);

		$meta_names[ 'spouse' ] = array(
			'ctxphc_sp_first_name',
			'ctxphc_sp_last_name',
			'ctxphc_sp_email',
			'ctxphc_sp_phone',
			'ctxphc_sp_birthdate',
			'ctxphc_sp_relationship',
		);

		$meta_names[ 'child_1' ] = array(
			'ctxphc_c1_first_name',
			'ctxphc_c1_last_name',
			'ctxphc_c1_email',
			'ctxphc_c1_phone',
			'ctxphc_c1_birthdate',
			'ctxphc_c1_relationship',
		);

		$meta_names[ ' child_2' ] = array(
			'ctxphc_c2_first_name',
			'ctxphc_c2_last_name',
			'ctxphc_c2_email',
			'ctxphc_c2_phone',
			'ctxphc_c2_birthdate',
			'ctxphc_c2_relationship',
		);

		$meta_names[ 'child_3' ] = array(
			'ctxphc_c3_first_name',
			'ctxphc_c3_last_name',
			'ctxphc_c3_email',
			'ctxphc_c3_phone',
			'ctxphc_c3_birthdate',
			'ctxphc_c3_relationship',
		);

		$meta_names[ 'child_4' ] = array(
			'ctxphc_c4_first_name',
			'ctxphc_c4_last_name',
			'ctxphc_c4_email',
			'ctxphc_c4_phone',
			'ctxphc_c4_birthdate',
			'ctxphc_c4_relationship',
		);
	}
}

function kaos_get_entry_ids() {
	$entry_ids[ 'member' ] = array( '48', '43.3', '43.6', '24', '6', '7', '23', '5', '49', '10.1', '10.2', '10.3', '10.4', '10.5', );

	$entry_ids[ 'spouse' ] = array( '12.3', '12.6', '13', '14', '15', '22', );

	$entry_ids[ 'child_1' ] = array( '17.3', '17.6', '18', '20', '33', '39', );

	$entry_ids[ 'child_2' ] = array( '27.3', '27.6', '31', '35', '32', '40', );

	$entry_ids[ 'child_3' ] = array( '26.3', '26.6', '30', '36', '19', '21', );

	$entry_ids[ 'child_4' ] = array( '25.3', '25.6', '29', '37', '34', '38', );
}

//add_action( 'gform_after_submission_3', 'kaos_process_membership_renewal', 10, 2 );

function kaos_process_membership_renewal( $entry, $form ) {

	$renewal_fields = array();
	/** @noinspection PhpUnusedLocalVariableInspection */
	foreach ( $form[ 'fields' ] as &$field ) {

		$renewal_fields[ 'member' ] = array(
			'48'   => 'user_memb_type',
			'43.3' => 'first_name',
			'43.6' => 'last_name',
			'24'   => 'username',
			'6'    => 'email',
			'7'    => 'phone',
			'23'   => 'occupation',
			'5'    => 'birthdate',
			'49'   => 'relationship',
			'10.1' => 'addr1',
			'10.2' => 'addr2',
			'10.3' => 'city',
			'10.4' => 'state',
			'10.5' => 'zip',
		);

		$renewal_fields[ 'spouse' ] = array(
			'12.3' => 'sp_first_name',
			'12.6' => 'sp_last_name',
			'13'   => 'sp_email',
			'14'   => 'sp_phone',
			'15'   => 'sp_birthdate',
			'22'   => 'sp_relationship',
		);

		$renewal_fields[ 'child_1' ] = array(
			'17.3' => 'c1_first_name',
			'17.6' => 'c1_last_name',
			'18'   => 'c1_email',
			'20'   => 'c1_phone',
			'33'   => 'c1_birthdate',
			'39'   => 'c1_relationship',
		);

		$renewal_fields[ ' child_2' ] = array(
			'27.3' => 'c2_first_name',
			'27.6' => 'c2_last_name',
			'31'   => 'c2_email',
			'35'   => 'c2_phone',
			'32'   => 'c2_birthdate',
			'40'   => 'c2_relationship',
		);

		$renewal_fields[ 'child_3' ] = array(
			'26.3' => 'c3_first_name',
			'26.6' => 'c3_last_name',
			'30'   => 'c3_email',
			'36'   => 'c3_phone',
			'19'   => 'c3_birthdate',
			'21'   => 'c3_relationship',
		);

		$renewal_fields[ 'child_4' ] = array(
			'25.3' => 'c4_first_name',
			'25.6' => 'c4_last_name',
			'29'   => 'c4_email',
			'37'   => 'c4_phone',
			'34'   => 'c4_birthdate',
			'38'   => 'c4_relationship',
		);

		$something = rgar( $entry, '17.3' );
	}

	$user_id = get_current_user_id();
	//todo: load data from wordpress.
	kaos_insert_renewal_data( $renewal_fields, $user_id );
}

function kaos_insert_renewal_data( $renewal_data, $entry_ids ) {
	$current_user = wp_get_current_user();
	$user_id      = $current_user->id;
	$membtypes    = kaos_get_memb_types();

	foreach ( $membtypes as $membtype ) {
		foreach ( $entry_ids[ $membtype ] as $entry_id ) {
			foreach ( $renewal_data[ $membtype ] as $meta_key => $meta_value ) {
				if ( ! empty( $meta_value ) ) {
					gform_update_meta( $entry_id, $meta_key, $meta_value );
					update_user_meta( $user_id, $meta_key, $meta_value );
				}
			}
		}
	}
}

function kaos_get_memb_types() {
	$membtypes = array(
		'member_fields',
		'spouse_fields',
		'child_1_fields',
		'child_2_fields',
		'child_3_fields',
		'child_4_fields',
	);

	return $membtypes;
}

function kaos_get_renewal_form_fields() {
	// define the fields we'll be populating
	$fields[ 'member' ] = array(
		'wp_user_id',
		'username',
		'display_name',
		'first_name',
		'last_name',
		'occupation',
		'phone',
		'membership_type',
		'membership_id',
		'birthday',
		'addr1',
		'addr2',
		'city',
		'state',
		'zip',
		'user_pending',
		'hatch_date',
		'initiated_date',
		'membUserID',
		'nickname',
		'prim_memb_id',
		'relationship',
		'relationship_id',
		'renewal_date',
		'status_id',
		'tag_date',
		'user_login',
		'user_email',
		'user_addr',
		'user_bday_day',
		'user_bday_month',
		'user_birthdate',
		'user_city',
		'user_state',
		'user_zip',
		'user_contact',
		'user_hatch_date',
		'user_initiated',
		'user_memb_type',
		'user_occup',
		'user_pending',
		'user_phone',
		'user_share',
		'user_tag',
		'userphoto_thumb_file',


	);

	$fields[ 'spouse' ] = array(
		'sp_birthday',
		'sp_email',
		'sp_first_name',
		'sp_hatch_date',
		'sp_initiated_date',
		'sp_last_name',
		'sp_phone',
		'sp_relationship_id',
		'sp_tag_date',
		'sp_wp_user_id',


	);

	$fields[ 'child_1' ] = array(
		'c1_birthday',
		'c1_email',
		'c1_first_name',
		'c1_hatch_date',
		'c1_initiated_date',
		'c1_last_name',
		'c1_phone',
		'c1_relationship_id',
		'c1_tag_date',
		'c1_wp_user_id',
	);

	$fields[ 'child_2' ] = array(
		'c2_birthday',
		'c2_email',
		'c2_first_name',
		'c2_hatch_date',
		'c2_initiated_date',
		'c2_last_name',
		'c2_phone',
		'c2_relationship_id',
		'c2_tag_date',
		'c2_wp_user_id',
	);

	$fields[ 'child_3' ] = array(
		'c3_birthday',
		'c3_email',
		'c3_first_name',
		'c3_hatch_date',
		'c3_initiated_date',
		'c3_last_name',
		'c3_phone',
		'c3_relationship_id',
		'c3_tag_date',
		'c3_wp_user_id',
	);

	$fields[ 'child_4' ] = array(
		'c4_birthday',
		'c4_email',
		'c4_first_name',
		'c4_hatch_date',
		'c4_initiated_date',
		'c4_last_name',
		'c4_phone',
		'c4_relationship_id',
		'c4_tag_date',
		'c4_wp_user_id',
	);

	return $fields;
}

//add_filter( 'gform_field_value', 'kaos_populate_gf_field', 10, 3 );

function kaos_populate_gf_field( $value, $field, $name ) {
	global $user_error_type;

	$current_user = wp_get_current_user();
	if ( ! ( $current_user instanceof WP_User ) ) {
		return null;
	}

	$people = kaos_get_renewal_form_fields();

	foreach ( $people as $person ) {
		foreach ( $person as $meta_key ) {
			$values[ $meta_key ] = $current_user->$meta_key;

			$emessage = $meta_key . '->' . $current_user->$meta_key;
			error_log_message( $emessage, $user_error_type[ 'warning' ] );
		}
	}

	return isset( $values[ $name ] ) ? $values[ $name ] : $value;
}


/***********************************
 *  Database Cleanup
 **********************************/
//add_action( 'wp_footer', 'kaos_database_cleanup' );
function kaos_database_cleanup() {
	global $user_error_type;

	$meta_keys_array[ 'member' ] = $member_meta_keys = array(
		'first_name',
		'last_name',
		'membership_type',
		'relationship_id',
		'phone',
		'user_email',
		'occupation',
		'addr1',
		'addr2',
		'city',
		'state',
		'zip',
		'status',
		'share',
		'contact',
		'hatch_date',
		'initiated_date',
		'tag_date',
		'user_login',
		'wp_user_id',
	);

	$meta_keys_array[ 'spouse' ] = $spouse_meta_keys = array(
		'sp_first_name',
		'sp_last_name',
		'sp_email',
		'sp_phone',
		'sp_birthdate',
		'sp_relationship_id',
		'sp_wp_user_id',
	);

	$meta_keys_array[ 'child_1' ] = $c1_meta_keys = array(
		'c1_first_name',
		'c1_last_name',
		'c1_email',
		'c1_phone',
		'c1_birthdate',
		'c1_relationship_id',
		'c1_wp_user_id',
	);

	$meta_keys_array[ 'child_2' ] = $c2_meta_keys = array(
		'c2_first_name',
		'c2_last_name',
		'c2_email',
		'c2_phone',
		'c2_birthdate',
		'c2_relationship_id',
		'c2_wp_user_id',
	);

	$meta_keys_array[ 'child_3' ] = $c3_meta_keys = array(
		'c3_first_name',
		'c3_last_name',
		'c3_email',
		'c3_phone',
		'c3_birthdate',
		'c3_relationship_id',
		'c3_wp_user_id',
	);

	$meta_keys_array[ 'child_4' ] = $c4_meta_keys = array(
		'c4_first_name',
		'c4_last_name',
		'c4_email',
		'c4_phone',
		'c4_birthdate',
		'c4_relationship_id',
		'c4_wp_user_id',
	);

	$cleanup_meta_fields[ 'membership' ] = array(
		'membership',
		'membership_id',
		'memb_type',
		'user_memb_type',
		'cm_membership_type',
		'mb_membership_type',
	);

	$cleanup_meta_fields[ 'relationship' ] = array(
		'relationship',
		'cm_relationship',
		'sp_relationship',
		'c1_relationship',
		'c2_relationship',
		'c3_relationship',
		'c4_relationship',
		'fam_1_relationship_id',
		'fam_2_relationship_id',
		'fam_3_relationship_id',
		'fam_4_relationship_id',
		'mb_relationship_id',
	);

	$cleanup_meta_fields[ 'phone' ] = array(
		'cm_phone',
		'user_phone',
		'dbem_phone',
		'fam_1_phone',
	);

	$cleanup_meta_fields[ 'email' ] = array(
		'cm_email',
		'mb_mb_email',
		'mb_email',
		'c1_email',
		'c2_email',
		'c3_email',
		'c4_email',
		'fam_1_email',
		'fam_2_email',
		'fam_3_email',
		'fam_4_email',
	);

	$cleanup_meta_fields[ 'occupation' ] = array(
		'mb_occupation',
		'cm_occupation',
		'user_occup',
	);

	$cleanup_meta_fields[ 'address' ] = array(
		'user_addr',
		'address',
		'dbem_address',
		'dbem_address_2',
		'mb_addr1',
		'cm_addr1',
		'mb_addr2',
	);

	$cleanup_meta_fields[ 'city' ] = array(
		'dbem_city',
		'mb_city',
		'cm_city',
		'user_city',
	);

	$cleanup_meta_fields[ 'state' ] = array(
		'dbem_state',
		'cm_state',
		'user_state',
	);

	$cleanup_meta_fields[ 'zip' ] = array(
		'dbem_zip',
		'mb_zip',
		'user_zip',
		'cm_zip',
	);

	$cleanup_meta_fields[ 'dates' ] = array(
		'fam_1_hatch_date',
		'fam_2_hatch_date',
		'fam_3_hatch_date',
		'mb_hatch_date',
		'sp_hatch_date',
		'user_hatch_date',
		'fam_1_initiated',
		'user_initiated',
		'fam_1_tab_date',
		'fam_2_tab_date',
		'fam_3_tab_date',
		'sp_tag_date',
		'user_tag',
	);

	$cleanup_meta_fields[ 'misc_fields' ] = array(
		'mb_status_id',
		'status_id',
		'user_share',
		'user_contact',
	);

	$cleanup_meta_fields[ 'usernames' ] = array(
		'fam_1_username',
		'user_login',
	);

	$cleanup_meta_fields[ 'user_ids' ] = array(
		'fam_1_wp_user_id',
		'fam_2_wp_user_id',
		'fam_3_wp_user_id',
		'sp_wp_user_id',
		'fam_memb_id_1',
		'fam_memb_id_2',
		'fam_memb_id_3',
		'fam_memb_id_4',
		'prim_memb_id',
		'membUserID',
	);

	$delete_fields = array(
		'user_bday_day',
		'user_bday_month',
		'user_birthdate',
	);

	foreach ( $meta_keys_array as $member_meta_keys ) {
		$member_meta_keys_count = count( $member_meta_keys );
		foreach ( $cleanup_meta_fields as $cleanup_meta_field_group ) {
			foreach ( $cleanup_meta_field_group as $cleanup_meta_field ) {
				$emessage = 'cleanup_meta_field is ' . $cleanup_meta_field;
				user_error( $emessage, $user_error_type[ 'warning' ] );

				kaos_migrate_data( $cleanup_meta_field, $member_meta_keys, $member_meta_keys_count, $delete_fields );
			}
		}
	}
}

/**
 * @param $user_meta_key
 * @param $member_meta_keys
 * @param $member_meta_keys_count
 *
 * @param $fields_to_delete
 *
 * @internal param $member_meta_key
 */
function kaos_migrate_data( $user_meta_key, $member_meta_keys, $member_meta_keys_count, $fields_to_delete ) {
	global $user_error_type;

	if ( ! empty( $fields_to_delete ) ) {
		//TODO: create process to delete these fields as they will never be used again.
	}


	$fdelete       = false;
	$get_user_args = array(
		'meta_key' => $user_meta_key,
		'orderby'  => 'last_name',
		'fields'   => 'all_with_meta',
	);

	$members_data = get_users( $get_user_args );

	if ( ! empty( $members_data ) ) {
		foreach ( $members_data as $member ) {
			$memb_id    = $member->id;
			$user_value = $member->$user_meta_key;

			$validate_args = array(
				'memb_id'       => $memb_id,
				'user_value'    => $user_value,
				'user_meta_key' => $user_meta_key,
				'proc_memb'     => false,
			);

			switch ( $user_meta_key ) {
				case 'memb_type':
				case 'membership':
				case 'membership_id':
				case 'membership_type':
				case 'user_memb_type':
				case 'mb_membership_type':
				case 'cm_membership_type':
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'm_type' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'm_type' ];
					$fdelete                            = kaos_cleanup_membership( $validate_args );
					break;
				/*
				case 'relationship':
				case 'cm_relationship':
				case 'relationship_id':
				case 'mb_relationship_id':
				case 'cm_relationship_id':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'rel' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'rel' ];
					$fdelete                            = kaos_cleanup_relationship( $validate_args );
					break;
				case 'sp_relationship':
				case 'c1_relationship':
				case 'c2_relationship':
				case 'c3_relationship':
				case 'c4_relationship':
				case 'sp_relationship_id':
				case 'c1_relationship_id':
				case 'c2_relationship_id':
				case 'c3_relationship_id':
				case 'c4_relationship_id':
				case 'fam_1_relationship_id':
				case 'fam_2_relationship_id':
				case 'fam_3_relationship_id':
				case 'fam_4_relationship_id':
					$validate_args[ 'member_meta_key' ] = $user_meta_key;
					$validate_args[ 'member_value' ]    = '';
					$fdelete                            = kaos_cleanup_fam_relationship( $validate_args );
					break;
				case 'dbem_phone':
				case 'user_phone':
				case 'phone':
				case 'fam_1_phone':
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'phone' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'phone' ];
					$fdelete                            = kaos_cleanup_phone( $validate_args );
					break;
				case 'email':
				case 'mb_email':
				case 'mb_mb_email':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'email' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'email' ];
					$fdelete                            = kaos_cleanup_emails( $validate_args );
				case 'mb_occupation':
				case 'user_occup':
				case 'occupation':
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'occu' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'occu' ];
					$fdelete                            = kaos_cleanup_occupation( $validate_args );
					break;
				case 'user_addr':
				case 'address':
				case 'dbem_address1':
				case 'mb_addr1':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'addr1' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'addr1' ];
					$fdelete                            = kaos_cleanup_address( $validate_args );
					break;
				case 'dbem_address2':
				case 'mb_addr2':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'addr2' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'addr2' ];
					$fdelete                            = kaos_cleanup_address( $validate_args );
					break;
				case 'user_city':
				case 'mb_city':
				case 'dbem_city':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'city' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'city' ];
					$fdelete                            = kaos_cleanup_city( $validate_args );
					break;
				case 'user_state':
				case 'dbem_state':
				case 'state':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'state' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'state' ];
					$fdelete                            = kaos_cleanup_state( $validate_args );
					break;
				case 'user_zip':
				case 'mb_zip':
				case 'dbem_zip':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'zip' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'zip' ];
					$fdelete                            = kaos_cleanup_zip( $validate_args );
					break;
				case 'status_id':
				case 'mb_status_id':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'status' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'status' ];
					$fdelete                            = kaos_cleanup_status( $validate_args );
					break;
				case 'user_share':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'share' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'share' ];
					$fdelete                            = kaos_cleanup_share( $validate_args );
					break;
				case 'user_contact':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'cont' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'cont' ];
					$fdelete                            = kaos_cleanup_contact( $validate_args );
					break;
				case 'user_hatch_date':
					$validate_args[ 'proc_memb' ] = true;
				case 'fam_1_hatch_date':
				case 'fam_2_hatch_date':
				case 'fam_3_hatch_date':
				case 'mb_hatch_date':
				case 'sp_hatch_date':
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'hatch' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'hatch' ];
					$fdelete                            = kaos_cleanup_hatch_dates( $validate_args );
					break;
				case 'fam_1_initiated':
				case 'user_initiated':
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'init' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'init' ];
					$fdelete                            = kaos_cleanup_initiated_dates( $validate_args );
					break;
				case 'user_tag':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'tag' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'tag' ];
					$fdelete                            = kaos_cleanup_tag_dates( $validate_args );
					break;
				case 'user_login':
				case 'membUserID':
					$validate_args[ 'proc_memb' ]       = true;
					$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'login' ];
					$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'login' ];
					$fdelete                            = kaos_cleanup_usernames( $validate_args );
					break;
				*/
				default:
					$fdelete = false;
			}

			If ( $fdelete === true ) {
				$del_result = kaos_delete_user_meta( $memb_id, $user_meta_key );
			} else {
				$emessage = 'Skipped deleting ' . $user_meta_key . ' for user id: ' . $memb_id;
				user_error( $emessage, $user_error_type[ 'warning' ] );
			}
		}
	}
}

function kaos_get_cleanup_defaults() {
	$defaults = array(
		'fdelete'         => false,
		'memb_type'       => null,
		'memb_id'         => null,
		'user_value'      => null,
		'user_meta_key'   => null,
		'member_meta_key' => null,
		'member_value'    => null,
	);

	return $defaults;
}

function kaos_cleanup_membership( $args ) {
	global $user_error_type;
	/** @noinspection PhpUndefinedVariableInspection */
	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}


	If ( empty( $member_value ) && ! empty( $user_value ) ) {
		$switch_test = $user_value;
	} elseif ( ! empty( $member_value ) ) {
		$switch_test = $member_value;
	} else {
		return $fdelete = true;
	}

	switch ( $switch_test ) {
		case 'S':
		case 'Single':
		case '1':
			$memb_type = 'ID';
			break;
		case '2':
			$memb_type = 'IC';
			break;
		case 'C':
		case 'Couple':
		case '3':
			$memb_type = 'CO';
			break;
		case 'F':
		case '4':
		case 'Family':
			$memb_type = 'HH';
			break;
		default:
			$memb_type = $switch_test;
	}


	if ( empty( $member_value ) && ! empty( $memb_type ) ) {
		$emessage = 'Membership Type is being updated with: ' . $memb_type;
		user_error( $emessage, $user_error_type[ 'warning' ] );

		$result = kaos_update_member_meta( $memb_id, $member_meta_key, $memb_type );
		if ( $result ) {
			$fdelete = true;
		}
	} elseif ( $member_value === $memb_type ) {
		$emessage = 'Membership Type does not need to be updated.';
		user_error( $emessage, $user_error_type[ 'warning' ] );

		$fdelete = true;

	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_relationship( $args ) {
	global $user_error_type;

	//Relationship_id(user_value) trumps relationship(member_value)
	/** @noinspection PhpUndefinedVariableInspection */

	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	$update_meta_value = null;

	If ( empty( $member_value ) && ! empty( $user_value ) ) {
		$switch_test = $user_value;
	} elseif ( ! empty( $member_value ) ) {
		$switch_test = $member_value;
	} else {
		return $fdelete = true;
	}

	switch ( $user_value ) {
		//case 'M':
		case '1':
			$update_meta_value = 'M';
			break;
		//case 'S':
		case '2':
			$update_meta_value = 'S';
			break;
		//case 'P':
		case '3':
			$update_meta_value = 'P';
			break;
		//case 'C':
		case '4':
			$update_meta_value = 'C';
			break;
		//case 'O':
		case '5':
			$update_meta_value = 'O';
			break;
		default:
			$update_meta_value = $user_value;
	}

	if ( empty( $member_value ) && ! empty( $update_meta_value ) ) {
		$emessage = $member_meta_key . ' is being updated with: ' . $update_meta_value;
		user_error( $emessage, $user_error_type[ 'warning' ] );

		$result = kaos_update_member_meta( $memb_id, $member_meta_key, $update_meta_value );
		if ( $result ) {
			$fdelete = true;
		}
	} elseif ( $member_value === $update_meta_value ) {
		$emessage = $member_meta_key . ' does not need to be updated.';
		user_error( $emessage, $user_error_type[ 'warning' ] );

		$fdelete = true;

	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_fam_relationship( $args ) {
	global $user_error_type;

	//Relationship_id(user_value) trumps relationship(member_value)
	/** @noinspection PhpUndefinedVariableInspection */

	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	$update_meta_value = null;

	If ( empty( $member_value ) && ! empty( $user_value ) ) {
		$switch_test = $user_value;
	} elseif ( ! empty( $member_value ) ) {
		$switch_test = $member_value;
	} else {
		return $fdelete = false;
	}

	switch ( $switch_test ) {
		//case 'M':
		case '1':
			$update_meta_value = 'M';
			break;
		//case 'S':
		case '2':
			$update_meta_value = 'S';
			break;
		//case 'P':
		case '3':
			$update_meta_value = 'P';
			break;
		//case 'C':
		case '4':
			$update_meta_value = 'C';
			break;
		//case 'O':
		case '5':
			$update_meta_value = 'O';
			break;
		default:
			$update_meta_value = $switch_test;
	}

	if ( empty( $member_value ) && ! empty( $update_meta_value ) ) {
		$emessage = $member_meta_key . ' is being updated with: ' . $update_meta_value;
		user_error( $emessage, $user_error_type[ 'warning' ] );

		$result = kaos_update_member_meta( $memb_id, $member_meta_key, $update_meta_value );
		if ( $result ) {
			$fdelete = false;
		}
	} elseif ( $member_value === $update_meta_value ) {
		$emessage = 'cm_relationship does not need to be updated.';
		user_error( $emessage, $user_error_type[ 'warning' ] );

		$fdelete = false;

	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_phone( $args ) {
	global $user_error_type;

	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	if ( ( empty( $member_value ) && ! empty( $user_value ) ) || ! empty( $user_value ) ) {
		$formatted_phone = formatPhoneNumber( $user_value );
	} elseif ( ! empty( $member_value ) ) {
		$formatted_phone = formatPhoneNumber( $member_value );
	} else {
		return true;
	}

	if ( $proc_memb ) {
		$fdelete = kaos_update_member_meta( $memb_id, $member_meta_key, $formatted_phone );
		user_error( 'Phone update result is ' . $fdelete, $user_error_type[ 'warning' ] );
	} else {
		$fam_meta_key   = kaos_get_fam_meta_key( $user_meta_key, $memb_id );
		$fam_first_name = get_user_meta( $memb_id, $fam_meta_key, 'single' );

		if ( ! empty( $fam_first_name ) ) {
			if ( kaos_update_member_meta( $memb_id, $user_meta_key, $formatted_phone ) ) {
				$fdelete = false;
			}
		} else {
			$fdelete = true;
		}
	}

	return $fdelete;
}

function kaos_cleanup_emails( $args ) {
	global $user_error_type;

	//TODO: Create email cleanup functions
	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	$user_data   = get_userdata( $memb_id );
	$users_email = $user_data->user_email;


	if ( empty( $member_value ) && ! empty( $user_value ) ) {
		$user_id = wp_update_user( array( 'ID' => $memb_id, $member_meta_key => $user_value ) );

		if ( is_wp_error( $user_id ) ) {
			user_error( $user_id->get_error_message(), $user_error_type[ 'warning' ] );
			$fdelete = false;
		} else {
			user_error( 'user_email update with ' . $user_value, $user_error_type[ 'warning' ] );
			$fdelete = true;
		}

	} elseif ( ! empty( $member_value ) ) {

	} elseif ( $member_value === $user_value ) {
		user_error( 'user_email : No update needed. ', $user_error_type[ 'warning' ] );
	} else {
		//user value doesn't equal member value
		//hold both values for review


		if ( is_wp_error( $user_id ) ) {
			user_error( 'user_email : No update needed. ', $user_error_type[ 'warning' ] );
			$fdelete = false;
		} else {
			$fdelete = true;
		}
	}

	return $fdelete;
}

/**
 * @param $args
 *
 * @return bool
 */
function kaos_cleanup_occupation( $args ) {
	global $user_error_type;

	//TODO: Create occupation cleanup function
	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	// If user_value is to be used for updating user_meta_field
	if ( empty( $member_value ) && ! empty( $user_value ) ) {
		$emessage = 'Occupation is being updated with: ' . $user_value;
		user_error( $emessage, $user_error_type[ 'warning' ] );

		$result = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );

		if ( $result ) {
			$fdelete = true;
		} elseif ( $user_value === $meta_value ) {
			$emessage = 'Occupation does not need to be updated.';
			user_error( $emessage, $user_error_type[ 'warning' ] );
			$fdelete = true;
		} else {
			$fdelete = false;
		}

	} elseif ( $member_value === $user_value ) {
		$emessage = 'Membership Type does not need to be updated.';
		user_error( $emessage, $user_error_type[ 'warning' ] );

		$fdelete = true;
	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_address( $args ) {
	global $user_error_type;

	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	$fdelete      = false;
	$user_value   = ucwords( $user_value );
	$member_value = ucwords( $member_value );
	$member_addr2 = get_user_meta( $memb_id, 'addr2', 'single' );

	if ( strpos( $user_value, ',' ) ) {
		list( $addr1, $addr2 ) = explode( ',', $user_value );
		$addr1 = ucwords( $addr1 );
		$addr2 = '#' . ucwords( $addr2 );

		error_log_message( 'addr1: ' . $addr1 . ' addr2: ' . $addr2 );

		$result1 = kaos_update_member_meta( $memb_id, $member_meta_key, $addr1 );
		$result2 = kaos_update_member_meta( $memb_id, 'addr2', $addr2 );

		if ( ( $result1 ) && ( $result2 ) ) {
			$fdelete = true;
		}
	} elseif ( strpos( $user_value, '#' ) ) {
		list( $addr1, $addr2 ) = explode( '#', $user_value );
		$addr1 = ucwords( $addr1 );
		$addr2 = ucwords( $addr2 );

		error_log_message( 'addr1: ' . $addr1 . ' addr2: ' . $addr2 );

		$result1 = kaos_update_member_meta( $memb_id, $member_meta_key, $addr1 );
		$result2 = kaos_update_member_meta( $memb_id, 'addr2', $addr2 );

		if ( ( $result1 ) && ( $result2 ) ) {
			$fdelete = true;
		}
	} else {
		$addr1 = $user_value;
		if ( $addr1 !== $member_value ) {
			error_log_message( 'addr1:' . $addr1 . ' member value:' . $member_value );
			$result1 = kaos_update_member_meta( $memb_id, $member_meta_key, $addr1 );
		}

		if ( ! empty( $addr2 ) ) {
			error_log_message( 'addr2:' . $addr2 );
			$result2 = kaos_update_member_meta( $memb_id, 'addr2', $addr2 );

			if ( $result1 ) {
				if ( $result2 ) {
					$fdelete = true;
				}
			}
		}
	}

	return $fdelete;
}

function kaos_cleanup_city( $args ) {
	global $user_error_type;

	//TODO: Create city cleanup function
	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	if ( empty( $member_value ) && ! empty( $user_value ) ) {
		$user_value = ucwords( $user_value );
		$result     = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );
		$emessage   = 'cm_city is being updated with: ' . $user_value;
		user_error( $emessage, $user_error_type[ 'warning' ] );

		if ( $result ) {
			$fdelete = true;
		}
	} elseif ( $member_value === $user_value ) {
		$emessage = 'cm_city does not need to be updated!';
		user_error( $emessage, $user_error_type[ 'warning' ] );
		$fdelete = true;
	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_state( $args ) {
	global $user_error_type;

	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	$state      = null;
	$test_value = null;

	If ( ( empty( $member_value ) && ! empty( $user_value ) ) || ! empty( $user_value ) ) {
		$switch_test = $user_value;
	} elseif ( ! empty( $member_value ) ) {
		$switch_test = $member_value;
	} else {
		return $fdelete = true;
	}

	switch ( $test_value ) {
		case '5':
		case 'CA':
			$state = 'California';
			break;
		case '6':
		case 'CO':
			$state = 'Colorado';
			break;
		case 'FL':
			$state = 'Florida';
			break;
		case 'NY':
		case '32':
			$state = 'New York';
			break;
		case 'TX':
		case '43':
			$state = 'Texas';
			break;
	}

	if ( empty( $member_value ) && ! empty( $state ) ) {
		$emessage = 'State is being updated with: ' . $state;
		user_error( $emessage, $user_error_type[ 'warning' ] );
		$result = kaos_update_member_meta( $memb_id, $member_meta_key, $state );

		if ( $result ) {
			$fdelete = true;
		} else {
			//TODO: Display some kind of error the php error log.
		}

	} elseif ( $member_value === $state ) {
		$emessage = 'State does not need to be updated.';
		user_error( $emessage, $user_error_type[ 'warning' ] );
		$fdelete = true;
	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_zip( $args ) {
	global $user_error_type;

	//TODO: Create zip cleanup function
	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	if ( empty( $member_value ) && ! empty( $user_value ) ) {
		$result   = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );
		$emessage = 'cm_zip is being updated with: ' . $user_value;
		user_error( $emessage, $user_error_type[ 'warning' ] );

		if ( $result ) {
			$fdelete = true;
		}
	} elseif ( $member_value === $user_value ) {
		$emessage = 'cm_zip does not need to be updated!';
		user_error( $emessage, $user_error_type[ 'warning' ] );
		$fdelete = true;
	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_status( $args ) {
	global $user_error_type;

	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	If ( ( empty( $member_value ) && ! empty( $user_value ) ) || ! empty( $user_value ) ) {
		$switch_test = $user_value;
	} elseif ( ! empty( $member_value ) ) {
		$switch_test = $member_value;
	} else {
		return $fdelete = true;
	}

	switch ( $switch_test ) {
		case '0':
		case 'Pending':
			$update_value = 'Pending';
			break;
		case 'Active':
		case '1':
			$update_value = 'Active';
			break;
		case 'Archived':
		case '2':
			$update_value = 'Archived';
			break;
		case 'Inactive':
		case '3':
			$update_value = 'Inactive';
			break;
		default:
			$update_value = $switch_test;
	}

	if ( empty( $member_value ) && ! empty( $update_value ) ) {
		$result   = kaos_update_member_meta( $memb_id, $member_meta_key, $update_value );
		$emessage = 'cm_status is being updated with: ' . $update_value;
		user_error( $emessage, $user_error_type[ 'warning' ] );

		if ( $result ) {
			$fdelete = true;
		}
	} elseif ( $member_value === $update_value ) {
		$emessage = 'cm_status does not need to be updated!';
		user_error( $emessage, $user_error_type[ 'warning' ] );
		$fdelete = true;
	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_share( $args ) {
	global $user_error_type;

	$defaults = kaos_get_cleanup_defaults();

	$args = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	if ( empty( $member_value ) && ! empty( $user_value ) ) {
		$result   = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );
		$emessage = 'cm_share is being updated with: ' . $user_value;
		user_error( $emessage, $user_error_type[ 'warning' ] );

		if ( $result ) {
			$fdelete = true;
		}
	} elseif ( $member_value === $user_value ) {
		$emessage = 'cm_share does not need to be updated!';
		user_error( $emessage, $user_error_type[ 'warning' ] );
		$fdelete = true;
	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_contact( $args ) {
	global $user_error_type;

	$defaults = kaos_get_cleanup_defaults();

	$args = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	if ( empty( $member_value ) && ! empty( $user_value ) ) {
		$result   = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );
		$emessage = 'cm_share is being updated with: ' . $user_value;
		user_error( $emessage, $user_error_type[ 'warning' ] );

		if ( $result ) {
			$fdelete = true;
		}
	} elseif ( $member_value === $user_value ) {
		$emessage = 'cm_share does not need to be updated!';
		user_error( $emessage, $user_error_type[ 'warning' ] );
		$fdelete = true;
	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_hatch_dates( $args ) {
	global $user_error_type;

	$defaults = kaos_get_cleanup_defaults();

	$args = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	//TODO: Compare hatch_ddate to registration date
	$user_data     = get_userdata( $memb_id );
	$memb_reg_date = $user_data->user_registered;

	if ( strtotime( $user_value ) <= strtotime( $memb_reg_date ) ) {
		$emessage = 'user_value: ' . $user_value . ':strtotime is: ' . strtotime( $user_value );
		user_error( $emessage, $user_error_type[ 'warning' ] );

		$emessage = 'user_registered: ' . $memb_reg_date . ' :strtotime is: ' . strtotime( $memb_reg_date );
		user_error( $emessage, $user_error_type[ 'warning' ] );
	}

	if ( empty( $member_value ) && ! empty( $user_value ) ) {
		$result   = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );
		$emessage = 'cm_share is being updated with: ' . $user_value;
		user_error( $emessage, $user_error_type[ 'warning' ] );

		if ( $result ) {
			$fdelete = true;
		}
	} elseif ( $member_value === $user_value ) {
		$emessage = 'cm_share does not need to be updated!';
		user_error( $emessage, $user_error_type[ 'warning' ] );
		$fdelete = true;
	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_initiated_dates( $args ) {
	global $user_error_type;

	$defaults = kaos_get_cleanup_defaults();

	$args = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	if ( empty( $member_value ) ) {

		if ( $user_value !== $member_value ) {
			$result = kaos_update_member_meta( $memb_id, $member_meta_key, $member_value );
		}

		if ( $fdelete ) {
			$fdelete = true;
		} else {
			$fdelete = false;
		}
	}

	return $fdelete;
}

function kaos_cleanup_tag_dates( $args ) {
	global $user_error_type;

	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	if ( empty( $member_value ) ) {

		if ( $user_value !== $member_value ) {
			$fdelete = kaos_update_member_meta( $memb_id, $member_meta_key, $member_value );
		}
	}
	if ( $fdelete ) {
		$fdelete = true;
	} else {
		$fdelete = false;
	}

	return $fdelete;
}

function kaos_cleanup_usernames( $args ) {
	global $user_error_type;

	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}


	if ( $user_value === $member_value ) {
		$fdelete = kaos_delete_user_meta( $memb_id, $member_meta_key );
	} elseif ( ! empty( $user_login ) ) {
		$fdelete = kaos_update_member_meta( $memb_id, $member_meta_key, $member_value );
	}

	return $fdelete;
}

function kaos_cleanup_wp_user_ids( $args ) {
	global $user_error_type;

	$defaults = kaos_get_cleanup_defaults();
	$args     = wp_parse_args( $args, $defaults );

	foreach ( $args as $arg_key => $arg_value ) {
		${$arg_key} = $arg_value;
	}

	if ( empty( $member_value ) ) {

		if ( $user_value !== $member_value ) {
			$fdelete = kaos_update_member_meta( $memb_id, $member_meta_key, $member_value );
		}
	}

	return $fdelete;
}

function kaos_delete_user_meta( $memb_id, $user_meta_key ) {
	global $user_error_type;

	$delete_result = delete_user_meta( $memb_id, $user_meta_key );
	if ( ! $delete_result ) {
		error_log_message( 'Delete of ' . $memb_id . ' ' . $user_meta_key . ' FAILED!!!!' );
		$delete_result = false;
	} else {
		error_log_message( 'Delete of ' . $memb_id . ' ' . $user_meta_key . ' completed.' );
	}
	error_log_message( " " );

	return $delete_result;
}

function kaos_update_member_meta( $memb_id, $update_meta_key, $update_meta_value ) {
	global $user_error_type;

	$update_result = update_user_meta( $memb_id, $update_meta_key, $update_meta_value );
	if ( ! $update_result ) {
		$curr_meta_value = get_user_meta( $memb_id, $update_meta_key, 'single' );
		if ( $update_meta_value === $curr_meta_value ) {
			$emessage = $memb_id . ': No update of ' . $update_meta_key . ' was needed.';
			error_log_message( $emessage, $user_error_type[ 'warning' ] );
			$update_result = true;
		} else {
			$emessage = $memb_id . ': Update of ' . $update_meta_key . ' FAILED!!!!';
			error_log_message( $emessage, $user_error_type[ 'warning' ] );
			$update_result = false;
		}

	} else {
		$emessage = $memb_id . ' : ' . $update_meta_key . ' Update completed.';
		error_log_message( $emessage, $user_error_type[ 'warning' ] );
		$update_result = true;
	}

	return $update_result;
}

function kaos_get_fam_meta_key( $user_meta_key, $memb_id ) {

	switch ( $user_meta_key ) {
		case 'sp_relationship':
		case 'c1_relationship':
		case 'c2_relationship':
		case 'c3_relationship':
		case 'c4_relationship':
		case 'sp_relationship_id':
		case 'c1_relationship_id':
		case 'c2_relationship_id':
		case 'c3_relationship_id':
		case 'c4_relationship_id':
		case 'fam_1_relationship_id':
		case 'fam_2_relationship_id':
		case 'fam_3_relationship_id':
		case 'fam_1_phone':
		case 'sp_phone':
		case 'sp_email':
		case 'fam_1_email':
		case 'fam_2_email':
		case 'fam_3_email':
		case 'c1_email':
		case 'c2_email':
		case 'c3_email':
		case 'c4_email':
		case 'fam_1_hatch_date':
		case 'fam_2_hatch_date':
		case 'fam_3_hatch_date':
		case 'fam_1_initiated':
			$splt_field   = explode( '_', $user_meta_key );
			$fam_meta_key = $splt_field[ 0 ] . '_first_name';
			break;
		default:
			$fam_meta_key = null;
	}

	return $fam_meta_key;
}

function kaos_compare_fam_ids( $memb_id ) {
	global $user_error_type, $wpdb;

	$wpdb->show_errors( true );
	$x = 1;

	$fam_meta_keys = array();

	$fam_memb_recs = $wpdb->get_results( $wpdb->prepare( "SELECT user_id, meta_key, meta_value
															FROM ctxphcco_wp_db.ctxphc_usermeta
															WHERE user_id = $memb_id AND meta_key REGEXP \'^[c,fam,sp,mb,prim,wp]*_.*id$\'" ) );

	if ( ! empty( $fam_memb_recs ) ) {
		foreach ( $fam_memb_recs as $fam_memb_rec ) {
			foreach ( $fam_memb_rec as $fmid_key => $fmid_value ) {
				$emessage = 'fam_memb_ids returned: ' . $fmid_key . ': => :' . $fmid_value;
				error_log_message( $emessage, $user_error_type[ 'warning' ] );
			}
			if ( $fam_memb_rec[ 'meta_value' ] === $fam_memb_rec[ 'user_id' ] ) {
				$emessage = 'the meta_value: ' . $fam_memb_rec[ 'meta_value' ] . ' equals the user_id value: ' . $fam_memb_rec[ 'user_id' ];
				error_log_message( $emessage, $user_error_type[ 'warning' ] );
				$fam_args[ 'del_fam_key' ][] = $fam_memb_rec[ 'meta_key' ];
			} else {
				$emessage = 'the meta_value: ' . $fam_memb_rec[ 'meta_value' ] . ' is not equal to the user_id value: ' . $fam_memb_rec[ 'user_id' ];
				error_log_message( $emessage, $user_error_type[ 'warning' ] );
				$fam_args[ 'save_fam_key' ][] = $fam_memb_rec[ 'meta_key' ];
			}
		}
	}

	return $fam_args;
}

function kaos_get_relationship_value( $user_value ) {
	global $user_error_type;

	$update_meta_value = null;

	switch ( $user_value ) {
		//case 'M':
		case '1':
			$update_meta_value = 'M';
			break;
		//case 'S':
		case '2':
			$update_meta_value = 'S';
			break;
		//case 'P':
		case '3':
			$update_meta_value = 'P';
			break;
		//case 'C':
		case '4':
			$update_meta_value = 'C';
			break;
		//case 'O':
		case '5':
			$update_meta_value = 'O';
			break;
		default:
			$update_meta_value = $user_value;
	}

	return $update_meta_value;
}

function kaos_process_rel_update( $memb_id, $update_meta_key, $update_meta_value ) {
	global $user_error_type;

	$emessage = 'user: ' . $memb_id . ' field ' . $update_meta_key . ' is being replaced with ' . $update_meta_value;
	error_log_message( $emessage, $user_error_type[ 'warning' ] );
	$fdelete = kaos_update_member_meta( $memb_id, $update_meta_key, $update_meta_value );

	if ( $fdelete ) {
		$fdelete = true;
	} else {
		$fdelete = false;
	}

	return $fdelete;
}

add_action( 'gform_after_submission', 'get_entry_data', 10, 2 );
function get_entry_data() {

	if ( $submit = 'registration' ) {
		$form_id = 1;
	} else {
		$form_id = 5;
	}


	//Get all entries for registration or renewal forms
	$entries = GFAPI::get_entries( $form_id );

	//Get the most recent entry
	$entry = GFAPI::get_entry( $entry_id );

	//get all entry data
	$field_data = array(
		'$date_created'   => rgar( $entry, 'date_created' ), // returns the entry date
		'membership_type' => rgar( $entry, '1' ),       // Membership Options.
		'm_first_name'    => rgar( $entry, '41.3' ),    // returns the members first name.
		'm_last_name'     => rgar( $entry, '41.6' ),    // returns the members last name.
		'm_email'         => rgar( $entry, '6' ),       // return the members email address.
		'm_phone'         => rgar( $entry, '7' ),       // returns the members phone.
		'm_birthday'      => rgar( $entry, '5' ),        // returns the spouses birthday
		'm_occupation'    => rgar( $entry, '23' ),      // returns the members occupation.
		'addr1'           => rgar( $entry, '10.1' ),    // returns the address field
		'addr2'           => rgar( $entry, '10.2' ),    // returns the suite/apt # field
		'city'            => rgar( $entry, '10.3' ),    // returns the city.
		'state'           => rgar( $entry, '10.4' ),    // returns the state.
		'zip'             => rgar( $entry, '10.5' ),    // returns the zip code.
		's_first_name'    => rgar( $entry, '12.3' ),    // returns the spouses first name.
		's_last_name'     => rgar( $entry, '12.6' ),    // returns the spouses last name.
		's_email'         => rgar( $entry, '13' ),       // return the spouses email address.
		's_phone'         => rgar( $entry, '14' ),       // returns the spouses phone.
		's_birthday'      => rgar( $entry, '15' ),        // returns the spouses birthday
		's_relationship'  => rgar( $entry, '22' ),        // Returns the spouses relationship status
		'c1_first_name'   => rgar( $entry, '17.3' ),    // returns child 1 first name.
		'c1_last_name'    => rgar( $entry, '17.6' ),    // returns child 1 last name.
		'c1_email'        => rgar( $entry, '18' ),       // return child 1 email address.
		'c1_phone'        => rgar( $entry, '20' ),       // returns child 1 phone.
		'c1_birthday'     => rgar( $entry, '33' ),        // returns child 1 birthday
		'c1_relationship' => rgar( $entry, '39' ),        // Returns child 1 relationship status
		'c2_first_name'   => rgar( $entry, '27.3' ),    // returns child 2 first name.
		'c2_last_name'    => rgar( $entry, '27.6' ),    // returns child 2 last name.
		'c2_email'        => rgar( $entry, '31' ),       // returns child 2 email address.
		'c2_phone'        => rgar( $entry, '35' ),       // returns child 2 phone.
		'c2_birthday'     => rgar( $entry, '32' ),        // returns child 2 birthday
		'c2_relationship' => rgar( $entry, '40' ),        // Returns child 2 relationship status
		'c3_first_name'   => rgar( $entry, '26.3' ),    // returns child 3 first name.
		'c3_last_name'    => rgar( $entry, '26.6' ),    // returns child 3 last name.
		'c3_email'        => rgar( $entry, '30' ),       // returns child 3 email address.
		'c3_phone'        => rgar( $entry, '36' ),       // returns child 3 phone.
		'c3_birthday'     => rgar( $entry, '19' ),        // returns child 3 birthday
		'c3_relationship' => rgar( $entry, '21' ),        // Returns child 3 relationship status
		'c4_first_name'   => rgar( $entry, '25.3' ),    // returns child 4 first name.
		'c4_last_name'    => rgar( $entry, '25.6' ),    // returns child 4 last name.
		'c4_email'        => rgar( $entry, '29' ),       // returns child 4 email address.
		'c4_phone'        => rgar( $entry, '37' ),       // returns child 4 phone.
		'c4_birthday'     => rgar( $entry, '34' ),        // returns child 4 birthday
		'c4_relationship' => rgar( $entry, '38' ),        // Returns child 4 relationship status
	);

	email_membership_directory( $field_data );
	//post_to_paypal( $field_data );
}

function email_membership_directory( $memb_data ) {
	$to[] = "support@ctxphc.com";

	$subject = "New Membership Registration Form Submitted";

	$headers[] = "From: Central Texas Parrot Head Club<support@ctxphc.com>";
	$headers[] = "Reply-To: support@ctxphc.com";
	$headers[] = "MIME-Version: 1.0\r\n";
	$headers[] = "Content-Type: text/html; charset=ISO-8859-1\r\n";

	$body = "<html><body>";

	foreach ( $memb_data as $membkey => $membvalue ) {
		$body .= '<div>' . $membkey . ': ' . $membvalue . '</div>';
	}

	$body .= "<p>FinsUp! ";
	$body .= "<p>CTxPHC Support<br />";
	$body .= "Central Texas Parrot Head Club</div>";
	$body .= "</body></html>";

	add_filter( 'wp_mail_content_type', 'set_html_content_type' );
	wp_mail( $to, $subject, $body, $headers );
	remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
}


function post_to_paypal( $trans_data ) { //TODO: This needs work and testing!!!!!!

	$pp_button_fields = $post_url = 'http://thirdparty.com';
	$body             = array(
		'first_name' => rgar( $entry, '1.3' ),
		'last_name'  => rgar( $entry, '1.6' ),
		'message'    => rgar( $entry, '3' ),
	);


	//log.LogLevel can be any of: FINE, INFO, WARN or ERROR
	$config  = array(
		'mode'           => 'sandbox',
		'acct1.UserName' => 'jb-us-seller_api1.paypal.com',
		'acct1.Password' => 'WX4WTU3S8MY44S7F',
		'log.LogEnabled' => 'true',
		'log.FileName'   => 'PayPal.log',
		'log.LogLevel'   => 'INFO',
	);
	$service = new PayPalAPIInterfaceServiceService( $config );
	$service->SetExpressCheckout();

	$service = new AdaptivePaymentsService( $config );
	$service->Pay();


	$request  = new WP_Http();
	$response = $request->post( $post_url, array( 'body' => $body ) );

}