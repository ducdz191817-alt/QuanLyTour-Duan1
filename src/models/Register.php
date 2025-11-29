<?php
// Model đăng ký tài khoản cho bảng tb_user
class Register {
	// Kiểm tra email đã tồn tại chưa
	public static function existsByEmail($email) {
		$pdo = getDB();
		$stmt = $pdo->prepare('SELECT COUNT(*) FROM tb_user WHERE email = :email');
		$stmt->execute(['email' => $email]);
		return $stmt->fetchColumn() > 0;
	}

	// Tạo tài khoản mới
	public static function create($data) {
		$pdo = getDB();
		$user_name = $data['user_name'] ?? $data['email'];
		$full_name = $data['full_name'] ?? $data['user_name'] ?? '';
		$email = $data['email'] ?? '';
		$pass_word = password_hash($data['pass_word'] ?? '', PASSWORD_DEFAULT);
		$phone = $data['phone'] ?? '';
		$id_role = $data['id_role'] ?? 3; // 3: user
		$gender = $data['gender'] ?? 0;

		$stmt = $pdo->prepare('INSERT INTO tb_user (user_name, pass_word, full_name, email, phone, id_role, gender) VALUES (:user_name, :pass_word, :full_name, :email, :phone, :id_role, :gender)');
		$ok = $stmt->execute([
			'user_name' => $user_name,
			'pass_word' => $pass_word,
			'full_name' => $full_name,
			'email' => $email,
			'phone' => $phone,
			'id_role' => $id_role,
			'gender' => $gender
		]);
		if (!$ok) return false;
		return [
			'id' => $pdo->lastInsertId(),
			'user_name' => $user_name,
			'full_name' => $full_name,
			'email' => $email,
			'phone' => $phone,
			'id_role' => $id_role,
			'gender' => $gender
		];
	}
}
