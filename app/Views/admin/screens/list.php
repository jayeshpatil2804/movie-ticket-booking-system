<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Manage Screens</h2>
        <a href="<?= base_url('admin/screens/create') ?>" class="btn btn-success">Add New Screen</a>
    </div>
    <hr>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cinema</th>
                <th>Screen Name</th>
                <th>Rows</th>
                <th>Seats per Row</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($screens as $screen): ?>
            <tr>
                <td><?= $screen['id'] ?></td>
                <td><?= $screen['cinema_name'] ?></td>
                <td><?= $screen['name'] ?></td>
                <td><?= $screen['rows'] ?></td>
                <td><?= $screen['seats_per_row'] ?></td>
                <td>
                    <a href="<?= base_url('admin/screens/edit/' . $screen['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="<?= base_url('admin/screens/delete/' . $screen['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this screen?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>