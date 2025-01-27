<?php
/**
 * Form Shortcodes
 *
 * @package FRMK
 * @author James Collings
 * @created 07/08/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Shortcode to display form
 *
 * @param array  $atts Shortcode attributes.
 * @param string $content Text in shortcode.
 *
 * @return string
 */
function frmk_shortcode_form( $atts, $content = null ) {

	$a = shortcode_atts( array(
		'form'    => '',
		'form_id' => 0,
		'title' => true,
		'ajax' => true
	), $atts );

	$display_args = array(
		'title' => $a['title'] === 'true' || $a['title'] === true ? true : false,
		'ajax' => $a['ajax'] === 'true' || $a['ajax'] === true ? true : false,
	);

	$form_id = intval( $a['form_id'] );
	if ( $form_id > 0 ) {
		$form     = new FRMK_DB_Form( $form_id );
		$form_key = $form->get_name();
	} else {
		$form_key = $a['form'];
		$form     = frmk_get_form( $form_key );
	}

	if ( ! $form ) {
		return sprintf( '<p>%s</p>', __( 'Shortcode Error: Form could not be displayed!', 'frmk' ) );
	}

	if ( $form_id > 0 && 'enabled' === $form->get_setting( 'enable_style' ) ) :
		?>
		<style type="text/css">
			<?php if ( $form->has_style( 'form_bg_colour' ) ) : ?>
			.frmk-form {
				background: <?php echo esc_attr( $form->get_style( 'form_bg_colour' ) ); ?>;
			}

			<?php endif; ?>
			<?php if ( $form->has_style( 'form_text_colour' ) ) : ?>
			.frmk-form, .frmk-form p, .frmk-form label {
				color: <?php echo esc_attr( $form->get_style( 'form_text_colour' ) ); ?>;
			}

			<?php endif; ?>
			<?php if ( $form->has_style( 'field_input_bg_colour' ) || $form->has_style( 'field_input_text_colour' ) ) : ?>
			.frmk-form .frmk-field {
				<?php if ( $form->has_style( 'field_input_bg_colour' ) ) : ?> background: <?php echo esc_attr( $form->get_style( 'field_input_bg_colour' ) ); ?>;<?php endif; ?>
				<?php if ( $form->has_style( 'field_input_text_colour' ) ) : ?> color: <?php echo esc_attr( $form->get_style( 'field_input_text_colour' ) ); ?>;<?php endif; ?>
			}

			<?php endif; ?>

			<?php if ( $form->has_style( 'field_label_bg_colour' ) || $form->has_style( 'field_label_text_colour' ) ) : ?>
			.frmk-form .frmk-label label {
				background: <?php echo esc_attr( $form->get_style( 'field_label_bg_colour' ) ); ?>;
				color: <?php echo esc_attr( $form->get_style( 'field_label_text_colour' ) ); ?>;
			}

			<?php endif; ?>

			<?php if ( $form->has_style( 'checkbox_text_colour' ) ) : ?>
			.frmk-form .frmk-choices label {
				color: <?php echo esc_attr( $form->get_style( 'checkbox_text_colour' ) ); ?>;
			}

			<?php endif; ?>

			<?php if ( $form->has_style( 'button_text_colour' ) || $form->has_style( 'button_bg_colour' ) ) : ?>
			.frmk-form .frmk-button {
				color: <?php echo esc_attr( $form->get_style( 'button_text_colour' ) ); ?>;
				background: <?php echo esc_attr( $form->get_style( 'button_bg_colour' ) ); ?>;
				border: 1px solid <?php echo esc_attr( $form->get_style( 'button_bg_colour' ) ); ?>;
			}

			<?php endif; ?>

			<?php if ( $form->has_style( 'button_hover_text_colour' ) || $form->has_style( 'button_hover_bg_colour' ) ) : ?>
			.frmk-form .frmk-button:hover {
				color: <?php echo esc_attr( $form->get_style( 'button_hover_text_colour' ) ); ?>;
				background: <?php echo esc_attr( $form->get_style( 'button_hover_bg_colour' ) ); ?>;
				border: 1px solid <?php echo esc_attr( $form->get_style( 'button_hover_bg_colour' ) ); ?>;
			}

			<?php endif; ?>

			<?php if ( $form->has_style( 'form_bg_error_colour' ) ) : ?>
			.frmk-form-error {
				background: <?php echo esc_attr( $form->get_style( 'form_bg_error_colour' ) ); ?>;
			}

			<?php endif; ?>
			<?php if ( $form->has_style( 'form_text_error_colour' ) ) : ?>
			.frmk-form-error ul, .frmk-form-error p {
				color: <?php echo esc_attr( $form->get_style( 'form_text_error_colour' ) ); ?>;
			}

			<?php endif; ?>
			<?php if ( $form->has_style( 'field_error_text_colour' ) ) : ?>
			.frmk-field-error {
				color: <?php echo esc_attr( $form->get_style( 'field_error_text_colour' ) ); ?>;
			}

			<?php endif; ?>
			<?php if ( $form->has_style( 'field_border_colour' ) ) : ?>
			.frmk-field {
				border: 1px solid <?php echo esc_attr( $form->get_style( 'field_border_colour' ) ); ?>
			}

			<?php endif; ?>
			<?php if ( $form->has_style( 'field_error_border_colour' ) ) : ?>
			.frmk-has-error .frmk-field {
				border: 1px solid <?php echo esc_attr( $form->get_style( 'field_error_border_colour' ) ); ?>
			}

			<?php endif; ?>

			<?php if ( $form->has_style( 'form_bg_success_colour' ) || $form->has_style( 'form_text_success_colour' ) ) : ?>
			.frmk-form-confirmation {
			<?php if ( $form->has_style( 'form_bg_success_colour' ) ) : ?> background: <?php echo esc_attr( $form->get_style( 'form_bg_success_colour' ) ); ?>;
			<?php endif; ?> <?php if ( $form->has_style( 'form_text_success_colour' ) ) : ?> color: <?php echo esc_attr( $form->get_style( 'form_text_success_colour' ) ); ?>;
			<?php endif; ?>
			}

			<?php endif; ?>

		</style>
		<?php
	endif;

	ob_start();
	frmk_display_form( $form_key, $display_args );

	return ob_get_clean();
}

add_shortcode( 'wp_form', 'frmk_shortcode_form' );
