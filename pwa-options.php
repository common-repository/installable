<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'aspwa_add_admin_menu' );
add_action( 'admin_init', 'aspwa_settings_init' );


function aspwa_add_admin_menu(  ) { 

	add_submenu_page( 'options-general.php', 'Adapt Sites Helps You Convert Your Site Into A Progressive Web App', 'Progressive Web App', 'manage_options', 'aspwa', 'aspwa_options_page' );

}


function aspwa_settings_init(  ) {

	register_setting( 'addASPWAPage', 'aspwa_settings' );
	
	// Progressive Web App Settings

	add_settings_section(
		'aspwa_app_section', 
		__( 'Progressive Web App Settings', 'aspwa' ), 
		'aspwa_app_section_callback', 
		'addASPWAPage'
	);
	
	add_settings_field( 
		'aspwa_text_field_2', 
		__( 'App Name', 'aspwa' ), 
		'aspwa_text_field_2_render', 
		'addASPWAPage', 
		'aspwa_app_section' 
	);
	
	add_settings_field( 
		'aspwa_text_field_3', 
		__( 'Short Name', 'aspwa' ), 
		'aspwa_text_field_3_render', 
		'addASPWAPage', 
		'aspwa_app_section' 
	);

	add_settings_field( 
		'aspwa_text_field_4', 
		__( 'App Description', 'aspwa' ), 
		'aspwa_text_field_4_render', 
		'addASPWAPage', 
		'aspwa_app_section' 
	);
	
	// Icon Settings
	
	add_settings_section(
		'aspwa_icon_section', 
		__( 'App Icon Settings', 'aspwa' ), 
		'aspwa_icon_section_callback', 
		'addASPWAPage'
	);
	
	add_settings_field( 
		'aspwa_text_field_5', 
		__( 'Image Icon URL - 192x192 pixels', 'aspwa' ), 
		'aspwa_text_field_5_render', 
		'addASPWAPage', 
		'aspwa_icon_section' 
	);
	
	add_settings_field( 
		'aspwa_text_field_6', 
		__( 'Image Icon URL - 512x512 pixels', 'aspwa' ), 
		'aspwa_text_field_6_render', 
		'addASPWAPage', 
		'aspwa_icon_section' 
	);
	
	// App General Settings
	
	add_settings_section(
		'aspwa_additional_section', 
		__( 'App Settings', 'aspwa' ), 
		'aspwa_additional_section_callback', 
		'addASPWAPage'
	);
	
	add_settings_field( 
		'aspwa_select_field_0', 
		__( 'App Orientation', 'aspwa' ), 
		'aspwa_select_field_0_render', 
		'addASPWAPage', 
		'aspwa_additional_section' 
	);
	
	add_settings_field( 
		'aspwa_text_field_7', 
		__( 'Start Page URL', 'aspwa' ), 
		'aspwa_text_field_7_render', 
		'addASPWAPage', 
		'aspwa_additional_section' 
	);
	
	add_settings_field( 
		'aspwa_text_field_8', 
		__( 'Offline Page URL', 'aspwa' ), 
		'aspwa_text_field_8_render', 
		'addASPWAPage', 
		'aspwa_additional_section' 
	);
	
	add_settings_field( 
		'aspwa_text_field_9', 
		__( 'UTM Source', 'aspwa' ), 
		'aspwa_text_field_9_render', 
		'addASPWAPage', 
		'aspwa_additional_section' 
	);
	
	add_settings_field( 
		'aspwa_text_field_10', 
		__( 'UTM Medium', 'aspwa' ), 
		'aspwa_text_field_10_render', 
		'addASPWAPage', 
		'aspwa_additional_section' 
	);
	
	add_settings_field( 
		'aspwa_text_field_11', 
		__( 'UTM Campaign', 'aspwa' ), 
		'aspwa_text_field_11_render', 
		'addASPWAPage', 
		'aspwa_additional_section' 
	);
	
	// Install Message Settings
	
	add_settings_section(
		'aspwa_install_section', 
		__( 'Install Settings', 'aspwa' ), 
		'aspwa_install_section_callback', 
		'addASPWAPage'
	);
	
	add_settings_field( 
		'aspwa_select_field_1', 
		__( 'Show Progressive Web App Install Message', 'aspwa' ), 
		'aspwa_select_field_1_render', 
		'addASPWAPage', 
		'aspwa_install_section' 
	);

}


function aspwa_select_field_0_render(  ) { 

	$options = get_option( 'aspwa_settings' );
	?>
	<select name='aspwa_settings[aspwa_select_field_0]'>
		<option value='portrait' <?php selected( $options['aspwa_select_field_0'], 'portrait' ); ?>>Portrait</option>
		<option value='landscape' <?php selected( $options['aspwa_select_field_0'], 'landscape' ); ?>>Landscape</option>
		<option value='natural' <?php selected( $options['aspwa_select_field_0'], 'natural' ); ?>>Same As Device</option>
	</select>
	<?php

}

function aspwa_select_field_1_render(  ) { 

	$options = get_option( 'aspwa_settings' );
	?>
	<select name='aspwa_settings[aspwa_select_field_1]'>
		<option value='1' <?php selected( $options['aspwa_select_field_1'], 1 ); ?>>No</option>
		<option value='2' <?php selected( $options['aspwa_select_field_1'], 2 ); ?>>Yes</option>
	</select>
	<?php

}

function aspwa_text_field_2_render(  ) { 

	$options = get_option( 'aspwa_settings' );
	?>
	<input type='text' name='aspwa_settings[aspwa_text_field_2]' value='<?php echo $options['aspwa_text_field_2']; ?>'>
	<?php

}


function aspwa_text_field_3_render(  ) { 

	$options = get_option( 'aspwa_settings' );
	?>
	<input type='text' name='aspwa_settings[aspwa_text_field_3]' value='<?php echo $options['aspwa_text_field_3']; ?>'>
	<?php

}


function aspwa_text_field_4_render(  ) { 

	$options = get_option( 'aspwa_settings' );
	?>
	<input type='text' name='aspwa_settings[aspwa_text_field_4]' value='<?php echo $options['aspwa_text_field_4']; ?>'>
	<?php

}

function aspwa_text_field_5_render(  ) { 

	$options = get_option( 'aspwa_settings' );
	?>
	<input type='text' id="aspwa_the_192_image_url" name='aspwa_settings[aspwa_text_field_5]' value='<?php echo $options['aspwa_text_field_5']; ?>'>
	<input id="aspwa_upload_192_image_button" type="button" class="button-primary" value="Insert Image" />
	<?php

}

function aspwa_text_field_6_render(  ) { 

	$options = get_option( 'aspwa_settings' );
	?>
	<input type='text' id="aspwa_the_512_image_url" name='aspwa_settings[aspwa_text_field_6]' value='<?php echo $options['aspwa_text_field_6']; ?>'>
	<input id="aspwa_upload_512_image_button" type="button" class="button-primary" value="Insert Image" />
	<?php

}

function aspwa_text_field_7_render(  ) { 

	$options = get_option( 'aspwa_settings' );
	?>
	<input type='text' name='aspwa_settings[aspwa_text_field_7]' value='<?php echo $options['aspwa_text_field_7']; ?>'>
	<?php

}

function aspwa_text_field_8_render(  ) { 

	$options = get_option( 'aspwa_settings' );
	?>
	<input type='text' name='aspwa_settings[aspwa_text_field_8]' value='<?php echo $options['aspwa_text_field_8']; ?>'>
	<?php

}

function aspwa_text_field_9_render(  ) {

	$options = get_option( 'aspwa_settings' );
	?>
	<input type='text' name='aspwa_settings[aspwa_text_field_9]' value='<?php echo $options['aspwa_text_field_9']; ?>'>
	<?php

}

function aspwa_text_field_10_render(  ) { 

	$options = get_option( 'aspwa_settings' );
	?>
	<input type='text' name='aspwa_settings[aspwa_text_field_10]' value='<?php echo $options['aspwa_text_field_10']; ?>'>
	<?php

}

function aspwa_text_field_11_render(  ) { 

	$options = get_option( 'aspwa_settings' );
	?>
	<input type='text' name='aspwa_settings[aspwa_text_field_11]' value='<?php echo $options['aspwa_text_field_11']; ?>'>
	<?php

}

function aspwa_app_section_callback(  ) { 

	echo __( 'What would you like to call your progressive web app?', 'aspwa' );

}

function aspwa_icon_section_callback(  ) { 

	echo __( 'What icon(s) would you like to use for your app?', 'aspwa' );

}

function aspwa_additional_section_callback(  ) { 

	echo __( 'Additional App Settings', 'aspwa' );

}

function aspwa_install_section_callback(  ) { 

	echo __( 'Show a message on mobile devices encouraging visitors to install your progressive web app?', 'aspwa' );

}

/**
 * Hook into options page after save.
 */

function aspwa_options_page(  ) {

		?>
		<form action='options.php' method='post'>

			<h2>The Installable Plugin By Adapt Sites Helps You Convert Your Site Into A Progressive Web App</h2>

			<?php
			settings_fields( 'addASPWAPage' );
			do_settings_sections( 'addASPWAPage' );
			submit_button();
			?>

		</form>
		<?php

}
