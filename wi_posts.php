<?php
/*
Plugin Name:        wi_post_Posts
Description:        Create Multiple Pages with one Template <a href="https://www.paypal.com/donate?hosted_button_id=QU2M5UYRMVTSC">&#10052; Donate &#10052;</a>
Author:             wi_post_VOID 
Plugin URI:         https://wordpress.org/plugins/wi-posts/
Author URI:         https://www.paypal.com/donate?hosted_button_id=QU2M5UYRMVTSC
Version:            1.0.1
Requires at least:  5.8
Requires PHP:       7.2
License:            GPL v2 or later
Domain Path:        /languages/
*/

function wi_post_udp_load_textdomain() {
    load_plugin_textdomain( 'wi_post_language', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'wi_post_udp_load_textdomain' ); 

function wi_post_setup_menu(){

	$user = wp_get_current_user();
	$user_roles=( array ) $user->roles;

    if (in_array("administrator", $user_roles) || in_array("wi_post_role", $user_roles)){

        require_once( 'includes/Menu.php' );

        add_menu_page( 'wi_Posts', 'wi_Posts', 'read', 'Post-Menu', 'wi_post_PostMenu', 'dashicons-plus-alt', 5 );
            
        add_submenu_page( 'Post-Menu', 'wi_Templates', 'wi_post_Templates', 'read', 'templates', 'wi_post_TemplateMenu');
            
        add_submenu_page( 'Post-Menu', 'wi_Repeater', 'wi_post_Repeater', 'read', 'repeater', 'wi_post_RepeaterMenu');
            
        add_submenu_page( 'Post-Menu', 'wi_Placeholder', 'wi_post_Placeholder', 'read', 'placeholder', 'wi_post_PlaceholderMenu');

    }
}


add_action('admin_menu', 'wi_post_setup_menu');


function wi_post_onActivation(){

    require_once( 'includes/Setup.php' );
    wi_post_Setup();
    wi_post_Role();

}

register_activation_hook( __FILE__, 'wi_post_onActivation' );




add_action("admin_head","wi_post_load_custom_wp_tiny_mce");
function wi_post_load_custom_wp_tiny_mce() {

if (function_exists('wp_tiny_mce')) {

  add_filter('teeny_mce_before_init', create_function('$a', '
    $a["theme"] = "advanced";
    $a["skin"] = "wp_theme";
    $a["height"] = "200";
    $a["width"] = "800";
    $a["onpageload"] = "";
    $a["mode"] = "exact";
    $a["elements"] = "intro";
    $a["editor_selector"] = "mceEditor";
    $a["plugins"] = "safari,inlinepopups,spellchecker,textcolor";

    $a["forced_root_block"] = false;
    $a["force_br_newlines"] = true;
    $a["force_p_newlines"] = false;
    $a["convert_newlines_to_brs"] = true;

    return $a;'));

    wp_tiny_mce(true);
    }
}
?>