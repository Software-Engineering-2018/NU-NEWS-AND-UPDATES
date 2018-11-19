=== Loginizr ===
Contributors: tifosi
Donate link: tbc
Tags: login, customizer
Requires at least: 4.0
Tested up to: 4.3.1
Stable tag: 1.0.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Control the look & feel of your WordPress login page through the WordPress Customizer.

== Description ==
This plugin allows the user to completely customise the look and feel of the WordPress login page from the comfort of the WordPress admin customizer interface.

- Dynamically creates a custom login css file to override default values
- Fully integrated with WordPress Customiser
- Intuitive interface with sections & settings
- Filters to override defaults via your parent/child theme

== Frequently Asked Questions ==
Support via github: https://github.com/ontiuk/Loginizr/

== Installation ==
1a. Upload the plugin folder to the /wp-content/plugins/ directory. Activate the plugin through the 'Plugins' menu in WordPress OR/
1.b. Upload the plugin zip file via the WordPress plugin interface, either by uploading the dowloaded zip file, or via installing directly from the WordPress repository.

The Login Customizer panel will then be available via the Appearance > Customize option to all users with at least edit_theme_options permission.

== Upgrade Notice == 
Download the update and follow the install instructions, overwriting the files. Future versions via WordPress admin UI.

== Screenshots == 
Coming soon.

=== Usage ===
1. Go to Appearance > Customise
2. Select the Login Customiser Menu Panel
3. Select from the available sections and change the settings as required. Once changed the save & publish button will be available. Select this to save changes. These will be available to the login screen when next logging in, or as below.

The plugin dynamically creates a new login css file for the login page only which contains css settings that override the default styling.

The login panel of course is only available to non logged-in users, so testing the changes can be done via logging out / in, or better via using a separate computer, or via testing with a different browser e.g. logged in via IE, not logged in on chrome.

== Filters ==
The default values displayed in the Admin UI customiser can be modified via a selection of filters.

Panel:
loginizr_panel - Panel name, default: lognizr_panel
add_filter ( 'loginizr_panel', set_loginizr_panel, 10, 1);
function set_loginizr_panel ( $default ) {
	return 'loginizr';
}

loginizr_priority - Panel priority, default: 30
add_filter ( 'loginizr_priority', set_loginizr_priority, 10, 1);
function set_loginizr_priority ( $default ) {
	return 40; // put lower down order
}

loginizr_title - Panel title, default: Login Customizer
add_filter( 'loginizr_title',set_loginizr_title, 10, 1);
function set_loginizr_title ( $title ) {
	return __( 'Loginizr', 'wordpress' ); 
}

loginizr_description - Panel description, default: Customize the generic WordPress login page
add_filter( 'loginizr_description', set_loginizr_description, 10, 1);
function set_loginizr_title ( $desc ) {
	return __( 'Customize your WordPress Login Page', 'wordpress' ); 
}

Section: Each section has a filter for priority & title

add_filter( 'loginizr_section_priority', 'set_loginizr_section_priority', 10, 2 );
function set_loginizr_section_priority( $priority, $section = '' ) {

	// basic check, can also add check against set section names
	if ( empty( $section ) ) { return $priority; } 

	//check section name
	switch( $section ) {
		case 'background':
			return 5;
		case 'logo':
			return 10;
		case 'form_bg':
			return 15;
		case 'form':
			return 20;
		case 'field':
			return 25;
		case 'button':
			return 30;
		case 'other':
			return 35;
		default:
			return $priority;
	}	
}

add_filter( 'loginizr_section_title', 'set_loginizr_section_title', 10, 2 );
function set_loginizr_section_title( $title, $section = '' ) {

	// basic check, can also add check against set section names
	if ( empty( $section ) ) { return $title; } 

	//check section name
	switch( $section ) {
		case 'background':
			return __( 'Background', 'txt' );
		case 'logo':
			return __( 'Logo', 'txt' );
		case 'form_bg':
			return __( 'Form Background', 'txt' );
		case 'form':
			return __( 'Form', 'txt' );
		case 'field':
			return __( 'Field', 'txt' );
		case 'button':
			return __( 'Button', 'txt' );
		case 'other':
			return __( 'Other', 'txt' );
		default:
			return $title;
	}	
}

Settings: Some settings with default values also have available filters

add_filter ( 'loginizr_bg_color', set_loginizr_bg_color, 10, 1);
function set_loginizr_priority ( $default ) {
	return '#fff'; // valid hex or rbg value, default #f1f1f1
}

add_filter ( 'loginizr_logo_width', set_loginizr_logo_width, 10, 1);
function set_loginizr_logo_width ( $default ) {
	return '100px'; //default 125px
}

add_filter ( 'loginizr_logo_height', set_loginizr_logo_height, 10, 1);
function set_loginizr_logo_height ( $default ) {
	return '60px'; //default 50px
}

add_filter ( 'loginizr_logo_padding', set_loginizr_logo_padding, 10, 1);
function set_loginizr_logo_padding ( $default ) {
	return '10px'; //default 5px
}

add_filter ( 'loginizr_form_bg_color', set_loginizr_form_bg_color, 10, 1);
function set_loginizr_form_bg_color ( $default ) {
	return '#fff'; // calid hex or rgb value, default #fff
}

add_filter ( 'loginizr_form_width', set_loginizr_form_width, 10, 1);
function set_loginizr_form_width ( $default ) {
	return '400px'; //valid css width default 320px 
}

add_filter ( 'loginizr_form_height', set_loginizr_form_height, 10, 1);
function set_loginizr_form_height ( $default ) {
	return '100px'; //default 194px
}

add_filter ( 'loginizr_form_padding', set_loginizr_form_padding, 10, 1);
function set_loginizr_form_padding ( $default ) {
	return '10px'; //calid padding css, default 26px 24px 46px
}

add_filter ( 'loginizr_field_width', set_loginizr_field_width, 10, 1);
function set_loginizr_field_width ( $default ) {
	return '90%'; // percentage or px value, default 100%
}

add_filter ( 'loginizr_field_margin', set_loginizr_field_margin, 10, 1);
function set_loginizr_field_margin ( $default ) {
	return '10px'; // valid margin: css , default 2px 6px 16px 0px
}

add_filter ( 'loginizr_field_bg', set_loginizr_field_bg, 10, 1);
function set_loginizr_field_bg ( $default ) {
	return '#ccc'; // valid css hex/rgb, default #fff 
}

add_filter ( 'loginizr_field_color', set_loginizr_field_color, 10, 1);
function set_loginizr_field_color ( $default ) {
	return '#ccc'; //valid hex/rgb, default #333
}

add_filter ( 'loginizr_field_label_color', set_loginizr_field_label_color, 10, 1);
function set_loginizr_field_label_color ( $default ) {
	return '#ccc'; //valid hex/rgb, default #777
}

add_filter ( 'loginizr_button_color', set_loginizr_buttoncolor, 10, 1);
function set_loginizr_button_color ( $default ) {
	return '#ccc'; //valid hex/rgb, default #fff
}

add_filter ( 'loginizr_button_bg', set_loginizr_button_bg, 10, 1);
function set_loginizr_button_bg ( $default ) {
	return '#ccc'; //valid hex/rgb, default #2EA2CC 
}

add_filter ( 'loginizr_button_border', set_loginizr_button_border, 10, 1);
function set_loginizr_button_border ( $default ) {
	return '#ccc'; //valid hex/rgb, default #0074A2
}

add_filter ( 'loginizr_button_hover_bg', set_loginizr_button_hover_bg, 10, 1);
function set_loginizr_button_hover_bg ( $default ) {
	return '#ccc'; //valid hex/rgb, default #1E8CBE
}

add_filter ( 'loginizr_button_hover_border', set_loginizr_button_hover_border, 10, 1);
function set_loginizr_button_hover_border ( $default ) {
	return '#ccc'; //valid hex/rgb, default #0074A2
}

add_filter ( 'loginizr_button_hover_shadow', set_loginizr_button_hover_shadow, 10, 1);
function set_loginizr_button_hover_shadow ( $default ) {
	return '#ccc'; //valid hex/rgb, default #78C8E6 
}

add_filter ( 'loginizr_other_color', set_loginizr_other_color, 10, 1);
function set_loginizr_other_color ( $default ) {
	return '#fff'; //valid hex/rgb, default #999 
}

add_filter ( 'loginizr_other_hover', set_loginizr_other_hover, 10, 1);
function set_loginizr_other_hover ( $default ) {
	return '#fff'; //valid hex/rgb, default #2EA2CC
}

== Changelog ===

= 1.0.0 =
* Initial release
