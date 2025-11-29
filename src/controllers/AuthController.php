<?php

// Controller xử lý các chức năng liên quan đến xác thực (đăng nhập, đăng xuất)
class AuthController
{
    
    // Hiển thị form đăng nhập
    public function login()
    {
        // Nếu đã đăng nhập rồi thì chuyển về trang home
        if (isLoggedIn()) {
            header('Location: ' . BASE_URL . 'home');
            exit;   
        }

        // Lấy URL redirect nếu có (để quay lại trang đang xem sau khi đăng nhập)
        // Mặc định redirect về trang home
        $redirect = $_GET['redirect'] ?? BASE_URL . 'home';

        // Hiển thị view login
        view('auth.login', [
            'title' => 'Đăng nhập',
            'redirect' => $redirect,
        ]);
    }

    // Hiển thị form đăng ký
    public function register()
    {
        if (isLoggedIn()) {
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        view('auth.register', [
            'title' => 'Đăng ký',
        ]);
    }

    // Xử lý đăng ký (nhận dữ liệu POST)
    public function checkRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?act=register');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $errors = [];
        if (empty($name)) {
            $errors[] = 'Vui lòng nhập họ và tên';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        }
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }
        if ($password !== $passwordConfirm) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }

        // Nếu đã có lỗi thì show view lại
        if (!empty($errors)) {
            view('auth.register', [
                'title' => 'Đăng ký',
                'errors' => $errors,
                'name' => $name,
                'email' => $email,
            ]);
            return;
        }

        // Kiểm tra trùng email
        if (Register::existsByEmail($email)) {
            $errors[] = 'Email đã được đăng ký trước đó';
            view('auth.register', [
                'title' => 'Đăng ký',
                'errors' => $errors,
                'name' => $name,
                'email' => $email,
            ]);
            return;
        }

        // Tạo account mới
        $newUser = Register::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        if (!$newUser) {
            $errors[] = 'Không thể tạo tài khoản (lỗi hệ thống)';
            view('auth.register', [
                'title' => 'Đăng ký',
                'errors' => $errors,
                'name' => $name,
                'email' => $email,
            ]);
            return;
        }

        // Đăng nhập tự động sau khi đăng ký
        $user = new User($newUser);
        loginUser($user);

        // Chuyển hướng về home
        header('Location: ' . BASE_URL . 'home');
        exit;
    }

    // Xử lý đăng nhập (nhận dữ liệu từ form POST)
    public function checkLogin()
    {
        // Chỉ xử lý khi là POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Lấy dữ liệu từ form
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        // Mặc định redirect về trang home sau khi đăng nhập
        $redirect = $_POST['redirect'] ?? BASE_URL . 'home';

        // Validate dữ liệu đầu vào
        $errors = [];

        if (empty($email)) {
            $errors[] = 'Vui lòng nhập email';
        }

        if (empty($password)) {
            $errors[] = 'Vui lòng nhập mật khẩu';
        }

        // Nếu có lỗi validation thì quay lại form login
        if (!empty($errors)) {
            view('auth.login', [
                'title' => 'Đăng nhập',
                'errors' => $errors,
                'email' => $email,
                'redirect' => $redirect,
            ]);
            return;
        }

        // Dùng model Login để xác thực
        $row = Login::authenticate($email, $password);
        if (!$row) {
            $errors[] = 'Email hoặc mật khẩu không đúng';
            view('auth.login', [
                'title' => 'Đăng nhập',
                'errors' => $errors,
                'email' => $email,
                'redirect' => $redirect,
            ]);
            return;
        }

        // Build User object from database row
        $user = new User([
            'id' => $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'role' => $row['role'] ?? 'huong_dan_vien',
            'status' => $row['status'] ?? 1,
        ]);

        // Đăng nhập thành công: lưu vào session
        loginUser($user);

        // Chuyển hướng về trang được yêu cầu hoặc trang chủ
        header('Location: ' . $redirect);
        exit;
    }

    // Xử lý đăng xuất
    public function logout()
    {
        // Xóa session và đăng xuất
        logoutUser();

        // Chuyển hướng về trang welcome
        header('Location: ' . BASE_URL . 'welcome');
        exit;
    }
}

