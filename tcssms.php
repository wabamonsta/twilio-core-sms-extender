<?php

/**
 
 * @package twilioCoreSMSExtender
 
 */

/*
 
Plugin Name: Twilio Core SMS Extender
Plugin URI:  https://github.com/wabamonsta
Description: SMS is a plugin used to send text message to users with link to download page
Version: 1.0.7
Author: Jermaine Byfield
Author URI: https://github.com/wabamonsta
License: GPLv2 or later
 
*/

//Check if twillio core is a active plugin
$wp_twillio = 0;
define('sms_currentDir', plugin_dir_url(__FILE__));
$active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
if (in_array('wp-twilio-core/core.php', $active_plugins)) {
    // Plugin is active
    $wp_twillio = 1;
} else {

    function tcs_sms_admin_notice()
    {
        echo "<div class='notice notice-error is-dismissible'>Twilio Core plugin is required in order to use tcs SMS plugin</div>";
    }
    add_action('admin_notices', 'tcs_sms_admin_notice');
}


function wp_sms_tel_assets()
{
    wp_enqueue_style('telephone-mask', sms_currentDir . "/css/intlTelInput.css");
    wp_enqueue_script('script', sms_currentDir . "/js/intlTelInput-jquery.min.js", array('jquery'));
}
add_action('wp_enqueue_scripts', 'wp_sms_tel_assets');

function tcs_sms_shortcode($atts = array(), $content = null, $tag = '')
{
    $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
    if (!in_array('wp-twilio-core/core.php', $active_plugins)) {
        echo   tcs_sms_admin_notice();
        exit;
    }

    extract($atts);

    if (isset($_POST['sms_num']) && $_POST['sms_num'] != null) {
        // Send message 
        if(strpos($_POST['sms_num'],$_POST['countrycode'],-7)!==false){
            $number = $_POST['sms_num'];
        }else{
        $number =  $_POST['countrycode'].$_POST['sms_num'];
        }
        $args = array(
            'number_to' =>"+".$number,
            'message' => $message,
        );
        if (twl_send_sms($args)) {
            if (isset($redirect) && $redirect != null) {
                $url = $redirect;
                wp_redirect($url);
            } else {
                $url = get_site_url();
                wp_redirect($url);
            }
        }
    }
    return   '
        <form method="post" id="tc_sms" >
        <input type="hidden" id="countrycode" name="countrycode">
        <div><input type="tel" placeholder="" id="tcs_telephone" name="sms_num"></div>
        <div><input type="submit" value="' . $buttonvalue . '" ></div>
        </form>
        <script>
        jQuery(document).ready(function(){
        jQuery("#tcs_telephone").on("blur focus",function(){
            var str = jQuery(".iti__selected-dial-code").html();
            jQuery("#countrycode").val(str.replace("+",""));
        })
        jQuery("#tcs_telephone").intlTelInput({
            allowDropdown:true,
            separateDialCode:true,
            autoPlaceholder:"polite",
            initialCountry: "auto",
            geoIpLookup: function(success, failure) {
                jQuery.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                  var countryCode = (resp && resp.country) ? resp.country : "us";
                  success(countryCode);
                });
              },

            utilsScript:"' . sms_currentDir . '/js/utils.js' . '"

        });
        });
            
        </script>
    ';
}
add_shortcode('tcs_sms', 'tcs_sms_shortcode');
