<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>InfinityFree Automated Deployer</h1>";

// 1. Unzip deploy.zip
$zipFile = __DIR__ . '/deploy.zip';
if (file_exists($zipFile)) {
    echo "<p>Extracting deploy.zip...</p>";
    $zip = new ZipArchive();
    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo(__DIR__);
        $zip->close();
        echo "<p style='color:green;'>SUCCESS: Extracted deploy.zip successfully!</p>";
        @unlink($zipFile);
    } else {
        echo "<p style='color:red;'>ERROR: Failed to open deploy.zip</p>";
    }
} else {
    echo "<p style='color:orange;'>Notice: deploy.zip already extracted or missing.</p>";
}

// 2. Import Database
$host = 'sql211.infinityfree.com';
$user = 'if0_42559479';
$pass = 'skuy12345678';
$db   = 'if0_42559479_sipgaji';

echo "<p>Importing Database to MySQL ($db)...</p>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $sqlFile = __DIR__ . '/sipgaji.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
        
        $statements = array_filter(array_map('trim', explode(";
", $sql)));
        $count = 0;
        foreach ($statements as $stmt) {
            if (!empty($stmt)) {
                $pdo->exec($stmt);
                $count++;
            }
        }
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
        echo "<h3 style='color:green;'>SUCCESS: Database restored cleanly ($count queries executed)!</h3>";
    } else {
        echo "<p style='color:red;'>ERROR: sipgaji.sql not found!</p>";
    }
} catch (Exception $e) {
    echo "<h3 style='color:red;'>MYSQL ERROR: " . htmlspecialchars($e->getMessage()) . "</h3>";
}

echo "<h2>Deployment Finished! You can now visit <a href='/'>SIPGAJI Homepage</a></h2>";
?>