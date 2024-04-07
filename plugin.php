<?php
/*
Plugin Name: Safe Redirect
Plugin URI: https://github.com/imydou/yourls-safe-redirect
Description: A security reminder is displayed before redirecting long links, and custom ads can be inserted on the reminder page.
Version: 1.0
Author: diku.tech
Author URI: https://diku.tech/
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Register the 'pre_redirect' hook
yourls_add_action('pre_redirect', 'safe_redirect');

// Hook the admin page into the 'plugins_loaded' event
yourls_add_action( 'plugins_loaded', 'safe_redirect_setting' );

function safe_redirect($args) {
    $url = $args[0]; // The URL to redirect to
    if (str_contains($args[0], YOURLS_SITE)) {
        return;
    }

    $setting_option = yourls_get_option ('safe_redirect_setting');
    if ($setting_option) {
        $setting = unserialize($setting_option);

        // Check user agent
        $user_agent = $setting['user_agent'];
        if ($user_agent) {
            $user_agent = explode("\r\n", $user_agent);
            $is_redirect = false;
            foreach ($user_agent as $ua) {
                if (str_contains($_SERVER['HTTP_USER_AGENT'], $ua)) {
                    $is_redirect = true;
                    break;
                }
            }
            if (!$is_redirect) {
                return;
            }
        }

        if ($setting['relay_domain'] && $setting['relay_domain'] != $_SERVER['HTTP_HOST']) {
            //redirect to relay domain
            header('Location: ' . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? $_SERVER['REQUEST_SCHEME']) . '://' . $setting['relay_domain'] . $_SERVER['REQUEST_URI']);
        }

        // Prevent the default redirection
        yourls_do_action('redirect_shorturl', $url, $args[1]);

        // Check browser language
        $is_chinese = is_chinese();

        // Include your custom redirect page here
        include('redirect_page.php');

        // Stop execution to prevent the default redirection
        die();
    }
    return;
}

// Add admin page
function safe_redirect_setting() {
    yourls_register_plugin_page( 'safe_redirect_setting', 'Safe Redirect Settings', 'safe_redirect_setting_do_page' );
}

// Display admin page
function safe_redirect_setting_do_page() {
    if( isset( $_POST['action'] ) && $_POST['action'] == 'safe_redirect' ) {
        safe_redirect_setting_process();
    }
    safe_redirect_setting_form();
}

// Display form
function safe_redirect_setting_form () {
    $nonce = yourls_create_nonce( 'safe_redirect' ) ;
    $setting_option = yourls_get_option ('safe_redirect_setting','');
    if ($setting_option) {
        $setting = unserialize($setting_option);
    }else{
        $setting = [
            'user_agent' => "Mozilla\r\nChrome\r\nSafari\r\nOpera\r\nFirefox",
            'html' => '',
            'relay_domain' => '',
            'seconds' => 10,
        ];
    }
    $user_agent = $setting['user_agent'];
    $html = $setting['html'];
    $relay_domain = $setting['relay_domain'];
    $seconds = $setting['seconds'];
    echo <<<HTML
		<h2>Safe Redirect Settings</h2>
		<form method="post">

		<input type="hidden" name="action" value="safe_redirect" />
		<input type="hidden" name="nonce" value="$nonce" />

		<p>User-Agent keywords that require safe redirection, one per line. Leave blank to enable all.</p>
		<p><textarea cols="60" rows="15" name="user_agent">$user_agent</textarea></p>

		<p>The additional HTML code inserted into the secure redirect page may be promotional advertising or other.</p>
		<p><textarea cols="60" rows="15" name="html">$html</textarea></p>

		<p>Safe redirect page domain name. This needs to be set when the ad alliance code is only useful on a specific domain name. This domain name needs to be bound to the YOURLS site at the same time.</p>
		<p><input type="text" name="relay_domain" value="$relay_domain"></p>

		<p>Waiting time before redirection, in seconds.</p>
		<p><input type="number" name="seconds" value="$seconds"></p>

		<p><input type="submit" value="Save" /></p>
		</form>
HTML;
}

// Update
function safe_redirect_setting_process () {
    // Check nonce
    yourls_verify_nonce( 'safe_redirect' ) ;

    // Update list
    $setting = [
        'user_agent' => $_POST['user_agent'],
        'html' => $_POST['html'],
        'relay_domain' => $_POST['relay_domain'],
        'seconds' => $_POST['seconds'],
    ];
    yourls_update_option ( 'safe_redirect_setting', serialize($setting) );
    echo "Safe redirect settings updated." ;
}

function is_chinese() {
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        return preg_match('/zh-(CN|TW)/i', $acceptLang);
    }
    return false;
}
