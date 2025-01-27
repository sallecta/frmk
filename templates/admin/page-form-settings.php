<?php
/**
 * Form settings page
 *
 * @var FRMK_DB_Form $form
 *
 * @package FRMK/Admin
 * @author James Collings
 * @created 06/08/2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$form_id = '';
if ( false !== $form ) {
	$form_id = $form->get_id();
}

$settings_tab = isset($_GET['setting']) ? $_GET['setting'] : '';

// modules
$modules = FRMK()->get_modules();
?>
<form action="" method="post" autocomplete="off">

	<input type="hidden" name="frmk-action" value="edit-form-settings"/>
	<input type="hidden" name="frmk-form" value="<?php echo esc_attr( $form_id ); ?>"/>
	<div class="frmk-form-manager frmk-form-manager--inputs">

		<?php $this->display_form_header( 'settings', $form ); ?>
		<div class="frmk-cols">

			<div class="frmk-right">
				<div class="frmk-right__inside">

					<div class="frmk-panel">
						<div class="frmk-panel__header">
							<a href="<?php echo remove_query_arg('setting'); ?>" class="frmk-panel__title">General</a>
						</div>
					</div>

					<?php
					if ( ! empty( $modules ) ) {
						foreach( $modules as $module_id => $module ) {
							if(method_exists($module,'display_settings')){
								?>
								<br>
								<div class="frmk-panel">
									<div class="frmk-panel__header">
										<a href="<?php echo add_query_arg( 'setting', $module_id ); ?>" class="frmk-panel__title"><?php echo $module->get_name(); ?></a>
									</div>
								</div>
								<?php
							}
						}
					}
					?>

				</div>
			</div>

			<div class="frmk-left">
				<div class="frmk-left__inside">

					<div id="error-wrapper">
						<?php
						if ( $this->get_success() == 1 ) {
							?>
							<p class="notice notice-success frmk-notice frmk-notice--success"><?php echo esc_html( FRMK()->text->get( 'form_saved', 'general' ) ); ?></p>
							<?php
						}elseif ( $this->get_success() !== false ){

							$errorno = isset($_GET['errorno']) ? $_GET['errorno'] : 0;

							if ( ! empty( $modules ) && isset( $modules[ $settings_tab ] ) ){
								$modules[$settings_tab]->_display_error( $errorno );
							}
						}
						?>
					</div>

					<?php

					if ( ! empty( $modules ) && isset( $modules[ $settings_tab ] ) ):

						$modules[$settings_tab]->display_settings( $form );

					else:

						$settings = $form->export();

						$confirmation_location = $confirmation_redirect = $confirmation_message = $confirmation_type = $submit_label = '';
						if ( $settings ) {

							if ( isset( $settings['confirmations'] ) ) {
								$confirmation          = $settings['confirmations'][0];
								$confirmation_type     = $confirmation['type'];
								$confirmation_message  = $confirmation['message'];
								$confirmation_redirect = isset( $confirmation['redirect_url'] ) ? $confirmation['redirect_url'] : '';
							}

							$confirmation_location = isset( $settings['confirmation_location'] ) ? $settings['confirmation_location'] : 'after';

							if ( isset( $settings['settings'] ) ) {

								if ( isset( $settings['settings']['labels'] ) ) {
									$submit_label = $settings['settings']['labels']['submit'];
								}
							}
						}

						// reCAPTCHA settings.
						$recaptcha_private = $form->get_setting( 'recaptcha_private' );
						$recaptcha_public  = $form->get_setting( 'recaptcha_public' );

						?>

						<h2 class="frmk-settings__header">
							General Settings
						</h2>

						<table class="frmk-form-table">
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="form_label">Form Name</label>
									<span class="frmk-tooltip"
									      title="Name of form, displayed when outputting form.">?</span>
								</td>
								<td class="notification__input"><input id="form_label" type="text"
								                                       name="frmk_settings[form_label]"
								                                       value="<?php echo esc_attr( $form->get_label() ); ?>"/>
								</td>
							</tr>
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="form_content">Form Content</label>
									<span class="frmk-tooltip"
									      title="Content displayed on the form before the fields">?</span>
								</td>
								<td class="notification__input"><textarea name="frmk_settings[form_content]"
								                                          id="form_content" cols="30"
								                                          rows="10"><?php echo esc_textarea( $form->get_content() ); ?></textarea>
								</td>
							</tr>
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="submit_label">Submit Button Text</label>
									<span class="frmk-tooltip" title="Text displayed on the forms submit button">?</span>
								</td>
								<td class="notification__input"><input id="submit_label" type="text"
								                                       name="frmk_settings[submit_label]"
								                                       value="<?php echo esc_attr( $submit_label ); ?>"/>
								</td>
							</tr>
						</table>

						<h2 class="frmk-settings__header">
							Form Confirmation <span class="frmk-tooltip frmk-tooltip__inline"
							                        title="Set what happens when the form is successfully submitted.">?</span>
						</h2>

						<table class="frmk-form-table">
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="confirmation_type">Confirmation Type</label>
									<span class="frmk-tooltip"
									      title="On successful form submission redirect the user to a page or display a message">?</span>
								</td>
								<td class="notification__input"><select name="frmk_settings[confirmation_type]"
								                                        id="confirmation_type">
										<option value="message" <?php selected( $confirmation_type, 'message', true ); ?>>
											Message
										</option>
										<option value="redirect" <?php selected( $confirmation_type, 'redirect', true ); ?>>
											Redirect
										</option>
									</select></td>
							</tr>
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="confirmation_location">Confirmation Location</label>
									<span class="frmk-tooltip"
									      title="Choose where the confirmation message is displayed.">?</span>
								</td>
								<td class="notification__input"><select name="frmk_settings[confirmation_location]"
								                                        id="confirmation_location">
										<option value="after" <?php selected( $confirmation_location, 'after', true ); ?>>
											After Form Content
										</option>
										<option value="replace" <?php selected( $confirmation_location, 'replace', true ); ?>>
											Replace Form Content
										</option>
									</select></td>
							</tr>
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="confirmation_message">Confirmation Message</label>
									<span class="frmk-tooltip"
									      title="Message to be displayed on successful form submission">?</span>
								</td>
								<td class="notification__input"><textarea name="frmk_settings[confirmation_message]"
								                                          id="confirmation_message" cols="30"
								                                          rows="10"><?php echo esc_textarea( $confirmation_message ); ?></textarea>
								</td>
							</tr>
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="confirmation_redirect">Confirmation Redirect</label>
									<span class="frmk-tooltip"
									      title="Url to redirect the user on successful form submission">?</span>
								</td>
								<td class="notification__input"><input name="frmk_settings[confirmation_redirect]"
								                                       id="confirmation_redirect" type="text"
								                                       value="<?php echo esc_attr( $confirmation_redirect ); ?>"/>
								</td>
							</tr>
						</table>

						<h2 class="frmk-settings__header">
							Form Errors <span class="frmk-tooltip frmk-tooltip__inline"
							                  title="Modify text displayed on form output.">?</span>
						</h2>

						<table class="frmk-form-table">
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="submit_label">Field Error Message</label>
									<span class="frmk-tooltip" title="Change text displayed when form has error.">?</span>
								</td>
								<td class="notification__input"><textarea name="frmk_settings[error][general_message]"
								                                          id="general_error_message" cols="30"
								                                          rows="10"><?php echo esc_textarea( $form->get_setting( 'general_message', 'error' ) ); ?></textarea>
								</td>
							</tr>
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="submit_label">Display list of field errors</label>
									<span class="frmk-tooltip"
									      title="Enable the list of field errors below the general error">?</span>
								</td>
								<td class="notification__input">
									<select name="frmk_settings[error][show_fields]" id="frmk_settings-enable_style">
										<option value="yes" <?php selected( 'yes', $form->get_setting( 'show_fields', 'error' ), true ); ?>>
											Yes
										</option>
										<option value="no" <?php selected( 'no', $form->get_setting( 'show_fields', 'error' ), true ); ?>>
											No
										</option>
									</select>
								</td>
							</tr>
						</table>

						<h2 class="frmk-settings__header">
							Display Settings <span class="frmk-tooltip frmk-tooltip__inline"
							                       title="Settings related to the form output">?</span>
						</h2>

						<table class="frmk-form-table">
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="submit_label">Style Editor</label>
									<span class="frmk-tooltip" title="Enable the use of the visual style editor">?</span>
								</td>
								<td class="notification__input">
									<select name="frmk_settings[enable_style]" id="frmk_settings-enable_style">
										<option value="enabled" <?php selected( 'enabled', $form->get_setting( 'enable_style' ), true ); ?>>
											Enable
										</option>
										<option value="disabled" <?php selected( 'disabled', $form->get_setting( 'enable_style' ), true ); ?>>
											Disable
										</option>
									</select>
								</td>
							</tr>

							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="submit_label">Layout CSS</label>
									<span class="frmk-tooltip"
									      title="Enable this to help override the themes default display of the form">?</span>
								</td>
								<td class="notification__input">
									<select name="frmk_settings[enable_layout_css]" id="frmk_settings-enable_style">
										<option value="enabled" <?php selected( 'enabled', $form->get_setting( 'enable_layout_css' ), true ); ?>>
											Enable
										</option>
										<option value="disabled" <?php selected( 'disabled', $form->get_setting( 'enable_layout_css' ), true ); ?>>
											Disable
										</option>
									</select>
								</td>
							</tr>
						</table>

						<h2 class="frmk-settings__header">
							ReCAPTCHA Settings <span class="frmk-tooltip frmk-tooltip__inline"
							                         title="reCAPTCHA is a free service that protects your website from spam and abuse">?</span>
						</h2>

						<div class="frmk-settings__desc">
							<p>To generate or get your ReCAPTCHA details goto: <a target="_blank"
							                                                      href="https://www.google.com/recaptcha/intro/index.html">https://www.google.com/recaptcha/intro/index.html</a>
								and follow their instructions to generate an api key (you will need both site key and secret
								key).</p>
						</div>

						<table class="frmk-form-table">
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="recaptcha_public">ReCAPTCHA Site Key</label>
									<span class="frmk-tooltip" title="Enter your ReCAPTCHA Site key">?</span>
								</td>
								<td class="notification__input"><input name="frmk_settings[recaptcha_public]"
								                                       id="recaptcha_public" type="text"
								                                       value="<?php echo esc_attr( $recaptcha_public ); ?>"/>
								</td>
							</tr>
							<tr>
								<td class="frmk-tooltip__wrapper">
									<label for="recaptcha_private">ReCAPTCHA Public Key</label>
									<span class="frmk-tooltip" title="Enter your ReCAPTCHA Secret key">?</span>
								</td>
								<td class="notification__input"><input name="frmk_settings[recaptcha_private]"
								                                       id="recaptcha_private" type="text"
								                                       value="<?php echo esc_attr( $recaptcha_private ); ?>"/>
								</td>
							</tr>
						</table>

						<?php

					endif;
					?>
					&nbsp;
				</div>
			</div>

		</div>

		<div class="frmk-clear"></div>
	</div>
</form>
