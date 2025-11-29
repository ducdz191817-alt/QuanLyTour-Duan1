<?php
ob_start();
?>
<div class="login-wrapper">
    <div class="col-12 col-md-8 col-lg-5 col-xl-4">
        <div class="card login-card shadow-lg border-0">
            <div class="login-header text-center text-white">
                <a href="<?= BASE_URL ?>" class="text-white text-decoration-none">
                    <div class="brand-icon mb-2"><i class="bi bi-airplane-fill"></i></div>
                    <h2><strong>Quản Lý Tour FPOLY</strong></h2>
                </a>
                <div class="mt-2 fw-light fst-italic" style="font-size: 1rem;">Hệ thống quản lý tour chuyên nghiệp</div>
            </div>
            <div class="card-body">
                <h4 class="card-title text-center mb-4 fw-bold card-title-login">Tạo tài khoản mới</h4>
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger fade show" role="alert">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                        <strong>Lỗi</strong>
                    </div>
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <form action="<?= BASE_URL ?>check-register" method="post" novalidate>
                    <div class="mb-3">
                        <label for="registerName" class="form-label fw-semibold">Họ và tên</label>
                        <input type="text" id="registerName" name="name" class="form-control" value="<?= htmlspecialchars($name ?? '') ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="registerEmail" class="form-label fw-semibold">Email</label>
                        <input type="email" id="registerEmail" name="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label fw-semibold">Mật khẩu</label>
                        <input type="password" id="registerPassword" name="password" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="registerPasswordConfirm" class="form-label fw-semibold">Xác nhận mật khẩu</label>
                        <input type="password" id="registerPasswordConfirm" name="password_confirm" class="form-control" required />
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">Tạo tài khoản</button>
                    </div>
                    <div class="text-center small">
                        <a href="<?= BASE_URL ?>login" class="text-decoration-none">Đã có tài khoản? Đăng nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.AuthLayout', [
    'title' => $title ?? 'Đăng ký',
    'content' => $content,
]);
?>
