<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Register</h3>
            </div>
            <div class="card-body">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?= form_open('auth/processRegister') ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="<?= old('name') ?>">
                        <?php if($validation->hasError('name')): ?>
                            <div class="text-danger mt-1"><?= $validation->getError('name') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" value="<?= old('email') ?>">
                        <?php if($validation->hasError('email')): ?>
                            <div class="text-danger mt-1"><?= $validation->getError('email') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password">
                        <?php if($validation->hasError('password')): ?>
                            <div class="text-danger mt-1"><?= $validation->getError('password') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="pass_confirm" class="form-label">Confirm Password</label>
                        <input type="password" name="pass_confirm" class="form-control" id="pass_confirm">
                        <?php if($validation->hasError('pass_confirm')): ?>
                            <div class="text-danger mt-1"><?= $validation->getError('pass_confirm') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>