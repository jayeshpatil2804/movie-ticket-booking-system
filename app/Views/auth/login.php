<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Login</h3>
            </div>
            <div class="card-body">
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?= form_open('auth/processLogin') ?>
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
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>