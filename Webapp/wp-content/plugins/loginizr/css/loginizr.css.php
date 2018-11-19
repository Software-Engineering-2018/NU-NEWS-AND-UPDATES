<?php header('Content-type: text/css'); 

/* Login Page Background */
$bg_img         = get_theme_mod( 'loginizr_bg_image' );
$bg_color       = get_theme_mod( 'loginizr_bg_color' );
$bg_size        = get_theme_mod( 'loginizr_bg_size' );
$bg_position    = get_theme_mod( 'loginizr_bg_position' );
$bg_repeat      = get_theme_mod( 'loginizr_bg_repeat' );

/* Login Page Logo */
$logo_url       = get_theme_mod( 'loginizr_logo', '' );
$logo_width     = get_theme_mod( 'loginizr_logo_width' );
$logo_height    = get_theme_mod( 'loginizr_logo_height' );
$logo_padding   = get_theme_mod( 'loginizr_logo_padding' );
$logo_size      = get_theme_mod( 'loginizr_logo_size' );
$logo_position  = get_theme_mod( 'loginizr_logo_position' );
$logo_repeat    = get_theme_mod( 'loginizr_logo_repeat' );

/* Login Page Form Background */
$form_bg_image  = get_theme_mod( 'loginizr_form_bg_image' );
$form_bg_color  = get_theme_mod( 'loginizr_form_bg_color' );
$form_width     = get_theme_mod( 'loginizr_form_width' );
$form_height    = get_theme_mod( 'loginizr_form_height' );
$form_padding   = get_theme_mod( 'loginizr_form_padding' );
$form_border    = get_theme_mod( 'loginizr_form_border' );

/* Login Page Fields */
$field_width    = get_theme_mod( 'loginizr_field_width' );
$field_margin   = get_theme_mod( 'loginizr_field_margin' );
$field_bg       = get_theme_mod( 'loginizr_field_bg' );
$field_color    = get_theme_mod( 'loginizr_field_color' );
$field_label    = get_theme_mod( 'loginizr_field_label_color' );

/* Login Page Buttons */
$btn_bg         = get_theme_mod( 'loginizr_button_bg' );
$btn_border     = get_theme_mod( 'loginizr_button_border' );
$btn_shadow     = get_theme_mod( 'loginizr_button_shadow' );
$btn_color      = get_theme_mod( 'loginizr_button_color' );
$btn_hover_bg        = get_theme_mod( 'loginizr_button_hover_bg' );
$btn_hover_border    = get_theme_mod( 'loginizr_button_hover_border' );

/* Login Page Other CSS */
$other_color        = get_theme_mod( 'loginizr_other_color' );
$other_color_hover  = get_theme_mod( 'loginizr_other_color_hover' );
$other_css          = get_theme_mod( 'loginizr_other_css' );

?>

/* =========================================================================
LOGIN CSS ------------------------------------------------------------------
========================================================================= */

html, body {
<?php if ( !empty( $bg_img ) ) : ?>
    background-image: url(<?php echo $bg_img; ?>) !important;
<?php endif; ?>
<?php if ( !empty( $bg_color ) ) : ?>
    background-color: <?php echo $bg_color; ?> !important;
<?php endif; ?>
<?php if ( !empty( $bg_size ) ) : ?>
    background-size: <?php echo $bg_size; ?> !important;
<?php endif; ?>
<?php if ( !empty( $bg_position ) ) : ?>
    background-position: <?php echo $bg_position; ?> !important;
<?php endif; ?>
<?php if ( !empty( $bg_repeat ) ) : ?>
    background-repeat: <?php echo $bg_repeat; ?> !important;
<?php endif; ?>
}

body.login div#login h1 a {
<?php if ( !empty( $logo_url ) ) : ?>
    background-image: url(<?php echo $logo_url; ?>) !important;
<?php endif; ?>
<?php if ( !empty( $logo_width ) ) : ?>
    width: <?php echo $logo_width; ?> !important;
<?php endif; ?>
<?php if ( !empty( $logo_height ) ) : ?>
    height: <?php echo $logo_height; ?> !important;
<?php endif; ?>
<?php if ( !empty( $logo_padding ) ) : ?>
    padding-bottom: <?php echo $logo_padding; ?> !important;
<?php endif; ?>
<?php if ( !empty( $logo_size ) ) : ?>
    background-size: <?php echo $logo_size; ?> !important;
<?php elseif ( !empty( $logo_width ) && !empty( $logo_height ) ) : ?>
    background-size: <?php echo $logo_width; ?> <?php echo $logo_height; ?> !important;
<?php endif; ?>
<?php if ( !empty( $logo_position ) ) : ?>
    background-position: <?php echo $logo_position; ?> !important;
<?php endif; ?>
<?php if ( !empty( $logo_repeat ) ) : ?>
    background-repeat: <?php echo $logo_repeat; ?> !important;
<?php endif; ?>
}

#loginform {
<?php if ( !empty( $form_bg_image ) ) : ?>
    background-image: url(<?php echo $form_bg_image; ?>) !important;
<?php endif; ?>
<?php if ( !empty( $form_bg_color ) ) : ?>
    background-color: <?php echo $form_bg_color; ?> !important;
<?php endif; ?>
<?php if ( !empty( $form_height ) ) : ?>
    height: <?php echo $form_height; ?> !important;
<?php endif; ?>
<?php if ( !empty( $form_padding ) ) : ?>
    padding: <?php echo $form_padding; ?> !important;
<?php endif; ?>
<?php if ( !empty( $form_border ) ) : ?>
    border: <?php echo $form_border; ?> !important;
<?php endif; ?>
}

<?php if ( !empty( $form_width ) ) : ?>
#login {
    width: <?php echo $form_width; ?> !important;
}
<?php endif; ?>

.login form .input, .login input[type="text"] {
<?php if ( !empty( $field_width ) ) : ?>
    width: <?php echo $field_width; ?> !important;
<?php endif; ?>
<?php if ( !empty( $field_margin ) ) : ?>
    margin: <?php echo $field_margin; ?> !important;
<?php endif; ?>
<?php if ( !empty( $field_bg ) ) : ?>
    background: <?php echo $field_bg; ?> !important;
<?php endif; ?>
<?php if ( !empty( $field_color ) ) : ?>
    color: <?php echo $field_color; ?> !important;
<?php endif; ?>
}

<?php if ( !empty( $field_label ) ) : ?>
.login label {
    color: <?php echo $field_label; ?> !important;
}
<?php endif; ?>

.wp-core-ui .button-primary {
<?php if ( !empty( $btn_bg ) ) : ?>
    background: <?php echo $btn_bg; ?> !important;
<?php endif; ?>
<?php if ( !empty( $btn_border ) ) : ?>
    border-color: <?php echo $btn_border; ?> !important;
<?php endif; ?>
<?php if ( !empty( $btn_shadow ) ) : ?>
    box-shadow: 0px 1px 0px <?php echo $btn_shadow; ?> inset, 0px 1px 0px rgba(0, 0, 0, 0.15);
<?php endif; ?>
<?php if ( !empty( $btn_color ) ) : ?>
    color: <?php echo $btn_color; ?> !important;
<?php endif; ?>
}

.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover {
<?php if ( !empty( $btn_hover_bg ) ) : ?>
    background: <?php echo $btn_hover_bg; ?> !important;
<?php endif; ?>
<?php if ( !empty( $btn_hover_border ) ) : ?>
    border-color: <?php echo $btn_hover_border; ?> !important;
<?php endif; ?>
}

<?php if ( !empty( $other_color ) ) : ?>
.login #backtoblog a, .login #nav a {
    color: <?php echo $other_color; ?> !important;
}
<?php endif; ?>

<?php if ( !empty( $other_color_hover ) ) : ?>
.login #backtoblog a:hover, .login #nav a:hover, .login h1 a:hover {
    color: <?php echo $other_color_hover; ?> !important;
}
<?php endif; ?>

<?php if ( !empty( $other_css ) ) : ?>
<?php echo $other_css; ?>
<?php endif; ?>

