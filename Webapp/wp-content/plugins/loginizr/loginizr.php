<?php

/*
Plugin Name: Loginizr
Plugin URI: http://on.tinternet.co.uk
Description: Customise the WordPress login page from the admin customizer interface
Version: 1.0.0
Author: ontiuk
Author URI: http://on.tinternet.co.uk
Text Domain: loginizr

------------------------------------------------------------------------
Copyright 2015 OnTiUK.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses.
*/

// Access restriction
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

// Add plugin defines
define( 'LOGINIZR_VERSION', '1.0.0' );
define( 'LOGINIZR_WP_VERSION', '4.0' );

/**
 * Loginizr 
 * ==========
 *
 * @desc    Customize the WordPress login screen from the Admin UI Customizer 
 * @package WordPress
 * @author  OnTiUK
 */
final class Loginizr {

    /**
     * Loginizr panel name
     *
     * @var     string
     * @access  private
     */
    private $panel = 'loginizr_panel';

    /**
     * Default Loginizr panel priority
     *
     * @var     integer
     * @access  private
     */
    private $priority = 30;

    /**
     * Text domain id
     *
     * @var     string
     * @access  private
     */
    private $text = 'loginizr';

    /**
     * Class constructor
     *
     * @access public
     */
    public function __construct() {

        // Check WP Version
        if ( version_compare( get_bloginfo( 'version' ), LOGINIZR_WP_VERSION, '<' ) ) {
            add_action( 'admin_notices', array( $this, 'version_notice' ) );
            return;
        }

        // Set login panel id
        $this->panel = apply_filters( 'loginizr_panel', __( $this->panel, $this->text ) );

        // Set up customizer menu
        add_action( 'customize_register', array( $this, 'register_menu' ) );

        // Process login page css & force head placement: https://core.trac.wordpress.org/ticket/33922
        add_action( 'login_enqueue_scripts', array( $this, 'register_css' ), 10 );
        add_action( 'login_enqueue_scripts', 'wp_print_styles', 11 );
        
        // Login page header url
        add_filter( 'login_headerurl', array( $this, 'logo_url' ) );
        
        // Login page header title
        add_filter( 'login_headertitle', array( $this, 'logo_url_title' ) );

        // Login page remember me?
       	add_action( 'init', array( $this, 'login_remember_me' ) );

        // Simplified login messages
    	add_filter( 'login_errors', array( $this, 'login_custom_error_message' ) );

        // Dynamic login css ajax call
        add_action( 'wp_ajax_loginizr_css', array( $this, 'login_css' ) );
        add_action( 'wp_ajax_nopriv_loginizr_css', array( $this, 'login_css' ) );
    }

    /**
     * Add main login css dynamically 
     *
     * @acess public
     */
    public function login_css() {
        require_once( plugin_dir_path( __FILE__ ) . 'css/loginizr.css.php' );
        exit;
    }

    /**
     * Main register function
     *
     * @param   object $wp_customizer
     * @access  public
     */
    public function register_menu( $wp_customizer ) {

        // Set up the loginizr panel
        $this->panels( $wp_customizer );

        // Add the sections & controls to the loginizr panel
        $this->sections( $wp_customizer );
    }

    /*************************************/
    /**  Core Functionality             **/
    /*************************************/

    /**
     * Loginizr Panel
     *
     * @param   object  $wpc    WP_Customiser
     * @access  protected
     */
    protected function panels( $wpc ) { 

        //add loginizr panel
        $wpc->add_panel( $this->panel, array(
            'priority'       => apply_filters( 'loginizr_priority', $this->priority ),
            'capability'     => 'edit_theme_options',
            'title'          => apply_filters( 'loginizr_title', __( 'Login Customizer', $this->text ) ),
            'description'    => apply_filters( 'loginizr_description', __( 'Customize the generic WordPress login page.', $this->text ) )
        ) );
    }

    /**
     * Add the Loginizr sections to the Loginize panel
     *
     * @param   object  $wpc    WP_Customiser
     * @access  protected    
     */
    protected function sections( $wpc ) { 

        /*********************************/
        /**  Sections                   **/
        /*********************************/

        // Background Section
        $wpc->add_section( 'loginizr_background_section', array(
            'priority'      => apply_filters( 'loginizr_section_priority', 5, 'background' ),
            'title'         => apply_filters( 'loginizr_section_title', __('Background', $this->text), 'background' ),
            'description'   => 'Set the login page background color and add a full screen or positioned background image.',
            'panel'         => $this->panel
        ) );

        // Logo Section
        $wpc->add_section( 'loginizr_logo_section', array(
            'priority'      => apply_filters( 'loginizr_section_priority', 10, 'logo' ),
            'title'         => apply_filters( 'loginizr_section_title', __('Logo', $this->text), 'logo' ),
            'description'   => 'Change the default logo to a custom image. Set the size and position relative to the login form.', 
            'panel'         => $this->panel
        ) );

        // Form Background Section
        $wpc->add_section( 'loginizr_form_bg_section', array(
            'priority'      => apply_filters( 'loginizr_section_priority', 15, 'form_bg' ),
            'title'         => apply_filters( 'loginizr_section_title', __('Form Background', $this->text), 'form_bg' ),
            'description'   => 'Set the login form background color and add a positioned background image.',
            'panel'         => $this->panel
        ) );

        // Form Section
        $wpc->add_section( 'loginizr_form_section', array(
            'priority'      => apply_filters( 'loginizr_section_priority', 20, 'form' ),
            'title'         => apply_filters( 'loginizr_section_title', __('Form Styling', $this->text), 'form' ),            
            'description'   => 'Control the size and layout of the login form.',
            'panel'         => $this->panel
        ) );

        // Field Section
        $wpc->add_section( 'loginizr_field_section', array(
            'priority'      => apply_filters( 'loginizr_section_priority', 25, 'field' ),
            'title'         => apply_filters( 'loginizr_section_title', __('Fields Styling', $this->text), 'field' ),
            'description'   => 'Control the display style of the form input fields.',
            'panel'         => $this->panel
        ) );

        // Button Format Section
        $wpc->add_section( 'loginizr_button_section', array(
            'priority'      => apply_filters( 'loginizr_section_priority', 30, 'button' ),
            'title'         => apply_filters( 'loginizr_section_title', __('Button Styling', $this->text), 'button' ),
            'description'   => 'Control the display style and interactivity of the form buttons.',
            'panel'         => $this->panel
        ) );

        // Other Format Section
        $wpc->add_section( 'loginizr_other_section', array(
            'priority'      => apply_filters( 'loginizr_section_priority', 35, 'other' ),
            'title'         => apply_filters( 'loginizr_section_title', __( 'Other', $this->text ), 'other' ),
            'description'   => 'Add custom css styling and set some basic styling & checkbox defaults.',
            'panel'         => $this->panel
        ) );

        /*************************************/
        /**  Background Settings & Controls **/
        /*************************************/

        // Background Image Setting
        $wpc->add_setting( 'loginizr_bg_image', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Background Image Control
        $wpc->add_control( new WP_Customize_Image_Control( $wpc, 'loginizr_bg_image', array(
            'label'     => __( 'Background Image', $this->text),
            'section'   => 'loginizr_background_section',
            'priority'  => 5,
            'settings'  => 'loginizr_bg_image'
        ) ) );

       // Background Color Setting
       $wpc->add_setting( 'loginizr_bg_color', array(
            'default'       => apply_filters( 'loginizr_bg_color', '#F1F1F1' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Background Color Control
        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_bg_color', array(
            'label'     => __( 'Background Color', $this->text ),
            'section'   => 'loginizr_background_section',
            'priority'  => 10,
            'settings'  => 'loginizr_bg_color'
        ) ) );

        // Background Size Setting
        $wpc->add_setting( 'loginizr_bg_size', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Background Size Setting
        $wpc->add_control( 'loginizr_bg_size', array(
            'label'     => __( 'Background Size', $this->text ),
            'section'   => 'loginizr_background_section',
            'priority'  => 15,
            'settings'  => 'loginizr_bg_size',
            'description'   => __( 'cover|contain|px px|px', $this->text )
        ) );

        // Background Position Setting
        $wpc->add_setting( 'loginizr_bg_position', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Background Position Setting
        $wpc->add_control( 'loginizr_bg_position', array(
            'label'     => __( 'Background Position', $this->text ),
            'section'   => 'loginizr_background_section',
            'priority'  => 20,
            'settings'  => 'loginizr_bg_position',
            'description'   => __( 'px values|percentages|keywords', $this->text )
        ) );

        // Background Repeat Setting
        $wpc->add_setting( 'loginizr_bg_repeat', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Background Size Setting
        $wpc->add_control( 'loginizr_bg_repeat', array(
            'label'     => __( 'Background Repeat', $this->text ),
            'section'   => 'loginizr_background_section',
            'priority'  => 25,
            'settings'  => 'loginizr_bg_repeat',
            'description'   => __( 'repeat|repeat-x|repeat-y|no-repeat', $this->text )
        ) );

        /*************************************/
        /**  Logo Settings & Controls       **/
        /*************************************/

        // Logo Image Settings
        $wpc->add_setting('loginizr_logo', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ));

        // Logo Image Control
        $wpc->add_control( new WP_Customize_Image_Control( $wpc, 'loginizr_logo', array(
            'label'     => __( 'Login Logo', $this->text ),
            'section'   => 'loginizr_logo_section',
            'priority'  => 5,
            'settings'  => 'loginizr_logo'
        ) ) );

        // Logo Width Setting
        $wpc->add_setting( 'loginizr_logo_width', array(
            'default'       => apply_filters( 'loginizr_logo_width', '125px' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Logo Width Control
        $wpc->add_control( 'loginizr_logo_width', array(
            'label'     => __( 'Logo Width', $this->text ),
            'section'   => 'loginizr_logo_section',
            'priority'  => 10,
            'settings'  => 'loginizr_logo_width'
        ));

        // Logo Height Setting
        $wpc->add_setting( 'loginizr_logo_height', array(
            'default'       => apply_filters( 'loginizr_logo_height', '50px' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Logo Height Control
        $wpc->add_control( 'loginizr_logo_height', array(
            'label'     => __( 'Logo Height', $this->text ),
            'section'   => 'loginizr_logo_section',
            'priority'  => 15,
            'settings'  => 'loginizr_logo_height'
        ) );

        // Logo Padding Setting
        $wpc->add_setting( 'loginizr_logo_padding', array(
            'default'       => apply_filters( 'loginizr_logo_padding', '5px' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Logo Padding Control
        $wpc->add_control( 'loginizr_logo_padding', array(
            'label'     => __( 'Padding Bottom', $this->text ),
            'section'   => 'loginizr_logo_section',
            'priority'  => 20,
            'settings'  => 'loginizr_logo_padding'
        ) );

        // Background Size Setting
        $wpc->add_setting( 'loginizr_logo_size', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Background Size Setting
        $wpc->add_control( 'loginizr_logo_size', array(
            'label'     => __( 'Background Size', $this->text ),
            'section'   => 'loginizr_logo_section',
            'priority'  => 25,
            'settings'  => 'loginizr_logo_size',
            'description'   => __( 'cover|contain|px px|px', $this->text )
        ) );

        // Background Position Setting
        $wpc->add_setting( 'loginizr_logo_position', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Background Position Setting
        $wpc->add_control( 'loginizr_logo_position', array(
            'label'     => __( 'Background Position', $this->text ),
            'section'   => 'loginizr_logo_section',
            'priority'  => 30,
            'settings'  => 'loginizr_logo_position',
            'description'   => __( 'px values|percentages|keywords', $this->text )
        ) );

        // Background Repeat Setting
        $wpc->add_setting( 'loginizr_logo_repeat', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Background Size Setting
        $wpc->add_control( 'loginizr_logo_repeat', array(
            'label'     => __( 'Background Repeat', $this->text ),
            'section'   => 'loginizr_logo_section',
            'priority'  => 35,
            'settings'  => 'loginizr_logo_repeat',
            'description'   => __( 'repeat|repeat-x|repeat-y|no-repeat', $this->text )
        ) );

        /*********************************************/
        /**  Form Background Settings & Controls    **/
        /*********************************************/

        // Form Background Image Setting
        $wpc->add_setting( 'loginizr_form_bg_image', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Form Background Image Control
        $wpc->add_control( new WP_Customize_Image_Control( $wpc, 'loginizr_form_bg_image', array(
            'label'     => __( 'Background Image', $this->text ),
            'section'   => 'loginizr_form_bg_section',
            'priority'  => 5,
            'settings'  => 'loginizr_form_bg_image'
        ) ) );

        // Form Background Color Setting
        $wpc->add_setting( 'loginizr_form_bg_color', array(
            'default'       => apply_filters( 'loginizr_form_bg_color', '#FFF' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Form Background Color Control
        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_form_bg_color', array(
            'label'     => __( 'Background Color', $this->text ),
            'section'   => 'loginizr_form_bg_section',
            'priority'  => 10,
            'settings'  => 'loginizr_form_bg_color'
        ) ) );

        /*************************************/
        /** Form Settings & Controls        **/
        /*************************************/

        // Form Width Setting
        $wpc->add_setting( 'loginizr_form_width', array(
            'default'       => apply_filters( 'loginizr_form_width', '320px' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Form Width Control
        $wpc->add_control( 'loginizr_form_width', array(
            'label'     => __( 'Width', $this->text ),
            'section'   => 'loginizr_form_section',
            'priority'  => 15,
            'settings'  => 'loginizr_form_width'
        ) );

        // Form Height Setting
        $wpc->add_setting( 'loginizr_form_height', array(
            'default'       => apply_filters( 'loginizr_form_height', '194px' ),
            'type'          => 'option',
            'capability'    => 'edit_theme_options'
        ) );

        // Form Height Control
        $wpc->add_control( 'loginizr_form_height', array(
            'label'     => __( 'Height', $this->text ),
            'section'   => 'loginizr_form_section',
            'priority'  => 20,
            'settings'  => 'loginizr_form_height'
        ) );

        // Form Padding Setting
        $wpc->add_setting( 'loginizr_form_padding', array(
            'default'       => apply_filters( 'loginizr_form_padding', '26px 24px 46px' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Form Padding Control
        $wpc->add_control( 'loginizr_form_padding', array(
            'label'     => __( 'Padding', $this->text ),
            'section'   => 'loginizr_form_section',
            'priority'  => 25,
            'settings'  => 'loginizr_form_padding'
        ) );

        // Form Border Setting
        $wpc->add_setting( 'loginizr_form_border', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Form Border Control
        $wpc->add_control( 'loginizr_form_border', array(
            'label'     => __( 'Border : 2px style color', $this->text ),
            'section'   => 'loginizr_form_section',
            'priority'  => 30,
            'settings'  => 'loginizr_form_border'
        ) );

        /*****************************************/
        /**  Field Settings & Controls          **/
        /*****************************************/

        // Field Width Setting
        $wpc->add_setting( 'loginizr_field_width', array(
            'default'       => apply_filters( 'loginizr_field_width', '100%' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Field Width Control
        $wpc->add_control( 'loginizr_field_width', array(
            'label'     => __( 'Input Field Width', $this->text ),
            'section'   => 'loginizr_field_section',
            'priority'  => 5,
            'settings'  => 'loginizr_field_width'
        ) );

        // Field Margin Setting
        $wpc->add_setting( 'loginizr_field_margin', array(
            'default'       => apply_filters( 'loginizr_field_margin', '2px 6px 16px 0px' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Field Margin Control
        $wpc->add_control( 'loginizr_field_margin', array(
            'label'     => __( 'Input Field Margin', $this->text ),
            'section'   => 'loginizr_field_section',
            'priority'  => 10,
            'settings'  => 'loginizr_field_margin'
        ) );

        // Field Background Setting
        $wpc->add_setting( 'loginizr_field_bg', array(
            'default'       => apply_filters( 'loginizr_field_bg', '#FFF' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Field Background Control
        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_field_bg', array(
            'label'     => __( 'Input Field Background', $this->text ),
            'section'   => 'loginizr_field_section',
            'priority'  => 15,
            'settings'  => 'loginizr_field_bg'
        ) ) );

        // Field Color Setting
        $wpc->add_setting( 'loginizr_field_color', array(
            'default'       => apply_filters( 'loginizr_field_color', '#333' ),
            'type'          => 'option',
            'capability'    => 'edit_theme_options'
        ) );

        // Field Color Control
        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_field_color', array(
            'label'         => __( 'Input Field Color', $this->text ),
            'section'       => 'loginizr_field_section',
            'priority'      => 20,
            'settings'      => 'loginizr_field_color'
        ) ) );

        // Field Label Setting
        $wpc->add_setting( 'loginizr_field_label_color', array(
            'default'       => apply_filters( 'loginizr_field_label_color', '#777' ),
            'type'          => 'option',
            'capability'    => 'edit_theme_options'
        ) );

        // Field Label Control
        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_field_label_color', array(
            'label'     => __( 'Field Label Color', $this->text ),
            'section'   => 'loginizr_field_section',
            'priority'  => 25,
            'settings'  => 'loginizr_field_label_color'
        ) ) );

        /*****************************************/
        /**  Button Settings & Controls         **/
        /*****************************************/

        // Button Color Setting
        $wpc->add_setting( 'loginizr_button_color', array(
            'default'       => apply_filters( 'loginizr_button_color', '#FFF' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Button Color Control
        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_button_color', array(
            'label'     => __( 'Button Color', $this->text ),
            'section'   => 'loginizr_button_section',
            'priority'  => 30,
            'settings'  => 'loginizr_button_color'
        ) ) );

        // Button Background Setting
        $wpc->add_setting( 'loginizr_button_bg', array(
            'default'       => apply_filters( 'loginizr_button_bg', '#2EA2CC' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Button Background Control
        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_button_bg', array(
            'label'     => __( 'Button Background', $this->text ),
            'section'   => 'loginizr_button_section',
            'priority'  => 5,
            'settings'  => 'loginizr_button_bg'
        ) ) );

        // Button Border Setting
        $wpc->add_setting( 'loginizr_button_border', array(
            'default'       => apply_filters( 'loginizr_button_border', '#0074A2' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Button Border Control
        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_button_border', array(
            'label'     => __( 'Button Border', $this->text ),
            'section'   => 'loginizr_button_section',
            'priority'  => 10,
            'settings'  => 'loginizr_button_border'
        ) ) );

        // Button Hover Background Setting
        $wpc->add_setting( 'loginizr_button_hover_bg', array(
            'default'       => apply_filters( 'loginizr_button_hover_bg', '#1E8CBE' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Button Hover Background Control
        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_button_hover_bg', array(
            'label'     => __( 'Button Background (Hover)', $this->text ),
            'section'   => 'loginizr_button_section',
            'priority'  => 15,
            'settings'  => 'loginizr_button_hover_bg'
        ) ) );

        // Button Hover Border Setting
        $wpc->add_setting( 'loginizr_button_hover_border', array(
            'default'       => apply_filters( 'loginizr_button_hover_border', '#0074A2' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Button Hover Border Control
        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_button_hover_border', array(
            'label'     => __( 'Button Border (Hover)', $this->text ),
            'section'   => 'loginizr_button_section',
            'priority'  => 20,
            'settings'  => 'loginizr_button_hover_border'
        ) ) );

        // Button Shadow Setting
        $wpc->add_setting( 'loginizr_button_shadow', array(
            'default'       => apply_filters( 'loginizr_button_shadow', '#78C8E6' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        // Button Shadow Control
        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_button_shadow', array(
            'label'     => __( 'Button Box Shadow', $this->text ),
            'section'   => 'loginizr_button_section',
            'priority'  => 25,
            'settings'  => 'loginizr_button_shadow'
        ) ) );

        /*****************************************/
        /**  Other Settings & Controls          **/
        /*****************************************/

        $wpc->add_setting( 'loginizr_other_color', array(
            'default'       => apply_filters( 'loginizr_other_color', '#999' ),
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_other_color', array(
            'label'     => __( 'Text Color', $this->text ),
            'section'   => 'loginizr_other_section',
            'priority'  => 5,
            'settings'  => 'loginizr_other_color'
        ) ) );

        $wpc->add_setting( 'loginizr_other_color_hover', array(
            'default'       => apply_filters( 'loginizr_other_color_hover', '#2EA2CC' ),
            'type'          => 'option',
            'capability'    => 'edit_theme_options'
        ) );

        $wpc->add_control( new WP_Customize_Color_Control( $wpc, 'loginizr_other_color_hover', array(
            'label'     => __( 'Text Color (Hover)', $this->text ),
            'section'   => 'loginizr_other_section',
            'priority'  => 10,
            'settings'  => 'loginizr_other_color_hover'
        ) ) );

        $wpc->add_setting( 'loginizr_other_css', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        $wpc->add_control( 'loginizr_other_css', array(
            'label'     => __( 'Custom CSS', $this->text ),
            'type'      => 'textarea',
            'section'   => 'loginizr_other_section',
            'priority'  => 15,
            'settings'  => 'loginizr_other_css'
        ) );

        $wpc->add_setting( 'loginizr_other_remember_me', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        $wpc->add_control( 'loginizr_other_remember_me', array(
            'label'     => __( 'Remember Me Checked?', $this->text ),
            'type'      => 'checkbox',
            'section'   => 'loginizr_other_section',
            'priority'  => 20,
            'settings'  => 'loginizr_other_remember_me'
        ));

        $wpc->add_setting( 'loginizr_other_simple_error', array(
            'type'          => 'theme_mod',
            'capability'    => 'edit_theme_options'
        ) );

        $wpc->add_control( 'loginizr_other_simple_error', array(
            'label'     => __( 'Simplify Login Error Message?', $this->text ),
            'type'      => 'checkbox',
            'section'   => 'loginizr_other_section',
            'priority'  => 25,
            'settings'  => 'loginizr_other_simple_error'
        ) );
    }

    /*********************************************/
    /**  Actions & Filter Callback Functions    **/
    /*********************************************/

    /**
     * Register the login screen styles
     *
     * @access public
     */
    public function register_css() {
        wp_register_style( 'loginizr-css', trailingslashit( admin_url( 'admin-ajax.php' ) ) . "?action=loginizr_css" );	
	    wp_enqueue_style( 'loginizr-css' );
    }

    /**
     * Set up the logo url
     *
     * @return string
     * @access public
     */
    public function logo_url() {
        return get_bloginfo( 'url' );
    }

    /**
     * Set up the logo url title
     *
     * @return string
     * @access public
     */
    public function logo_url_title() {
        $title = get_bloginfo( 'name', 'display' );
        return $title;
    }

    /**
     * Autocheck remember me
     *
     * @access public
     */
    public function login_remember_me() {
        $remember_me = (int)get_theme_mod( 'loginizr_other_remember_me', 0 );
        if( $remember_me == 1 ) {
            add_filter( 'login_footer', array( $this, 'login_rememberme_checked' ) );
        }
	}

    /**
     * Check remember me
     *
     * @access public
     */ 
	public function login_rememberme_checked() {
		echo "<script>document.getElementById('rememberme').checked = true;</script>";
	}

    /**
     * Simpified error message?
     *
     * @param string
     * @return string
     * @access public
     */
	public function login_custom_error_message( $error ){

        $simple_error = (int)get_theme_mod( 'loginizr_other_simple_error', 0 );
        if( $simple_error == 1 ) {
    		$error = __('Your login details are incorrect', 'wpr');
        }
		return $error;
	}
    
    /**
     * Version check?
     *
     * @access public
     */
    function version_notice() {
        echo '<div class="update-nag"><p>Your WordPress version ( < 4.0 ) is not compatible with the Loginizr, please update.</p></div>'; 
    }
}

// Set up loginizr
global $loginizr;
$loginizr = new Loginizr;

//end
