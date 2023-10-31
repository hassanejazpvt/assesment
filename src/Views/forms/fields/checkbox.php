<input type="hidden" name="fields[<?php echo $field['id']; ?>]" value="0">
<div class="form-check">
    <input type="checkbox" class="form-check-input" name="fields[<?php echo $field['id']; ?>]" value="1" id="field_no_<?php echo $index + 1; ?>">
    <label class="form-check-label" <?php echo $isRequired ? 'required' : ''; ?> for="field_no_<?php echo $index + 1; ?>"><?php echo $field_name; ?></label>
</div>