<?php
$config = require_once __DIR__ . '/config/database.php';

try {
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
    $db = new PDO($dsn, $config['username'], $config['password'], $config['options']);

    $name = 'parth 309';
    $password = 'i love dhamu';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Try to update existing admin first
    $stmt = $db->query("SELECT id FROM admins LIMIT 1");
    $admin = $stmt->fetch();

    if ($admin) {
        $stmt = $db->prepare("UPDATE admins SET name = :name, password = :password, email = :email WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'password' => $hashedPassword,
            'email' => str_replace(' ', '', $name) . '@admin.com',
            'id' => $admin['id']
        ]);
        echo "Updated existing admin.\n";
    } else {
        $stmt = $db->prepare("INSERT INTO admins (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([
            'name' => $name,
            'email' => str_replace(' ', '', $name) . '@admin.com',
            'password' => $hashedPassword
        ]);
        echo "Created new admin.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
