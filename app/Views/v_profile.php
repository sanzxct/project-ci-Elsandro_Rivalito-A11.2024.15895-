<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    
                    <div class="p-4 mt-3" style="background-color: #f6f9ff; border-radius: 8px;">
                        
                        <h6 class="mb-4" style="font-weight: 700; color: #012970; font-size: 1.1rem;">
                            Profile Information
                        </h6>

                        <div class="row mb-3 align-items-center">
                            <div class="col-lg-3 col-md-4 label" style="color: #4154f1; font-weight: 600;">
                                Username
                            </div>
                            <div class="col-lg-9 col-md-8">
                                <span class="fw-bold"><?= esc(session()->get('username')); ?></span>
                                <span class="badge bg-danger ms-2 px-2 py-1"><?= esc(session()->get('role')); ?></span>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <div class="col-lg-3 col-md-4 label" style="color: #4154f1; font-weight: 600;">
                                Email
                            </div>
                            <div class="col-lg-9 col-md-8" style="color: #4154f1;">
                                <?= esc(session()->get('email')); ?>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <div class="col-lg-3 col-md-4 label" style="color: #4154f1; font-weight: 600;">
                                Login Time
                            </div>
                            <div class="col-lg-9 col-md-8 text-muted">
                                <?= esc(session()->get('login_time')); ?>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-lg-3 col-md-4 label" style="color: #4154f1; font-weight: 600;">
                                Status
                            </div>
                            <div class="col-lg-9 col-md-8">
                                <span class="badge bg-success px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i> Sudah Login
                                </span>
                            </div>
                        </div>

                    </div> </div>
            </div> </div>
    </div>
</section>

<?= $this->endSection() ?>