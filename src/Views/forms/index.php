<?php echo view('layouts/header.php'); ?>
<h1>Saved Forms</h1>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Title</th>
            <th scope="col">Fields Count</th>
            <th scope="col">Entries Count</th>
            <th scope="col">Created At</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($forms as $index => $form) { ?>
            <tr>
                <th scope="row"><?php echo $index + 1; ?></th>
                <td><?php echo $form['name']; ?></td>
                <td><?php echo $form['title']; ?></td>
                <td><?php echo $form['fields_count']; ?></td>
                <td><?php echo $form['entries_count']; ?></td>
                <td><?php echo date('M d, Y - h:i A', strtotime($form['created_at'])); ?></td>
                <td>
                    <a class="btn text-white btn-info btn-sm" href="<?php echo url("forms/{$form['id']}"); ?>">View</a>
                    <a class="btn text-white btn-danger btn-sm" onclick="return confirm('Are you sure you want to perform this action?')" href="<?php echo url("forms/{$form['id']}/delete"); ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
        <?php if (count($forms) == 0) { ?>
            <tr>
                <td class="text-center" colspan="7">No records found!</td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php echo view('layouts/footer.php'); ?>