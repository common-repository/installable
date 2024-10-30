<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Add App Internal Links js File
add_action( 'wp_enqueue_scripts', 'aspwa_internal_links_js', 1, 1 );
function aspwa_internal_links_js()
{
	// Register the script:
	wp_register_script( 'aspwa_internal_links', plugins_url('appinternallinks.js', __FILE__) );
	
	// Enqueue the script:
	wp_enqueue_script( 'aspwa_internal_links' );
}


// Add Meta Tags To Header
function aspwa_pwa_header() {
    
// Get Plugin Settings to $options variable
$options = get_option( 'aspwa_settings' );
    
?>
    <meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

<!-- Add Apple App Title - Either The User Specified One in Settings or The Blog Name -->
<?php 
	if ($options['aspwa_text_field_2'] != '')
	{
?>
	<meta name="apple-mobile-web-app-title" content="<?php echo $options['aspwa_text_field_2']; ?>">	 
<?php   
	}
	else {
?>
	<meta name="apple-mobile-web-app-title" content="<?php echo get_bloginfo( 'name' ); ?>">
<?php
	}
	?>
	

<!-- Add Apple Touch Icon -->
<?php 

$aspwa_the_existing_manifest_version = get_option('aspwa_manifest_version');

	if ($options['aspwa_text_field_5'] != '')
	{
?>
	<link rel="apple-touch-icon" href="<?php echo $options['aspwa_text_field_5'] . '?v=' . $aspwa_the_existing_manifest_version['version_number']; ?>">
<?php   
	}
?>
	
<!-- Manifest added by Installable - https://adaptsites.com - Turn Your Website Into A Progressive Web App -->
<?php
$aspwa_the_existing_manifest_version = get_option('aspwa_manifest_version');
?>
<link rel="manifest" href="<?php echo get_option('siteurl') . '/adaptsites-pwa-manifest.json?v=' . $aspwa_the_existing_manifest_version['version_number']; ?>">
<?php
}
add_action('wp_head', 'aspwa_pwa_header');

//add js file
add_action( 'wp_enqueue_scripts', 'aspwa_addtohome_js', 10 );
function aspwa_addtohome_js()
{
    // Get Plugin Settings to $options variable
    $options = get_option( 'aspwa_settings' );

    if ($options['aspwa_select_field_1'] == 2)
    {
	// Register the script:
	wp_register_script( 'aspwa_ath', plugins_url('add2home.js', __FILE__) );
	
	// Enqueue the script:
	wp_enqueue_script( 'aspwa_ath' );
    }
}

//add css file
add_action( 'wp_enqueue_scripts', 'aspwa_addtohome_css' );
function aspwa_addtohome_css() {
    
    // Get Plugin Settings to $options variable
    $options = get_option( 'aspwa_settings' );

    if ($options['aspwa_select_field_1'] == 2)
    {
	// Register the style:
    wp_register_style( 'aspwa_ath', plugins_url('add2home.css', __FILE__) );
    
	// Register the style:
    wp_enqueue_style( 'aspwa_ath' );
    }
}