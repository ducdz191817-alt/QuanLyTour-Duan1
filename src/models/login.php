<?php
// Model đăng nhập cho bảng tb_user
class Login {
	// Xác thực đăng nhập
	public static function authenticate($email, $password) {
		$pdo = getDB();
		$stmt = $pdo->prepare('SELECT * FROM tb_user WHERE email = :email LIMIT 1');
		$stmt->execute(['email' => $email]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$user) return false;
		// Kiểm tra mật khẩu đã hash
		if (!isset($user['pass_word']) || !password_verify($password, $user['pass_word'])) return false;
		// Trả về đúng các trường cần thiết
		return [
			'id' => $user['id'],
			'user_name' => $user['user_name'],
			'full_name' => $user['full_name'],
			'email' => $user['email'],
			'id_role' => $user['id_role'],
			'status' => 1,
		];
	}
}
