<?php
/*
Plugin Name: Divi Custom Modules
Plugin URI: https://divithemestore.com/divi-shop/divi-custom-modules/
Description: A Brand new Divi Custom Modules for Divi Theme
Author: Divi Theme Store
Author URI: https://divithemestore.com/
Version: 1.7.6
License: GNU General Public License v2.0 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  dcm
 */
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// DIVI THEME STORE LOAD CLASSES AND PLUGIN OPTIONS PAGE
add_action( 'init', 'dcm_load_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 1.7
 */
function dcm_load_textdomain() {
  load_plugin_textdomain( 'dcm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
define( 'DCM__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
include (DCM__PLUGIN_DIR . 'dcm-page.php');
require_once( DCM__PLUGIN_DIR . 'class/dcm-pop-posts.class.php' );
require_once( DCM__PLUGIN_DIR . 'class/dcm-latest-posts.class.php' );
require_once( DCM__PLUGIN_DIR . 'class/dcm-custom-about.class.php' );
require( DCM__PLUGIN_DIR . 'inc/customizer/customizer-control.php' );
require_once( DCM__PLUGIN_DIR . 'class.wp-auto-plugin-update.php');

require (DCM__PLUGIN_DIR . '/inc/customizer/astra-customizer.class.php');
new Astra_Customizer();
function GWP_Custom_Modules() {
    if ( class_exists( "ET_Builder_Module" ) ) {
        include(DCM__PLUGIN_DIR .  'class/DcmCustomBlog.class.php' );
    }
}
//END CLASSES AND PLUGIN OPTIONS PAGE
//LOAD TEMPLATES
add_filter('template_include', 'DCM_set_template');
function DCM_set_template( $template ){
    if(is_home() || is_category() || is_search() && 'index.php' != $template ):
        $template = DCM__PLUGIN_DIR . '/templates/custom-index.php';
    endif;
    return $template;
}
//END TEMPLATE
// LOAD STYLES
add_image_size( 'medium_crop', 350, 210, true );
add_action( 'wp_enqueue_scripts', 'enqueue_custom_modules_scripts' );
function enqueue_custom_modules_scripts() {
    wp_enqueue_style( 'mod-css', plugin_dir_url( __FILE__ ) . 'css/mod.css' );
		wp_add_inline_style( 'mod-css', astra_customizer_css() );
}

if ( ! function_exists( "enqueue_dcm_font_awesome" ) ):
    add_action( 'wp_enqueue_scripts', 'enqueue_dcm_font_awesome' );
    function enqueue_dcm_font_awesome() {
        wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
    }
endif;
// END STYLES
function Build_GWP_Custom_Modules() {
    global $pagenow;
    $is_admin                     = is_admin();
    $action_hook                  = $is_admin ? 'wp_loaded' : 'wp';
    $required_admin_pages         = array(
        'edit.php',
        'post.php',
        'post-new.php',
        'admin.php',
        'customize.php',
        'edit-tags.php',
        'admin-ajax.php',
        'export.php'
    ); // list of admin pages where we need to load builder files
    $specific_filter_pages        = array( 'edit.php', 'admin.php', 'edit-tags.php' );
    $is_edit_library_page         = 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && 'et_pb_layout' === $_GET['post_type'];
    $is_role_editor_page          = 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'et_divi_role_editor' === $_GET['page'];
    $is_import_page               = 'admin.php' === $pagenow && isset( $_GET['import'] ) && 'wordpress' === $_GET['import'];
    $is_edit_layout_category_page = 'edit-tags.php' === $pagenow && isset( $_GET['taxonomy'] ) && 'layout_category' === $_GET['taxonomy'];
    if ( ! $is_admin || ( $is_admin && in_array( $pagenow, $required_admin_pages ) && ( ! in_array( $pagenow, $specific_filter_pages ) || $is_edit_library_page || $is_role_editor_page || $is_edit_layout_category_page || $is_import_page ) ) ) :
        add_action( $action_hook, 'GWP_Custom_Modules', 9789 );
    endif;
}

Build_GWP_Custom_Modules();
