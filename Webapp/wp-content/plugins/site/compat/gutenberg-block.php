<?php

class SiteOrigin_Panels_Compat_Gutenberg_Block {
	
	const BLOCK_NAME = 'siteorigin-panels/layout-block';
	
	/**
	 * Get the singleton instance
	 *
	 * @return SiteOrigin_Panels_Compat_Gutenberg_Block
	 */
	public static function single() {
		static $single;
		
		return empty( $single ) ? $single = new self() : $single;
	}
	
	public function __construct() {
		add_action( 'init', array( $this, 'register_layout_block' ) );
		// This action is slightly later than `enqueue_block_editor_assets`,
		// which we need to use to ensure our templates are loaded at the right time.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_layout_block_editor_assets' ) );
	}
	
	public function register_layout_block() {
		register_block_type( self::BLOCK_NAME, array(
			'render_callback' => array( $this, 'render_layout_block' ),
		) );
	}
	
	public function enqueue_layout_block_editor_assets() {
		// This is for the Gutenberg plugin.
		$is_block_editor = function_exists( 'is_gutenberg_page' ) && is_gutenberg_page();
		// This is for WP 5 with the integrated block editor. Let it override the Gutenberg plugin.
		$current_screen = get_current_screen();
		if ( $current_screen && method_exists( $current_screen, 'is_block_editor' ) ) {
			$is_block_editor = $current_screen->is_block_editor();
		}
		
		if ( $is_block_editor ) {
			
			$panels_admin = SiteOrigin_Panels_Admin::single();
			$panels_admin->enqueue_admin_scripts();
			$panels_admin->enqueue_admin_styles();
			$panels_admin->js_templates();
			
			wp_enqueue_script(
				'siteorigin-panels-layout-block',
				plugins_url( 'js/siteorigin-panels-layout-block' . SITEORIGIN_PANELS_JS_SUFFIX . '.js', __FILE__ ),
				array(
					'wp-editor',
					'wp-blocks',
					'wp-i18n',
					'wp-element',
					'wp-components',
					'wp-compose',
					'so-panels-admin'
				),
				SITEORIGIN_PANELS_VERSION
			);
			wp_localize_script(
				'siteorigin-panels-layout-block',
				'soPanelsGutenbergAdmin',
				array(
					'previewUrl' => wp_nonce_url( admin_url( 'admin-ajax.php' ), 'gutenberg-preview', '_panelsnonce' ),
				)
			);
			SiteOrigin_Panels_Styles::register_scripts();
			wp_enqueue_script( 'siteorigin-panels-front-styles' );
			
			// Enqueue front end scripts for our widgets bundle.
			if ( class_exists( 'SiteOrigin_Widgets_Bundle' ) ) {
				$sowb = SiteOrigin_Widgets_Bundle::single();
				$sowb->register_general_scripts();
				$sowb->enqueue_registered_widgets_scripts( true, false );
			}
		}
	}
	
	public function render_layout_block( $attributes ) {
		if ( empty( $attributes['panelsData'] ) ) {
			return '<div>'.
				   __( "You need to add a widget, row, or prebuilt layout before you'll see anything here. :)", 'siteorigin-panels' ) .
				   '</div>';
		}
		$panels_data = $attributes['panelsData'];
		$panels_data = $this->sanitize_panels_data( $panels_data );
		$builder_id = isset( $attributes['builder_id'] ) ? $attributes['builder_id'] : uniqid( 'gb' . get_the_ID() . '-' );
		$rendered_layout = SiteOrigin_Panels::renderer()->render( $builder_id, true, $panels_data );
		return $rendered_layout;
	}
	
	private function sanitize_panels_data( $panels_data ) {
		$panels_data['widgets'] = SiteOrigin_Panels_Admin::single()->process_raw_widgets( $panels_data['widgets'], false, true );
		$panels_data = SiteOrigin_Panels_Styles_Admin::single()->sanitize_all( $panels_data );
		return $panels_data;
	}
}
