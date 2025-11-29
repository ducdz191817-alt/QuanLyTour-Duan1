<?php
/**
 * Script để seed dữ liệu `tb_role` và (tuỳ chọn) `tb_user` admin.
 * Sử dụng: php tools/seed_roles.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/helpers/database.php';

$pdo = getDB();
if (!$pdo) {
    echo "Kết nối DB thất bại. Kiểm tra config/config.php hoặc MySQL server.\n";
    exit(1);
}

// Roles mặc định
$roles = [
    ['id' => 1, 'name_role' => 'admin', 'mo_ta' => 'Quản trị hệ thống'],
    ['id' => 2, 'name_role' => 'huong_dan_vien', 'mo_ta' => 'Hướng dẫn viên'],
    ['id' => 3, 'name_role' => 'user', 'mo_ta' => 'Người dùng thường'],
];

try {
    $pdo->beginTransaction();
    // Tạo bảng tb_role nếu chưa có (an toàn)
    $pdo->exec("CREATE TABLE IF NOT EXISTS `tb_role` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name_role` varchar(225) NOT NULL,
        `mo_ta` varchar(225) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Insert or update role
    $insert = $pdo->prepare('INSERT INTO tb_role (id, name_role, mo_ta) VALUES (:id, :name_role, :mo_ta) ON DUPLICATE KEY UPDATE name_role = VALUES(name_role), mo_ta = VALUES(mo_ta)');
    foreach ($roles as $r) {
        $insert->execute(['id' => $r['id'], 'name_role' => $r['name_role'], 'mo_ta' => $r['mo_ta']]);
    }

    // Optional: create a default admin user if not exists
    $adminEmail = 'admin@example.com';
    $chk = $pdo->prepare("SELECT id FROM tb_user WHERE email = :email LIMIT 1");
    $chk->execute(['email' => $adminEmail]);
    $exists = $chk->fetch();
    if (!$exists) {
        $pw = password_hash('admin123', PASSWORD_DEFAULT);
        $insertUser = $pdo->prepare('INSERT INTO tb_user (user_name, pass_word, full_name, email, phone, id_role, gender) VALUES (:user_name, :pass_word, :full_name, :email, :phone, :id_role, :gender)');
        $insertUser->execute([
            'user_name' => 'admin',
            'pass_word' => $pw,
            'full_name' => 'Admin',
            'email' => $adminEmail,
            'phone' => '',
            'id_role' => 1,
            'gender' => 0,
        ]);
        echo "Admin user created: {$adminEmail} / admin123\n";
    } else {
        echo "Admin user already exists: {$adminEmail}\n";
    }

    $pdo->commit();
    echo "Seed hoàn tất: roles và admin đã được cấu hình.\n";
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Seed thất bại: " . $e->getMessage() . "\n";
}

?>
