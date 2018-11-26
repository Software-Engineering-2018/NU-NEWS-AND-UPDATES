<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       ''
 * @since      1.0.0
 *
 * @package    Dwp_Loginizer
 * @subpackage Dwp_Loginizer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dwp_Loginizer
 * @subpackage Dwp_Loginizer/admin
 * @author     Demos Palana <demospalana@yahoo.com>
 */
class Dwp_Loginizer_Admin {

	/**
     * The settings we'll use.
     *
     * @since   1.0.0
     */
    const PAGE = 'dwp_loginizer';

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dwp_Loginizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dwp_Loginizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dwp-loginizer-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-color-picker' ); 
        wp_enqueue_style( 'dwp_loginizer_code_mirror_css', plugin_dir_url( __FILE__ ) . 'inc/codemirror/lib/codemirror.css', array() , null );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dwp_Loginizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dwp_Loginizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'dwp_loginizer_code_mirror_js', plugin_dir_url( __FILE__ ) . 'inc/codemirror/lib/codemirror.js', array() , false, true );
        wp_enqueue_script( 'dwp_loginizer_code_mirror_mode_css', plugin_dir_url( __FILE__ ) . 'inc/codemirror/mode/css/css.js', array( 'dwp_loginizer_code_mirror_js' ) , false, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dwp-loginizer-admin.js', array( 'wp-color-picker' ), $this->version, true );
        
		if ( function_exists( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
			wp_register_script( 'dwp_loginizer_media_uploader_js', plugin_dir_url( __FILE__ ) . 'js/upload.js', array( 'jquery' ) , false, true );
			$translation_array = array(
				'title'  => __( 'Select Image', 'dwp-loginizer' ),
				'button' => __( 'Set up Image', 'dwp-loginizer' ),
			);
			
			wp_localize_script( 'dwp_loginizer_media_uploader_js', 'option_media_text', $translation_array );
			wp_enqueue_script( 'dwp_loginizer_media_uploader_js' );
		}

	}

    /**
     * This is the option settings page callback
     * @since 1.0.0
     */
	public function dwp_loginizer_settings_page_setup() {

        $p = add_options_page(
            __('DWP Loginizer', 'dwp-loginizer'),
            __('DWP Loginizer', 'dwp-loginizer'),
            'manage_options',
            'dwp-loginizer',
            array($this, 'dwp_loginizer_settings')
        );

	}

	/**
     * Alter the whitelist options on the `options.php` admin page so we
     * don't loose changes to settings due to the fields not beeing there.
     *
     * @since   1.0.0
     * @access  public
     * @param   array $opts A multi-dimensional array of options.
     * @return  void
     */
     public function change_whitelist($opts)
    {
        if(empty($_POST) || $_POST['action'] != 'update')
            return $opts;
        // since WP tries to update ALL settings assigned to a particular page.
        // We want to make sure that we unset the other settings that aren't 
        // on the current request so they don't get overwritten.
        // this is going to have to be on a case by case basis -- 
        // sections aren't added with a knowledge of the setting to which
        // they belong. One section per setting makes it easy!
        $sect = isset($_POST['current_section']) ? $_POST['current_section'] : 'dwp-loginizer-design-section';
        $s = 'dwp-loginizer-design-section' == $sect ? 'dwp-loginizer-custom-css' : 'dwp-loginizer-field-options';
        if(($pos = array_search($s, $opts[self::PAGE])) !== false) 
        {
            unset($opts[self::PAGE][$pos]);
        }
        return $opts;
    }

    /**
     * Register sections 
     *
     * @since   1.0.0
     * @access  public
     * @uses    add_settings_section
     * @return  void
     */
    public function dwp_loginizer_register_setting_sections() {

        add_settings_section(
            'dwp-loginizer-design-section',
            __('Design Settings', 'dwp-loginizer'),
            '__return_false',
            self::PAGE
        );

        add_settings_section(
            'dwp-loginizer-custom-css-section',
            __('Custom CSS', 'dwp-loginizer'),
            '__return_false',
            self::PAGE
        );

    }

	/**
     * Registers settings and fields.
     *
     * @since   1.0.0
     * @access  public
     * @uses    register_setting
     * @uses    add_settings_field
     * @param   array $fields A multi-dimensional array of options.
     * @return  $fields
     */
    public function dwp_loginizer_register_setting_fields() {

        $fields = array(

            //Background Color
            array(
                'fid'           => 'dwp_loginizer_bg_color',
                'label'         => __('Login Background Color'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'color',
                'options'       => 'false',
                'placeholder'   => 'placeholder',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '#f1f1f1',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'clas'          => 'color-field',
            ),

            //Text Color
            array(
                'fid'           => 'dwp_loginizer_text_color',
                'label'         => __('Login Text Color'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'color',
                'options'       => 'false',
                'placeholder'   => 'placeholder',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '#72777c',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'clas'          => 'color-field',
            ),

            //Link Color
            array(
                'fid'           => 'dwp_loginizer_link_color',
                'label'         => __('Login Link Color'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'color',
                'options'       => 'false',
                'placeholder'   => 'placeholder',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '#555d66',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'clas'          => 'color-field',
            ),

            //Hover Color
            array(
                'fid'           => 'dwp_loginizer_link_hover_color',
                'label'         => __('Login Link Hover Color'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'color',
                'options'       => 'false',
                'placeholder'   => 'placeholder',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '#00a0d2',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'clas'          => 'color-field',
            ),

            //Background Image
            array(
                'fid'           => 'dwp_loginizer_bg_image',
                'label'         => __('Login Background Image'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'hidden',
                'options'       => 'false',
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => 'image_url',
                'id2'           => 'bg-image',
                'clas'          => 'upload_field',
            ),

            //Background Repeat
            array(
                'fid'           => 'dwp_loginizer_bg_repeat',
                'label'         => __('Login Background Repeat'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'select',
                'options'       => array(
                                    'no-repeat' => 'no-repeat',
                                    'repeat'    => 'repeat',
                                    'repeat-x'  => 'repeat-x',
                                    'repeat-y'  => 'repeat-y',
                                ),
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => 'no-repeat',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
            ),

            //Background Size
            array(
                'fid'           => 'dwp_loginizer_bg_size',
                'label'         => __('Login Background Size'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'select',
                'options'       => array(
                                    'initial'   => 'initial',
                                    'cover'     => 'cover',
                                    'contain'   => 'contain',
                                    'inherit'   => 'inherit',
                                ),
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => 'initial',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
            ),

            // Logo Settings
            // Hide Logo
            array(
                'fid'           => 'dwp_loginizer_hide_logo',
                'label'         => __('Hide Logo'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'checkbox',
                'options'       => 'false',
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '0',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
            ),

            //Logo Image
            array(
                'fid'           => 'dwp_loginizer_logo_image',
                'label'         => __('Login Logo Image'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'hidden',
                'options'       => 'false',
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => 'image_url',
                'id2'           => 'logo-image',
                'clas'          => 'upload_field',
            ),

            // Form Settings
            // Form Background Color
            array(
                'fid'           => 'dwp_loginizer_form_bg_color',
                'label'         => __('Form Background Color'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'color',
                'options'       => 'false',
                'placeholder'   => 'placeholder',
                'helper'        => '',
                'supplemental'  => __('This will set the color of your login form, default is #ffffff.'),
                'default'       => '#ffffff',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'clas'          => 'color-field',
            ),

            //Form Background Opacity
            array(
                'fid'           => 'dwp_loginizer_form_bg_opacity',
                'label'         => __('Form Background Opacity'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'select',
                'options'       => array(
                                    '1'     => '1',
                                    '0.9'   => '0.9',
                                    '0.8'   => '0.8',
                                    '0.7'   => '0.7',
                                    '0.6'   => '0.6',
                                    '0.5'   => '0.5',
                                    '0.4'   => '0.4',
                                    '0.3'   => '0.3',
                                    '0.2'   => '0.2',
                                    '0.1'   => '0.1',
                                    '0'     => '0',
                                ),
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '1',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'id2'           => ''
            ),

            //Form Background Image
            array(
                'fid'           => 'dwp_loginizer_form_bg_image',
                'label'         => __('Form Background Image'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'hidden',
                'options'       => 'false',
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => __('This will set the Background Image of your login form ' . '<br/>' . 'if set this will override form background color.'),
                'default'       => '',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => 'image_url',
                'id2'           => 'form-image',
                'clas'          => 'upload_field',
            ),

            //Form Background Image Size
            array(
                'fid'           => 'dwp_loginizer_form_bg_size',
                'label'         => __('Form Image Background Size'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'select',
                'options'       => array(
                                    'initial'   => 'initial',
                                    'cover'     => 'cover',
                                    'contain'   => 'contain',
                                    'inherit'   => 'inherit',
                                ),
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => 'initial',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
            ),

            //Form Border Radius
            array(
                'fid'           => 'dwp_loginizer_form_border_radius',
                'label'         => __('Form Border Radius'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'number',
                'options'       => 'false',
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => __('Increase the value if you want rounded form edges.'),
                'default'       => '0',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'id2'           => '',
                'clas'          => ''
            ),

            //Form Width
            array(
                'fid'           => 'dwp_loginizer_form_width',
                'label'         => __('Form Width'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'number',
                'options'       => 'false',
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '320',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'clas'          => 'form-width'
            ),

            //Form Alignment
            array(
                'fid'           => 'dwp_loginizer_form_align',
                'label'         => __('Form Alignment'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'select',
                'options'       => array(
                                    'center'    => 'center',
                                    'left'      => 'left',
                                    'right'     => 'right',
                                ),
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => __('Change your form location by changing the value here. ' . '<br/>' .  ' Default is center'),
                'default'       => 'center',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'clas'           => ''
            ),

            // Hide Links 
            array(
                'fid'           => 'dwp_loginizer_hide_links',
                'label'         => __('Hide Links'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'checkbox',
                'options'       => 'false',
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '0',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
            ),

            // Form Button Text Color
            array(
                'fid'           => 'dwp_loginizer_btn_text_color',
                'label'         => __('Button Text Color'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'color',
                'options'       => 'false',
                'placeholder'   => 'placeholder',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '#ffffff',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'clas'          => 'color-field',
            ),

            // Form Button Color
            array(
                'fid'           => 'dwp_loginizer_btn_color',
                'label'         => __('Button Color'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'color',
                'options'       => 'false',
                'placeholder'   => 'placeholder',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '#0085ba',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'clas'          => 'color-field',
            ),

            // Form Button Hover Color
            array(
                'fid'           => 'dwp_loginizer_btn_hover_color',
                'label'         => __('Button Hover Color'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'color',
                'options'       => 'false',
                'placeholder'   => 'placeholder',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '#008ec2',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'clas'          => 'color-field',
            ),

            // Form Button Border Color
            array(
                'fid'           => 'dwp_loginizer_btn_border_color',
                'label'         => __('Button Border Color'),
                'section'       => 'dwp-loginizer-design-section',
                'type'          => 'color',
                'options'       => 'false',
                'placeholder'   => 'placeholder',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '#006799',
                'option-holder' => 'dwp-loginizer-field-options',
                'id'            => '',
                'clas'          => 'color-field',
            ),

            // Custom CSS
            array(
                'fid'           => 'dwp_loginizer_custom_css',
                'label'         => __('Custom CSS'),
                'section'       => 'dwp-loginizer-custom-css-section',
                'type'          => 'textarea',
                'options'       => 'false',
                'placeholder'   => '',
                'helper'        => '',
                'supplemental'  => '',
                'default'       => '',
                'option-holder' => 'dwp-loginizer-custom-css',
                'id'            => 'editor',
                'clas'          => 'color-field',
            ),

        );

        foreach( $fields as $field ) {

            add_settings_field( 
                $field['fid'],
                $field['label'],
                array( $this, 'dwp_loginizer_field_callback' ),
                self::PAGE,
                $field['section'],
                $field
            );

            if ( $field['option-holder'] === 'dwp-loginizer-custom-css' ) {
                register_setting( self::PAGE, 'dwp-loginizer-custom-css' );
            } else {
                register_setting( self::PAGE, 'dwp-loginizer-field-options', array( $this, 'dwp_loginizer_validation' ));
            }
        }
        return $fields;

    }

    /**
     * Include Partials/dwp-loginizer-admin-display.php
     * 
     * @since   1.0.0
     * @access  public
     * @return  void
     */
    public function dwp_loginizer_settings() {
        $dir = plugin_dir_path( __FILE__ );
        global $wp_settings_sections;
        // current section.
        $sect = isset($_GET['tab']) ? $_GET['tab'] : 'dwp-loginizer-design-section';
        // sections on this page.
        $sections = $this->get_sections();
        // url of this page.
        $url = add_query_arg( 'page', 'dwp-loginizer', admin_url('options-general.php') );

        include $dir.'partials/dwp-loginizer-admin-display.php';
    }

    /**
     * Fields callback 
     * 
     * @since   1.0.0
     * @access  public
     * @return  void
     */

	public function dwp_loginizer_field_callback( $args ) {

		if ( $args['option-holder'] == 'dwp-loginizer-field-options' ) {
            $fieldname  = 'dwp-loginizer-field-options' . '[' . $args['fid'] . ']';
            $value      = get_option( $args['option-holder'] );

            if ( ! $value ) {
            	$value = $args[ 'default' ];	
            } else {
            	$value = $value[ $args['fid'] ];
            }
            	
        } else {

            $fieldname 	= 'dwp-loginizer-custom-css' . '[' . $args['fid'] . ']';
            $value 		= get_option( $args['option-holder'] );
            if ( $value )
                $value = $value[ $args['fid'] ];
            
        }
	
		// Check which type of field we want
		switch( $args['type'] ) {

			case 'text': // if it is a text field
				echo '<input name="' . $fieldname . '" id="' . $fieldname . '" type="text" placeholder="' . isset( $args['placeholder'] ) . '"  value="' . $value  . '" />';
				break;

			case 'color': // if it is a color field
				echo '<input name="' . $fieldname . '" id="' . $fieldname . '" type="text" placeholder="' . isset( $args['placeholder'] ) . '" class="' . $args['clas'] . '" value="' . $value  . '" />';
				break;

			case 'hidden': // if it is image and set to hidden
			?>
				<div id="<?php echo $args['id2'] ?>" class="media-upload no-image">
					
					<div id="" class="image-preview">
					<?php
						if( $value ) {
					?>
						<img type="image" src="<?php echo esc_url($value); ?>">
					</div>
					<?php	
						}
				      	echo '<input name="' . $fieldname . '" id="' . $args['id'] . '" type="' . $args['type'] . '" placeholder="' . isset( $args['placeholder'] ) . '" class="' . $args['clas'] . '" value="' . $value . '" />';
					?>
					<div class="action-button upload-active">
						<input type="button" id="upload-image" class="button option-upload-button" value="Select Image" />
						<input type="button" id="remove-image" class="button option-remove-button" value="Remove Image" />
					</div>
				</div>
			<?php

				break;

			case 'select': // if it is select
			?>
				<select class="regular" name="<?php echo $fieldname ?>" id="<?php echo $fieldname ?>"  />
				<?php
					foreach ( $args['options'] as $option => $v ) {
						?>
						<option value="<?php echo $option ?>"
						<?php if( $value === $v ) 
								{ echo "selected"; }?>>
								<?php echo $v ?>
						</option>
						<?php
					}
				?>
				</select>
				<?php
				break;

			case 'checkbox': // if it is checkbox
				echo '<input type="hidden" name="' . $fieldname . '" id="' . $fieldname . '" value="0" />';
				printf('<input id="%1$s" name="%1$s" type="%2$s" value="1" %3$s />', $fieldname , $args['type'], checked( 1, $value, false ) );
				/*echo '<input name="' . $fieldname . '" id="' . $fieldname . '" type="' . $args['type'] . '"  value="1" ' . checked( 1, $value, true ) . ' />';*/
				break;

			case 'number': // if it is number
				printf('<input id="%1$s" name="%1$s" type="%2$s" class="%3$s" value="%4$s" />', $fieldname , $args['type'], $args['clas'], $value );
				/*echo '<input name="' . $fieldname . '" id="' . $fieldname . '" type="' . $args['type'] . '"  value="1" ' . checked( 1, $value, true ) . ' />';*/
				break;

			case 'textarea': // if it is textarea
				printf('<textarea name="%1$s" id="%2$s" type="%3$s" cols="90" rows="10" />%4$s</textarea>', $fieldname , $args['id'], $args['type'], $value );
				break;
		}

		// If there is help text
	    if( $helper = $args['helper'] ){
	        printf( '<span class="helper">%s</span>', $helper ); // Show it
	    }

		// If there is supplemental text
	    if( $supplimental = $args['supplemental'] ){
	        printf( '<p class="description">%s</p>', $supplimental ); // Show it
	    }
		
	}

    /**
     * Validate and Sanitize options
     *
     * @since   1.0.0
     * @access  public
     * @return  $option
     */
	public function dwp_loginizer_validation( $option ) {

        $default_options = $this->dwp_loginizer_register_setting_fields();
        $options = array_column($default_options, 'default', 'fid');

         if ( isset( $_POST['reset'] ) ) {
            $option = $options;
        } else {
	
    	    //HTML Setting
    	    $option['dwp_loginizer_bg_color'] 				= esc_attr( $option['dwp_loginizer_bg_color'] );

    	    $option['dwp_loginizer_text_color'] 			= esc_attr( $option['dwp_loginizer_text_color'] );

    	    $option['dwp_loginizer_link_color'] 			= esc_attr( $option['dwp_loginizer_link_color'] );

    	    $option['dwp_loginizer_link_hover_color'] 		= esc_attr( $option['dwp_loginizer_link_hover_color'] );

    	    if ( ! isset( $option['dwp_loginizer_bg_image'] ) )
    	    	$option['dwp_loginizer_bg_image'] 			= null;
    		$option['dwp_loginizer_bg_image'] 				= esc_url( $option['dwp_loginizer_bg_image'] );

            if ( ! array_key_exists( $option['dwp_loginizer_bg_repeat'], $this->dwp_loginizer_get_options_value( 'dwp_loginizer_bg_repeat' ) ) )
                $option['dwp_loginizer_bg_repeat']            = "no-repeat";

    		if ( ! array_key_exists( $option['dwp_loginizer_bg_size'], $this->dwp_loginizer_get_options_value( 'dwp_loginizer_bg_size' ) ) )
    			$option['dwp_loginizer_bg_size'] 			= "initial";
    		
    		//Logo Setting
    	    if ( ! isset( $option['dwp_loginizer_hide_logo'] ) )
    			$option['dwp_loginizer_hide_logo'] 				= null;
    		$option['dwp_loginizer_hide_logo'] 					= absint( $option['dwp_loginizer_hide_logo'] == 1 ? 1 : 0 );

    		if ( ! isset( $option['dwp_loginizer_logo_image'] ) )
    			$option['dwp_loginizer_logo_image'] 		= null;
    		$option['dwp_loginizer_logo_image'] 			= esc_url( $option['dwp_loginizer_logo_image'] );

    	    
    		//Form Setting 
    	    $option['dwp_loginizer_form_bg_color'] 				= esc_attr( $option['dwp_loginizer_form_bg_color'] );
    	    
    	    if ( ! array_key_exists( $option['dwp_loginizer_form_bg_opacity'], $this->dwp_loginizer_get_options_value( 'dwp_loginizer_form_bg_opacity' ) ) )
    			$option['dwp_loginizer_form_bg_opacity'] 		= 1;

    	    if ( ! isset( $option['dwp_loginizer_form_bg_image'] ) )
    			$option['dwp_loginizer_form_bg_image'] 			= null;
    		$option['dwp_loginizer_form_bg_image'] 				= esc_url( $option['dwp_loginizer_form_bg_image'] );

    		$option['dwp_loginizer_form_border_radius'] 		= absint( $option['dwp_loginizer_form_border_radius'] );

    	    $option['dwp_loginizer_form_width'] 				= absint( $option['dwp_loginizer_form_width'] );

    	    if ( ! array_key_exists( $option['dwp_loginizer_form_align'], $this->dwp_loginizer_get_options_value( 'dwp_loginizer_form_align' ) ) )
    			$option['dwp_loginizer_form_align'] 			= "center";

    		$option['dwp_loginizer_btn_text_color'] 			= esc_attr( $option['dwp_loginizer_btn_text_color'] );

    		$option['dwp_loginizer_btn_color'] 					= esc_attr( $option['dwp_loginizer_btn_color'] );

    		$option['dwp_loginizer_btn_hover_color'] 			= esc_attr( $option['dwp_loginizer_btn_hover_color'] );

    		$option['dwp_loginizer_btn_border_color'] 			= esc_attr( $option['dwp_loginizer_btn_border_color'] );

        }
    	    
        return $option;
	
	}

    /**
     * Get options value to be used in validating if the
     * selected option in dropdown is part of the default array option
     *
     * @since   1.0.0
     * @access  public
     * @return  $options_value
     */

	public function dwp_loginizer_get_options_value( $option_id ) {

		$default_options = $this->dwp_loginizer_register_setting_fields();
		$options = array_column($default_options, 'options', 'fid');
		$options_value = $options[$option_id];		

		return $options_value;
	}

     /**
     * Get the saved style and custom css then echo in the header.
     *
     * @since   1.0.0
     * @access  public
     */

	public function dwp_loginizer_apply_style() {

        $options    = get_option( 'dwp-loginizer-field-options' );
        $custom_css = get_option( 'dwp-loginizer-custom-css' );
        
        echo '<style type="text/css">';

        /** HTML STYLE **/
        if ( ! empty( $options['dwp_loginizer_bg_color'] ) ) {
            echo ' body.login { background-color: ' . $options['dwp_loginizer_bg_color'] . '} ';
        }

        if ( ! empty( $options['dwp_loginizer_text_color'] ) ) {
            echo ' body.login label{ color: ' . $options['dwp_loginizer_text_color'] . ' !important;} ';
        }

        if ( ! empty( $options['dwp_loginizer_link_color'] ) ) {
            echo ' body.login a{ color: ' . $options['dwp_loginizer_link_color'] . ' !important;} ';
        }

        if ( ! empty( $options['dwp_loginizer_link_hover_color'] ) ) {
            echo ' body.login a:hover{ color: ' . $options['dwp_loginizer_link_hover_color'] . ' !important;} ';
        }

        if ( ! empty( $options['dwp_loginizer_bg_image'] ) ) {
            echo ' body.login { background-image: url("' . $options['dwp_loginizer_bg_image'] . '") !important;} ';
        }

        if ( ! empty( $options['dwp_loginizer_bg_repeat'] ) ) {
            echo ' body.login { background-repeat: ' . $options['dwp_loginizer_bg_repeat'] . ' !important;} ';
        }

        if ( ! empty( $options['dwp_loginizer_bg_size'] ) ) {
            echo ' body.login { background-size: ' . $options['dwp_loginizer_bg_size'] . ' !important;} ';
        }

        /** LOGO STYLE **/

        if ( ! empty( $options['dwp_loginizer_hide_logo'] && $options['dwp_loginizer_hide_logo'] == 1 ) ) {
            echo ' body.login h1 a { visibility: hidden !important;} ';
        }

        if ( ! empty( $options['dwp_loginizer_logo_image'] ) ) {
            echo ' body.login h1 a { background-image: url("' . $options['dwp_loginizer_logo_image'] . '") !important;} ';
        }

        /** FORM STYLE **/

        if ( ! empty( $options['dwp_loginizer_form_bg_color'] && empty( $options['dwp_loginizer_form_bg_image'] ) ) ) {
            
            if ( ! empty( $options['dwp_loginizer_form_bg_opacity'] ) ) {

                $hex = $options['dwp_loginizer_form_bg_color'];
                list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
                $color = "$r, $g, $b,";
                echo ' body.login form#loginform{ background: rgba(' . $color . ' ' . $options['dwp_loginizer_form_bg_opacity'] . ') } ';
            } else {
                echo ' body.login form#loginform{ background: ' . $options['dwp_loginizer_form_bg_color'] . '} ';
            }
        }

        if ( ! empty( $options['dwp_loginizer_form_bg_image'] ) ) {
            echo ' body.login form#loginform{ background-image: url("' . $options['dwp_loginizer_form_bg_image'] . '"); } ';
        }

        if ( ! empty( $options['dwp_loginizer_form_bg_size'] ) ) {
            echo ' body.login form#loginform{ background-size: ' . $options['dwp_loginizer_form_bg_size'] . '; } ';
        }

        if ( ! empty( $options['dwp_loginizer_form_border_radius'] ) ) {
            echo ' body.login form#loginform{ border-radius: ' . $options['dwp_loginizer_form_border_radius'] . 'px;} ';
        }

        if ( ! empty( $options['dwp_loginizer_form_width'] ) ) {
            //echo ' body.login form#loginform{ width: ' . $options['dwp_loginizer_form_width'] . 'px;} ';
            echo ' body.login #login{ width: ' . $options['dwp_loginizer_form_width'] . 'px;} ';
        }

        if ( ! empty( $options['dwp_loginizer_form_align'] ) ) {
            if ( $options['dwp_loginizer_form_align'] === 'left' ) {
                echo 'body.login #login{ margin-left: 50px; }';
            }
            if ( $options['dwp_loginizer_form_align'] === 'right' ) {
                echo 'body.login #login{ margin-right: 100px; }';
            }
    
        }

        if ( ! empty( $options['dwp_loginizer_hide_links'] && $options['dwp_loginizer_hide_links'] == 1 ) ) {
            echo ' body.login p#nav, body.login p#backtoblog a { display: none; } ';
        }

        if ( ! empty( $options['dwp_loginizer_btn_text_color'] ) ) {
            echo ' body.login p.submit input[type=submit] { color: ' . $options['dwp_loginizer_btn_text_color'] . ' } ';
            echo ' body.login p.submit input[type=submit] { text-shadow: none; } ';
        }

        if ( ! empty( $options['dwp_loginizer_btn_color'] ) ) {
            echo ' body.login p.submit input[type=submit] { background: ' . $options['dwp_loginizer_btn_color'] . ' } ';
        }

        if ( ! empty( $options['dwp_loginizer_btn_hover_color'] ) ) {
            echo ' body.login p.submit input[type=submit]:hover { background: ' . $options['dwp_loginizer_btn_hover_color'] . ' } ';
        }

        if ( ! empty( $options['dwp_loginizer_btn_border_color'] ) ) {
            echo ' body.login p.submit input[type=submit] { border-color: ' . $options['dwp_loginizer_btn_border_color'] . '!important; } ';
            echo ' body.login p.submit input[type=submit] { box-shadow: 0 1px 0' . $options['dwp_loginizer_btn_border_color'] . ' !important; } ';
        }

        // custom css
        if ( ! empty( $custom_css ) ) {
            echo "\n" . wp_kses_stripslashes( $custom_css['dwp_loginizer_custom_css'] ) . "\n";
        }

        echo '</style>';

    }

	/**
     * Get all the sections for the page.
     *
     * @since   1.0.0
     * @access  private
     * @return  array
     */
    private function get_sections()
    {
        global $wp_settings_sections;
        return isset($wp_settings_sections[self::PAGE]) ?
                $wp_settings_sections[self::PAGE] : array();
    }

}
