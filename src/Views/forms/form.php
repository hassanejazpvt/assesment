<?php echo view('layouts/header.php'); ?>
<div class="alert alert-danger" style="display: none;" id="form-errors-alert" role="alert"></div>
<div class="alert alert-success" style="display: none;" id="form-success-alert" role="alert"></div>
<h3><?php echo $form['title']; ?></h3>
<form class="row g-3" method="post" id="submitForm" action="<?php echo url("forms/{$form['id']}/submit"); ?>">
    <?php foreach ($fields as $index => $field) { ?>
        <?php
            $field_name = ucwords(str_replace(['_', '-'], ' ', $field['field_name']));
        $options        = array_filter(json_decode($field['options'] ?: '') ?: []);
        $validations    = array_filter(json_decode($field['validations'] ?: '') ?: []);
        $isEmail        = in_array('email', $validations);
        $isNumber       = in_array('number', $validations);
        $isRequired     = in_array('required', $validations);
        $minlength      = $maxlength = $min = $max = $pattern = null;
        foreach ($validations as $validation) {
            $validationExploded = explode(':', $validation, 2);
            if (isset($validationExploded[0]) && isset($validationExploded[1])) {
                if ('minlength' == $validationExploded[0]) {
                    $minlength = $validationExploded[1];
                }
                if ('maxlength' == $validationExploded[0]) {
                    $maxlength = $validationExploded[1];
                }
                if ('min' == $validationExploded[0]) {
                    $min = $validationExploded[1];
                }
                if ('max' == $validationExploded[0]) {
                    $max = $validationExploded[1];
                }
                if ('pattern' == $validationExploded[0]) {
                    $pattern = str_replace('\\\\', '\\', $validationExploded[1]);
                }
            }
        }
        ?>
        <div class="col-md-6">
            <?php if (! in_array($field['field_type'], ['checkbox', 'radio'])) { ?>
                <label for="field_no_<?php echo $index + 1; ?>" class="form-label"><?php echo $field_name; ?></label>
            <?php } ?>
            <?php echo @view("forms/fields/{$field['field_type']}.php", compact('index', 'field', 'field_name', 'options', 'isEmail', 'isNumber', 'isRequired', 'minlength', 'maxlength', 'min', 'max', 'pattern')); ?>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
    <div class="col-4">
        <!-- CAPTCHA field -->
        <label for="captcha">Please enter the text you see in the image:</label>
        <div class="d-flex">
            <input type="text" id="captcha" class="form-control" name="captcha" required>
            <img src="<?php echo url('captcha'); ?>" alt="CAPTCHA">
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-info btn-sm text-white">Submit</button>
    </div>
    <input type="hidden" name="form_id" value="<?php echo $form['id']; ?>" />
</form>
<?php echo view('layouts/footer.php'); ?>