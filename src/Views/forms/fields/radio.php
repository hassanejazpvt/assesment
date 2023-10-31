<legend class="col-form-label col-sm-2 pt-0"><?php echo $field_name; ?></legend>
<?php foreach ($options as $optIndex => $option) { ?>
    <div class="form-check">
        <input class="form-check-input" type="radio" <?php echo $isRequired ? 'required' : ''; ?> name="fields[<?php echo $field['id']; ?>]" id="field_no_<?php echo $index + 1; ?>_opt_no_<?php echo $optIndex + 1; ?>" value="<?php echo $option; ?>" <?php echo 0 == $optIndex ? 'checked' : ''; ?>>
        <label class="form-check-label" for="field_no_<?php echo $index + 1; ?>_opt_no_<?php echo $optIndex + 1; ?>"><?php echo $option; ?></label>
    </div>
<?php } ?>