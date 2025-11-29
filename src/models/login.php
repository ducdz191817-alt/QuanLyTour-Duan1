<?php

// Model Login: xử lý xác thực (đăng nhập)
class Login
{
    // Thử xác thực bằng email + password
    // @param string $email
    // @param string $password
    // @return array|null trả về mảng user (id, name, email, role, status) nếu đúng, null nếu sai
    public static function authenticate(string $email, string $password)
    {
        $pdo = getDB();
        if (!$pdo) return null;

        $stmt = $pdo->prepare('SELECT id, name, email, password, role, status FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }

        if (!password_verify($password, $row['password'])) {
            return null;
        }

        // Remove password when returning
        unset($row['password']);
        return $row;
    }

    // Tùy chọn: Lấy user theo ID (dùng cho cookie hoặc session rebuild)
    public static function getById(int $id)
    {
        $pdo = getDB();
        if (!$pdo) return null;
        $stmt = $pdo->prepare('SELECT id, name, email, role, status FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
}
