<?php
ob_start();
/**
* Plugin Name: Photo Experience Ireland - Photo Retrieval
* Plugin URI: http://www.rossmaguire.com
* Description: Allows users to retrieve and share their photo from photoexperienceireland.ie 
* Version: 1.0
* Author: Ross Maguire (RM Designs)
* Author URI: http://www.rossmaguire.com
*/
/* ///////////////////////////////// ADMIN SETTINGS ////////////////////////////////////// */
//add the menu
function photo_exp_irl_add_admin_menu() { 
	add_menu_page( 'Photo Exp App', 'Photo Exp Settings', 'manage_options', 'photo_experience_ireland', 'photo_exp_irl_options_page' );

}
function load_scripts() {
	wp_enqueue_script( 'jquery', plugins_url( '/js/jquery.js', __FILE__ ));
	wp_enqueue_script( 'custom', plugins_url( '/js/photo.js', __FILE__ ));
	wp_enqueue_script( 'bootstrap', plugins_url( '/js/bootstrap.min.js', __FILE__ ));
	wp_enqueue_style( 'bootstrap', plugins_url( '/css/bootstrap.min.css', __FILE__ ));
	wp_enqueue_style( 'custom', plugins_url( '/css/photo.css', __FILE__ ));
}
//add the fields to the settings page
function photo_exp_irl_settings_init() { 
	register_setting( 'pluginPage', 'photo_exp_irl_settings' );

	add_settings_section(
		'photo_exp_irl_pluginPage_section', 
		__( 'Photo App Config', 'wordpress' ), 
		'photo_exp_irl_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'photo_exp_irl_checkbox_field_1', 
		__( 'Display Facebook button?', 'wordpress' ), 
		'photo_exp_irl_checkbox_field_1_render', 
		'pluginPage', 
		'photo_exp_irl_pluginPage_section' 
	);

	add_settings_field( 
		'photo_exp_irl_checkbox_field_2', 
		__( 'Display Download button?', 'wordpress' ), 
		'photo_exp_irl_checkbox_field_2_render', 
		'pluginPage', 
		'photo_exp_irl_pluginPage_section' 
	);
	
	add_settings_field( 
		'photo_exp_irl_checkbox_field_3', 
		__( 'Display the Survey?', 'wordpress' ), 
		'photo_exp_irl_checkbox_field_3_render', 
		'pluginPage', 
		'photo_exp_irl_pluginPage_section' 
	);
	
	add_settings_field( 
		'photo_exp_irl_textbox_field_1', 
		__( 'Change the intro text (leave blank to reset to default)', 'wordpress' ), 
		'photo_exp_irl_textbox_field_1_render', 
		'pluginPage', 
		'photo_exp_irl_pluginPage_section' 
	);
}
//set up the first checkbox - show FB
function photo_exp_irl_checkbox_field_1_render() { 
	$options = get_option( 'photo_exp_irl_settings' );
	?>
	<input type='checkbox' value='1' name='photo_exp_irl_settings[photo_exp_irl_checkbox_field_1]' <?php if (isset($options['photo_exp_irl_checkbox_field_1']) && (1 == $options['photo_exp_irl_checkbox_field_1'])) echo 'checked="checked"'; ?>>
	<?php
}
//set up the second checkbox - show DL
function photo_exp_irl_checkbox_field_2_render() { 
	$options = get_option( 'photo_exp_irl_settings' );
	?>
	<input type='checkbox' value='1' name='photo_exp_irl_settings[photo_exp_irl_checkbox_field_2]' <?php if (isset($options['photo_exp_irl_checkbox_field_2']) && (1 == $options['photo_exp_irl_checkbox_field_2'])) echo 'checked="checked"'; ?>>
	<?php
}
//set up the second checkbox - show Survey
function photo_exp_irl_checkbox_field_3_render() { 
	$options = get_option( 'photo_exp_irl_settings' );
	?>
	<input type='checkbox' value='1' name='photo_exp_irl_settings[photo_exp_irl_checkbox_field_3]' <?php if (isset($options['photo_exp_irl_checkbox_field_3']) && (1 == $options['photo_exp_irl_checkbox_field_3'])) echo 'checked="checked"'; ?>>
	<?php
}
//set up the textbox - change Intro
function photo_exp_irl_textbox_field_1_render(){
	$options = get_option( 'photo_exp_irl_settings' );
	?>
	<textarea cols='40' rows='5' name='photo_exp_irl_settings[photo_exp_irl_textbox_field_1]'><?php echo $options['photo_exp_irl_textbox_field_1'];?></textarea>
	<?php
}

function photo_exp_irl_settings_section_callback() { 

	echo __( 'Choose which elements of the app to display', 'wordpress' );

}
function photo_exp_irl_options_page() { 
	?>
	<form action='options.php' method='post'>
    <br/>
	<br/>
	<br/>
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
	</form>
	<?php
}
add_action( 'admin_menu', 'photo_exp_irl_add_admin_menu' );
add_action( 'admin_init', 'photo_exp_irl_settings_init' );
/* /////////////////////////////////// RETURN THE CHECKBOX RESULT AND OUTPUT TO JS VARIABLES ///////////////////////////////////////// */
function showFB(){
	$showFB;
	$options = get_option( 'photo_exp_irl_settings' );

	// CHECK IF THE FACEBOOK BUTTON IS SHOWN OR HIDDEN
	if (is_array( $options ) && $options['photo_exp_irl_checkbox_field_1'] == '1' ) {
		$showFB = 'true';
		} else {
		$showFB = 'false';	
		}
		
    return $showFB;	
}

function showDL(){
	$showDL;
	$options = get_option( 'photo_exp_irl_settings' );
    // CHECK IF THE DOWNLOAD BUTTON IS SHOWN OR HIDDEN
	if ($options['photo_exp_irl_checkbox_field_2'] == '1' ) {
		$showDL = 'true';
		} else {
		$showDL = 'false';
		}
	
	return $showDL;	
}

function showSurv(){
	$showSurv;
	$options = get_option( 'photo_exp_irl_settings' );
    // CHECK IF THE DOWNLOAD BUTTON IS SHOWN OR HIDDEN
	if ($options['photo_exp_irl_checkbox_field_3'] == '1' ) {
		$showSurv = 'true';
		} else {
		$showSurv = 'false';
		}
	
	return $showSurv;	
}

function introText() {
	$introText;
	$options = get_option( 'photo_exp_irl_settings' );
	$introText = $options['photo_exp_irl_textbox_field_1'];
	
	return $introText;
}

function display_app() {
        echo '
		<div class="container-fluid">
		<div id="survey" class="modal fade" role="dialog">
		<iframe src="https://docs.google.com/forms/d/e/1FAIpQLSeJo1iRJ9Kmmd_6f-WJ8TvsX30yYxfOxeXFHfsHy0Cnp6p5KQ/viewform?embedded=true" width="760" height="500" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>
        </div>
        <div id="app-row" class="row">
		<div class="col-md-12" id="codeDiv" style="text-align:center;">
				<h2>Find my Photo&nbsp;&nbsp;<i class="fa fa-camera" aria-hidden="true"></i></h2><br>
				<p id="intro">Welcome to the find my photo app!
				<br/>
				Please enter the code you recieved from our photographer to retrieve your photo.
				<br/>
				<i aria-hidden="true" class="fa fa-long-arrow-down"></i>
				<br/>
				</p>
				<input id="theCode" name="codeInput" placeholder="Enter your image code here" style="color: black;" type="text"><br>
				<br>
				<div id="getImage">
					Find my photo!</i>
				</div>
				<p id="errorMsg">
				Oops, we couldnt find that one
				<br/>
				Please try again
				</p>
			</div>
			<div class="col-md-12">
			<div id="imageHolder">
				<img id="yourPhoto" src=""><br>
				<br>
			</div>
			<div id="buttonsHolder">
				<a href="#" id="save"><i aria-hidden="true" class="fa fa-download"></i>&nbsp;&nbsp;Save Photo</a>
				<div id="share">
					<i class="fa fa-facebook fa-2"></i>&nbsp;&nbsp;Share
				</div>
			</div>
			<div id="surveyHolder">
			    <p>Hi there, we hope you were satisfied with this service and your photo!<br/>If you have a few seconds, would you be interested in giving us some <span id="feedback" data-toggle="modal" data-target="#survey">feedback</span>? :)</p>
			</div>
		</div>
		</div>
	</div>
	<script type="text/javascript">      
      var showFacebook = "'.showFB().'"
      var showDownload = "'.showDL().'"
	  var showSurvey   = "'.showSurv().'"
	  var introText = "'.introText().'"
    </script>';
}
/* /////////////////////////////////// DISPLAY THE APP AS SHORTCODE ///////////////////////////////////////// */
add_shortcode( 'photo_app', 'display_app' );
add_action( 'wp_enqueue_scripts', 'load_scripts');
ob_end_flush();