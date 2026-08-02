import subprocess
import os

try:
    # Use mysqldump or raw php PDO to dump database sipgaji to sipgaji.sql
    php_code = """<?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db   = 'sipgaji';

    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    
    $tables = ['users', 'jabatan', 'karyawan', 'presensi', 'penggajian'];
    $sql = "SET FOREIGN_KEY_CHECKS = 0;\\n\\n";

    foreach ($tables as $table) {
        $sql .= "DROP TABLE IF EXISTS `$table`;\\n";
        $createStmt = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
        $sql .= $createStmt['Create Table'] . ";\\n\\n";

        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $cols = array_keys($row);
                $vals = array_map(function($val) use ($pdo) {
                    if ($val === null) return 'NULL';
                    return $pdo->quote($val);
                }, array_values($row));

                $sql .= "INSERT INTO `$table` (`" . implode("`, `", $cols) . "`) VALUES (" . implode(", ", $vals) . ");\\n";
            }
            $sql .= "\\n";
        }
    }
    $sql .= "SET FOREIGN_KEY_CHECKS = 1;\\n";

    file_put_contents('c:/laragon/www/sipgaji/sipgaji.sql', $sql);
    echo "SQL Dump exported successfully. Total bytes: " . strlen($sql);
    """
    
    with open("scratch/dump.php", "w") as f:
        f.write(php_code)

    res = subprocess.run(["php", "scratch/dump.php"], capture_output=True, text=True)
    print(res.stdout)
    if res.stderr:
        print(res.stderr)
except Exception as e:
    print("Error:", e)
