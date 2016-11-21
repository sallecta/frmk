<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 20/11/2016
 * Time: 18:49
 */
$forms = WPDF()->get_forms();

$options = array();
if(!empty($forms)) {
	foreach ( $forms as $form ) {

		$id = $form->getId();
		if ( $id ) {
			$options[ $id ] = array(
				'label' => $form->getLabel(),
				'type'  => 'form_id'
			);
		} else {
			$options[ $form->getName() ] = array(
				'label' => $form->getLabel(),
				'type'  => 'form'
			);
		}
	}
}
?>

<div class="wpdf-dialog">
	<?php if( empty($options) ): ?>
		<div id="wpdf_form_error" style="display:block;">
			<p>You currently have no forms available, please create one and try again.</p>
		</div>
	<?php else: ?>
		<div id="wpdf_form_error" style="display:none;">
			<p>An error occured with your selected, please try again.</p>
		</div>

		<table class="wpdf-dialog-table">
			<tr>
				<th class="notification__label">
					<label for="wpdf_form_select">Form:</label>
				</th>
				<td class="notification__input">
					<select name="wpdf_form_select" id="wpdf_form_select" data-options='<?php echo json_encode($options); ?>'>
						<?php
						foreach($options as $id => $option):
							?>
							<option value="<?php echo $id; ?>"><?php echo $option['label']; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>
	<?php endif; ?>
</div>


