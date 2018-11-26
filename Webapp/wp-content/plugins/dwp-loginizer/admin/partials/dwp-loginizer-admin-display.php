<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       ''
 * @since      1.0.0
 *
 * @package    Dwp_Loginizer
 * @subpackage Dwp_Loginizer/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

 <div class="wrap">
 	<h2><?php esc_html_e('DWP Loginizer 1.0.0', 'dwp-loginizer'); ?></h2>
    <h2 class="nav-tab-wrapper">
        <?php foreach($sections as $id => $data): ?>
            <a class="nav-tab <?php echo ($sect == $id ? 'nav-tab-active' : ''); ?>" 
               href="<?php echo add_query_arg('tab', $id, $url); ?>"><?php echo esc_html($data['title']); ?></a>
        <?php endforeach; ?>
    </h2>

    <?php settings_errors(self::PAGE); ?>

    <?php 
    if(isset($sections[$sect]['callback']))
    {
        call_user_func($sections[$sect]['callback']);
    }
    ?>

    <form id="form-settings" method="post" action="<?php echo admin_url('options.php'); ?>">

        <input type="hidden" name="current_section" value="<?php echo esc_attr($sect); ?>" />

        <table class="form-table">
            <?php
            settings_fields(self::PAGE);
            do_settings_fields(self::PAGE, $sect);
            ?>
        </table>
        <div class="submit-btn">
            <?php submit_button( __( 'Save Changes', 'dwp-loginizer' ), 'primary', 'save' ); ?>
            <?php submit_button( __( 'Reset Default', 'dwp-loginizer'), 'secondary', 'reset' ); ?>
        </div>
        
    </form>
</div>
