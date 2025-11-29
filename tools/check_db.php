<?php
// Công cụ đơn giản kiểm tra kết nối CSDL và bảng `tb_user`.
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/helpers/database.php';

$pdo = getDB();
if (!$pdo) {
    echo "Kết nối DB thất bại. Kiểm tra cấu hình `config/config.php` và khởi động MySQL.\n";
    exit(1);
}

    // Kiểm tra xem bảng tb_user tồn tại
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'tb_user'");
    $row = $stmt->fetch();
    if (!$row) {
        echo "Bảng 'tb_user' không tồn tại trong DB. Hãy chạy `database/schema.sql`.\n";
        exit(1);
    }
    echo "Kết nối DB OK, bảng 'tb_user' tồn tại.\n";
    // show count
    $cnt = $pdo->query('SELECT COUNT(*) AS c FROM tb_user')->fetchColumn();
    echo "Số bản ghi tb_user hiện tại: " . (int)$cnt . "\n";
    // Check tb_role
    $r = $pdo->query("SHOW TABLES LIKE 'tb_role'")->fetch();
    if (!$r) {
        echo "Bảng 'tb_role' không tồn tại. Bạn có thể cần tạo role mặc định trong database/schema.sql.\n";
    } else {
        $roleCnt = $pdo->query('SELECT COUNT(*) AS c FROM tb_role')->fetchColumn();
        echo "Số bản ghi tb_role hiện tại: " . (int)$roleCnt . "\n";
    }
} catch (PDOException $e) {
    echo "Lỗi khi kiểm tra DB: " . $e->getMessage() . "\n";
}
