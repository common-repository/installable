<?php
/**
 * Plugin Name:       Installable
 * Description:       Turn your website into a progressive web app that's installable on phones. Automatically prompt your mobile visitors to install your app.
 * Version:           1.0.0
 * Author:            Adapt Sites
 * Author URI:        https://adaptsites.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       installable
 */
 
 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Add Admin Options page
require_once 'pwa-options.php';

// Add Header Scripts and Styles
require_once 'pwa-header.php';

// Add Footer Scripts and Styles
require_once 'pwa-footer.php';

// Allow Use of WP Media Uploader to Select App Icons
function aspwa_media_uploader_enqueue() {
    	wp_enqueue_media();
    	wp_register_script('aspwa-media-uploader', plugins_url('media-upload.js' , __FILE__ ), array('jquery'));
    	wp_enqueue_script('aspwa-media-uploader');
}
add_action('admin_enqueue_scripts', 'aspwa_media_uploader_enqueue');

/**
 * Initialize the WP filesystem
 */
function aspwa_init_wp_filesystem() {
	
	global $wp_filesystem;
	
	if ( empty( $wp_filesystem ) ) {
		require_once( trailingslashit( ABSPATH ) . 'wp-admin/includes/file.php' );
		WP_Filesystem();
	}
}

function aspwa_write_to_file( $file, $content = null ) {
	
	// Return false if no filename is provided
	if ( empty( $file ) ) {
		return false;
	}
	
	// Initialize the WP filesystem
	aspwa_init_wp_filesystem();
	global $wp_filesystem;
	
	if( ! $wp_filesystem->put_contents( ( trailingslashit( ABSPATH ) . $file ), $content, 0644) ) {
		return false;
	}
	
	return true;
}

function aspwa_get_file_contents( $file, $array = false ) {
	
	// Return false if no filename is provided
	if ( empty( $file ) ) {
		return false;
	}
	
	// Initialize the WP filesystem
	aspwa_init_wp_filesystem();
	global $wp_filesystem;
	
	// Reads entire file into a string
	if ( $array == false ) {
		return $wp_filesystem->get_contents( $file );
	}
	
	// Reads entire file into an array
	return $wp_filesystem->get_contents_array( $file );
}

function aspwa_delete_file( $file ) {
	
	// Return false if no filename is provided
	if ( empty( $file ) ) {
		return false;
	}
	
	// Initialize the WP filesystem
	aspwa_init_wp_filesystem();
	global $wp_filesystem;
	
	return $wp_filesystem->delete( trailingslashit( ABSPATH ) . $file );
}

function aspwa_create_sw_file() {

    aspwa_delete_file( 'adaptsites-pwa-sw.js' );

    // Get Plugin Settings to $options variable
    $options = get_option( 'aspwa_settings' );
    $sw_options = get_option('aspwa_sw_options');
	
	// Start output buffer. Everything from here till ob_get_clean() is returned
	ob_start();  ?>
'use strict';

/**
 * Service Worker of Installable
 * To add one to your website or learn more, visit - https://adaptsites.com
 */

importScripts('<?php echo plugins_url('/workbox/workbox-sw.js', __FILE__); ?>');

workbox.setConfig({
    debug: true,
    cacheName: 'cache-pages',
    modulePathPrefix: '<?php echo plugins_url('/workbox/', __FILE__); ?>'
});

const {NavigationRoute} = workbox.routing;
const {precacheAndRoute} = workbox.precaching;
const {registerRoute} = workbox.routing;
const {NetworkFirst} = workbox.strategies;
const {PrecacheController} = workbox.precaching.PrecacheController;
const {matchPrecache} = workbox.precaching;

workbox.core.skipWaiting();
workbox.core.clientsClaim();

precacheAndRoute([
  {url: '<?php if($sw_options['offline_page'] == '') { echo '/'; } else { echo $sw_options['offline_page']; } ?>', revision: '<?php echo $sw_options['version_number']; ?>'}
], {
  directoryIndex: null,
  cleanUrls: false
});


const offlinePage = '<?php if($sw_options['offline_page'] == '') { echo '/'; } else { echo $sw_options['offline_page']; } ?>';

const networkFirst = new NetworkFirst();
const navigationHandler = async (params) => {
  try {
    // Attempt a network request.
    return await networkFirst.handle(params);
  } catch (error) {
    // If it fails, return the cached HTML.
    return await matchPrecache(offlinePage);
  }
};

// Register this strategy to handle all navigations.
registerRoute(
  new NavigationRoute(navigationHandler)
);
<?php 

$thefilecontents = ob_get_clean();

aspwa_write_to_file( 'adaptsites-pwa-sw.js', $thefilecontents );

}

function aspwa_sw_file_update( ) {
    
add_action('admin_notices', 'aspwa_print_sw_file_updated');
    
}

function aspwa_print_sw_file_updated() {
    $options = get_option( 'aspwa_settings' );
    $sw_options = get_option('aspwa_sw_options');
     ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Service Worker File Updated Successfully', 'installable' ); ?></p>
    </div>
    <?php
}
 
 
function aspwa_sw_file_created( ) {
    
add_action('admin_notices', 'aspwa_print_sw_file_created');
    
}
 
function aspwa_print_sw_file_created() {
     ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Service Worker File Created Successfully', 'installable' ); ?></p>
    </div>
    <?php
}

// Check If Service Worker File Should Be Updated
function aspwa_check_sw_settings(){
    
    // Get Most Recently Inputted Settings
    $options = get_option( 'aspwa_settings' );
    
    if (get_option('aspwa_sw_options') == FALSE){
        
        $aspwa_the_sw_options = array(
        'start_page'=>$options['aspwa_text_field_7'],
        'offline_page'=>$options['aspwa_text_field_8'],
        'previous_version_number'=>1,
        'version_number'=>1,
        'UTM_source'=>$options['aspwa_text_field_9'],
        'UTM_medium'=>$options['aspwa_text_field_10'],
        'UTM_campaign'=>$options['aspwa_text_field_11']
         );

        add_option('aspwa_sw_options', $aspwa_the_sw_options);
        
        aspwa_create_sw_file();
        
        aspwa_sw_file_created();
        
    }
    else
    {
        $sw_options = get_option('aspwa_sw_options');
        
        if ( ($sw_options['start_page'] != $options['aspwa_text_field_7']) || ($sw_options['offline_page'] != $options['aspwa_text_field_8']) || ($sw_options['UTM_source'] != $options['aspwa_text_field_9']) || ($sw_options['UTM_medium'] != $options['aspwa_text_field_10']) || ($sw_options['UTM_campaign'] != $options['aspwa_text_field_11']) ) {
        
        $aspwa_the_sw_options = array(
        'start_page'=>$options['aspwa_text_field_7'],
        'offline_page'=>$options['aspwa_text_field_8'],
        'previous_version_number'=>number_format($sw_options['previous_version_number']),
        'version_number'=>(number_format($sw_options['version_number'])+1),
        'UTM_source'=>$options['aspwa_text_field_9'],
        'UTM_medium'=>$options['aspwa_text_field_10'],
        'UTM_campaign'=>$options['aspwa_text_field_11']
         );
         
        update_option('aspwa_sw_options', $aspwa_the_sw_options);
        
        aspwa_create_sw_file();
        
        aspwa_sw_file_update();
        
        }
    }
    
}

function aspwa_manifest_file_updated( ) {
    
add_action('admin_notices', 'aspwa_print_manifest_file_updated');
    
}
 
function aspwa_print_manifest_file_updated() {
     ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Manifest File Updated Successfully', 'installable' ); ?></p>
    </div>
    <?php
}

function aspwa_manifest_file_created( ) {
    
add_action('admin_notices', 'aspwa_print_manifest_file_created');
    
}
 
function aspwa_print_manifest_file_created() {
     ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Manifest File Created Successfully', 'installable' ); ?></p>
    </div>
    <?php
}

function aspwa_delete_manifest_file() {

    aspwa_delete_file( 'adaptsites-pwa-manifest.json' );

}

function aspwa_create_manifest_file() {

    aspwa_delete_manifest_file();

    // Get Plugin Settings to $options variable
    $manifest_options = get_option('aspwa_manifest_options');

    aspwa_write_to_file( 'adaptsites-pwa-manifest.json', json_encode($manifest_options) );

}

// Check If Manifest File Should Be Updated
function aspwa_check_manifest_version(){
    
        if (get_option('aspwa_manifest_version') == FALSE){
        
        $aspwa_the_manifest_version = array(
        'version_number' => 1
        );
        
        add_option('aspwa_manifest_version', $aspwa_the_manifest_version);
        
        }
        else {
            
        $aspwa_the_existing_manifest_version = get_option('aspwa_manifest_version');
        
        $aspwa_the_new_manifest_version = array(
        'version_number' => (number_format($aspwa_the_existing_manifest_version['version_number'])+1),
        );
        
        update_option('aspwa_manifest_version', $aspwa_the_new_manifest_version);
            
        }
    
}

// Check If Manifest File Should Be Updated
function aspwa_check_manifest_settings(){
    
    // Get Most Recently Inputted Settings
    $options = get_option( 'aspwa_settings' );
    
    if (get_option('aspwa_manifest_options') == FALSE){
        
        $aspwa_the_manifest_options = array(
        'name' => $options['aspwa_text_field_2'],
        'short_name'=>$options['aspwa_text_field_3'],
        'description'=>$options['aspwa_text_field_4'],
        'icons'=>array(
            '0'=>array(
                'src'=>$options['aspwa_text_field_5'],
                'sizes'=>'192x192',
                'purpose'=>'any maskable'
                ),
            '1'=>array(
                'src'=>$options['aspwa_text_field_6'],
                'sizes'=>'512x512',
                'purpose'=>'any maskable'
                )
            ),
        'display'=>'standalone',
        'orientation'=>$options['aspwa_select_field_0'],
        'start_url'=>$options['aspwa_text_field_7'] . '?utm_source=' . $options['aspwa_text_field_9'] . '&utm_medium=' . $options['aspwa_text_field_10'] . '&utm_campaign=' . $options['aspwa_text_field_11']
         );

        add_option('aspwa_manifest_options', $aspwa_the_manifest_options);
        
        aspwa_check_manifest_version();
        
        aspwa_create_manifest_file();
        
        aspwa_manifest_file_created();
        
    }
    else
    {
        $aspwa_manifest_options = get_option('aspwa_manifest_options');
        
        if ( ($aspwa_manifest_options['name'] != $options['aspwa_text_field_2'] ) || ($aspwa_manifest_options['short_name'] != $options['aspwa_text_field_3']) || ($aspwa_manifest_options['description'] != $options['aspwa_text_field_4']) || ($aspwa_manifest_options['icons']['0']['src'] != $options['aspwa_text_field_5']) || ($aspwa_manifest_options['icons']['1']['src'] != $options['aspwa_text_field_6']) || ($aspwa_manifest_options['orientation'] != $options['aspwa_select_field_0'] ) || ($aspwa_manifest_options['start_url'] != ($options['aspwa_text_field_7'] . '?utm_source=' . $options['aspwa_text_field_9'] . '&utm_medium=' . $options['aspwa_text_field_10'] . '&utm_campaign=' . $options['aspwa_text_field_11']) ) ) {
        
        $aspwa_the_manifest_options = array(
        'name' => $options['aspwa_text_field_2'],
        'short_name'=>$options['aspwa_text_field_3'],
        'description'=>$options['aspwa_text_field_4'],
        'icons'=>array(
            '0'=>array(
                'src'=>$options['aspwa_text_field_5'],
                'sizes'=>'192x192',
                'purpose'=>'any maskable'
                ),
            '1'=>array(
                'src'=>$options['aspwa_text_field_6'],
                'sizes'=>'512x512',
                'purpose'=>'any maskable'
                )
            ),
        'display'=>'standalone',
        'orientation'=>$options['aspwa_select_field_0'],
        'start_url'=>$options['aspwa_text_field_7'] . '?utm_source=' . $options['aspwa_text_field_9'] . '&utm_medium=' . $options['aspwa_text_field_10'] . '&utm_campaign=' . $options['aspwa_text_field_11']
         );

        update_option('aspwa_manifest_options', $aspwa_the_manifest_options);
        
        aspwa_check_manifest_version();
        
        aspwa_create_manifest_file();
        
        aspwa_manifest_file_updated();
        
        }
    }

}

add_action('load-settings_page_aspwa', 'aspwa_check_sw_settings');
add_action('load-settings_page_aspwa', 'aspwa_check_manifest_settings');
