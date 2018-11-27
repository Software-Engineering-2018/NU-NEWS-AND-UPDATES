<?php

function fed_admin_input_fields_multi_line($row, $action,$menu_options ) {
	?>
    <div class="row fed_input_type_container fed_input_multi_line_container hide">
        <form method="post"
              class="fed_admin_menu fed_ajax"
              action="<?php echo admin_url( 'admin-ajax.php?action=fed_admin_setting_up_form' ) ?>">

			<?php fed_wp_nonce_field( 'fed_nonce', 'fed_nonce' ) ?>

			<?php echo fed_loader(); ?>

            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><b><?php _e( 'Multi-Line', 'frontend-dashboard' ) ?></b></h3>
                    </div>
                    <div class="panel-body">
                        <div class="fed_input_text">
		                    <?php fed_get_admin_up_label_input_order( $row ); ?>
                            <div class="row">
			                    <?php fed_get_admin_up_input_meta($row) ?>

                                <div class="form-group col-md-6">
                                    <?php fed_get_placeholder_field( $row) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <?php fed_get_class_field( $row) ?>
                                </div>

                                <div class="form-group col-md-4">
                                    <?php fed_get_id_field( $row) ?>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for=""><?php _e( 'Textarea Row', 'frontend-dashboard' ) ?></label>
				                    <?php echo fed_input_box( 'input_row', array( 'value' => $row['input_row'] ), 'single_line' ); ?>
                                </div>
                            </div>
		                    <?php
		                    fed_get_admin_up_display_permission( $row, $action );

		                    fed_get_admin_up_role_based($row, $action,$menu_options );

		                    fed_get_input_type_and_submit_btn( 'multi_line', $action );
		                    ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
	<?php
}