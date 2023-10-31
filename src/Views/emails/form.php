<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submitted Fields</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0;">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding: 20px;">
                <table width="400" cellspacing="0" cellpadding="0" style="background-color: #fff; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); padding: 20px;">
                    <tr>
                        <td align="center">
                            <h1 style="text-align: center;">Submitted Fields</h1>
                        </td>
                    </tr>
                    <?php foreach ($emailFields as $field) { ?>
                        <?php if (isset($submittedFields[$field['id']])) { ?>
                            <tr>
                                <td style="margin-bottom: 10px;">
                                    <label for="field1" style="font-weight: bold;"><?php echo $field['field_name']; ?>:</label>
                                    <p style="padding: 5px; background-color: #f9f9f9; border: 1px solid #ccc; border-radius: 3px;"><?php echo $submittedFields[$field['id']]; ?></p>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>