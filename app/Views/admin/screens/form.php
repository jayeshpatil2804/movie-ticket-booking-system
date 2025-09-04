<div class="container mt-4">
    <h2><?= isset($screen) ? 'Edit Screen' : 'Add New Screen' ?></h2>
    <hr>
    <form action="<?= isset($screen) ? base_url('admin/screens/update/' . $screen['id']) : base_url('admin/screens/store') ?>" method="post">
        <div class="mb-3">
            <label for="cinema_id" class="form-label">Select Cinema</label>
            <select class="form-control" id="cinema_id" name="cinema_id" required>
                <option value="">-- Select a Cinema --</option>
                <?php foreach ($cinemas as $cinema): ?>
                    <option value="<?= $cinema['id'] ?>" <?= isset($screen) && $screen['cinema_id'] == $cinema['id'] ? 'selected' : '' ?>>
                        <?= $cinema['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Screen Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= isset($screen) ? $screen['name'] : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="rows" class="form-label">Number of Rows</label>
            <input type="number" class="form-control" id="rows" name="rows" value="<?= isset($screen) ? $screen['rows'] : '' ?>" required min="1">
        </div>
        <div class="mb-3">
            <label for="seats_per_row" class="form-label">Seats per Row</label>
            <input type="number" class="form-control" id="seats_per_row" name="seats_per_row" value="<?= isset($screen) ? $screen['seats_per_row'] : '' ?>" required min="1">
        </div>
        <button type="submit" class="btn btn-primary"><?= isset($screen) ? 'Update' : 'Save' ?></button>
        <a href="<?= base_url('admin/screens') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>