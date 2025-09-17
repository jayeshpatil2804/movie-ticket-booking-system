<div class="container mt-4">
    <h2><?= isset($cinema) ? 'Edit Cinema' : 'Add New Cinema' ?></h2>
    <hr>
    <form action="<?= isset($cinema) ? base_url('admin/cinemas/update/' . $cinema['id']) : base_url('admin/cinemas/store') ?>" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Cinema Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= isset($cinema) ? $cinema['name'] : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="location" name="location" rows="3"><?= isset($cinema) ? $cinema['location'] : '' ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?= isset($cinema) ? 'Update' : 'Save' ?></button>
        <a href="<?= base_url('admin/cinemas') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>