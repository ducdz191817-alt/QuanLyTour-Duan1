<?php

// Model Register: xử lý các chức năng liên quan đến đăng ký người dùng
class Register
{
    // Kiểm tra xem email đã tồn tại chưa
    // @param string $email
    // @return bool
    public static function existsByEmail(string $email): bool
    {
        $pdo = getDB();
        if (!$pdo) return false; // nếu không có kết nối đơn giản trả false

        $stmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return ($row && (int)$row['cnt'] > 0);
    }

    // Tạo một user mới
    // @param array $data (name, email, password, role: optional)
    // @return array|false Trả về mảng dữ liệu user mới hoặc false nếu thất bại
    public static function create(array $data)
    {
        $pdo = getDB();
        if (!$pdo) return false;

        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $role = $data['role'] ?? 'huong_dan_vien';

        // Hash mật khẩu an toàn
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, status, created_at) VALUES (:name, :email, :password, :role, :status, NOW())');
        $ok = $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role,
            'status' => 1,
        ]);

        if (!$ok) {
            return false;
        }

        $id = (int)$pdo->lastInsertId();
        return [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'status' => 1,
        ];
    }

    // Lấy user theo email
    // @return array|null
    public static function getByEmail(string $email)
    {
        $pdo = getDB();
        if (!$pdo) return null;

        $stmt = $pdo->prepare('SELECT id, name, email, password, role, status FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
