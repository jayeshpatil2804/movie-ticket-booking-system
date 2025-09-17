<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Manage Cinemas</h2>
        <a href="<?= base_url('admin/cinemas/create') ?>" class="btn btn-success">Add New Cinema</a>
    </div>
    <hr>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cinemas as $cinema): ?>
            <tr>
                <td><?= $cinema['id'] ?></td>
                <td><?= $cinema['name'] ?></td>
                <td><?= $cinema['location'] ?></td>
                <td>
                    <a href="<?= base_url('admin/cinemas/edit/' . $cinema['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="<?= base_url('admin/cinemas/delete/' . $cinema['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this cinema?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>