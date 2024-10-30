<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('wp_enqueue_scripts', 'aspwa_footer_scripts');

function aspwa_footer_scripts() {
    
// Get Plugin Settings to $options variable
$options = get_option( 'aspwa_settings' );

// Get Service Worker Options to $sw_options variable
$sw_options = get_option( 'aspwa_sw_options' );

// Add Basic Service Worker Refreshing Script
wp_enqueue_script( 'aspwa-sw', plugins_url('installable-register-sw.js', __FILE__), array(), 1, true );

// If Service Worker Has Been Updated, Unregister Existing Service Workers and Register New One
if($sw_options['previous_version_number'] != $sw_options['version_number']){

wp_add_inline_script( 'aspwa-sw', 'if (location.protocol == "https:" && "serviceWorker" in navigator) {
window.addEventListener("load", function() {
    
console.log("Previous Service Worker Version ' . $sw_options["previous_version_number"] . '");
        
console.log("Current Service Worker Version ' . $sw_options["version_number"] . '");
        
navigator.serviceWorker.getRegistrations().then(function(registrations) {
    for(let registration of registrations) {
        console.log(registration);
        registration.unregister()
    } });
        
navigator.serviceWorker.register( "' . get_option('siteurl') . '/adaptsites-pwa-sw.js?ver=' . $sw_options['version_number'] . '" )
.then(function(registration) { console.log("Installable service worker ready" + registration.scope); registration.update(); })
.catch(function(error) { console.log("Registration failed with " + error); });

});
}', 'before' );
      
$aspwa_the_sw_options = array(
        'start_page'=>$options['aspwa_text_field_7'],
        'offline_page'=>$options['aspwa_text_field_8'],
        'previous_version_number'=>number_format($sw_options['version_number']),
        'version_number'=>number_format($sw_options['version_number']),
        'UTM_source'=>$options['aspwa_text_field_9'],
        'UTM_medium'=>$options['aspwa_text_field_10'],
        'UTM_campaign'=>$options['aspwa_text_field_11']
         );
         
update_option('aspwa_sw_options', $aspwa_the_sw_options);
      
}
else {
    
wp_add_inline_script( 'aspwa-sw', 'if (location.protocol == "https:" && "serviceWorker" in navigator) {
window.addEventListener("load", function() {
        
console.log("Current Service Worker Version ' . $sw_options["version_number"] . '");
        
navigator.serviceWorker.register( "' . get_option('siteurl') . '/adaptsites-pwa-sw.js?ver=' . $sw_options['version_number'] . '" )
.then(function(registration) { console.log("Installable service worker ready" + registration.scope); registration.update(); })
.catch(function(error) { console.log("Registration failed with " + error); });

});
}', 'before' );
    
}
}