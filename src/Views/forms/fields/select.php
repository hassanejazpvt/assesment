<select <?php echo $isRequired ? 'required' : ''; ?> class="form-select" name="fields[<?php echo $field['id']; ?>]" id="field_no_<?php echo $index + 1; ?>">
    <?php foreach ($options as $optIndex => $option) { ?>
        <option><?php echo $option; ?></option>
    <?php } ?>
</select>