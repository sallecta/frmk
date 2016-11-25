<?php
/**
 * @var WPDF_FileField $this
 */
?>
<div class="wpdf-field-row">
	<div class="wpdf-col wpdf-col__half">
		<label for="" class="wpdf-label">Maximum file size (Server Limit: <?php echo $this->getServerLimit(); ?>) <span class="wpdf-tooltip wpdf-tooltip__inline" title="Maximum size of file allowed to be uploaded">?</span></label>
		<input type="number" class="wpdf-input" name="field[][max_file_size]" value="<?php echo intval($this->getMaxFileSize()); ?>">
	</div>
	<div class="wpdf-col wpdf-col__half">
		<label for="" class="wpdf-label">Allowed Extensions <span class="wpdf-tooltip wpdf-tooltip__inline" title="Comma separated list of allowed file extensions (jpg,jpeg,png)">?</span></label>
		<input type="text" class="wpdf-input" name="field[][allowed_ext]" value="<?php echo $this->getAllowedExt(); ?>">
	</div>
</div>