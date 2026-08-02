import os
import sys
import ftplib
import shutil
import urllib.request
import time

FTP_HOST = "ftpupload.net"
FTP_USER = "if0_42559479"
FTP_PASS = "skuy12345678"
REMOTE_ROOT = "/htdocs"
DOMAIN = "http://sipgaji.fwh.is"

LOCAL_PROJECT_DIR = r"c:\laragon\www\sipgaji"
DEPLOY_BUILD_DIR = r"c:\laragon\www\sipgaji\deploy_build"

def prepare_build():
    print("--- 1. Preparing Build Directory ---")
    if os.path.exists(DEPLOY_BUILD_DIR):
        shutil.rmtree(DEPLOY_BUILD_DIR)
    os.makedirs(DEPLOY_BUILD_DIR)

    # Copy core directories
    dirs_to_copy = ['app', 'system', 'public', 'vendor', 'writable']
    for d in dirs_to_copy:
        src = os.path.join(LOCAL_PROJECT_DIR, d)
        dst = os.path.join(DEPLOY_BUILD_DIR, d)
        if os.path.exists(src):
            shutil.copytree(src, dst, ignore=shutil.ignore_patterns('.git', '.github', 'node_modules', '*.log'))

    # Create root index.php that points to CI4 bootstrap
    index_php_content = """<?php
// CodeIgniter 4 InfinityFree Production Entry Point
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);

require FCPATH . '../app/Config/Paths.php';
$paths = new Config\\Paths();
require $paths->systemDirectory . '/bootstrap.php';

$app = Config\\Services::codeigniter();
$app->initialize();
$app->run();
"""
    with open(os.path.join(DEPLOY_BUILD_DIR, 'index.php'), 'w') as f:
        f.write(index_php_content)

    # Create root .htaccess for clean URLs without index.php
    htaccess_content = """<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L,QSA]
</IfModule>
"""
    with open(os.path.join(DEPLOY_BUILD_DIR, '.htaccess'), 'w') as f:
        f.write(htaccess_content)

    # Create Production .env file
    env_content = """# Production Environment Config for InfinityFree
CI_ENVIRONMENT = production

app.baseURL = 'http://sipgaji.fwh.is/'
app.forceGlobalSecureRequests = false

database.default.hostname = sql211.infinityfree.com
database.default.database = if0_42559479_sipgaji
database.default.username = if0_42559479
database.default.password = "skuy12345678"
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_general_ci
"""
    with open(os.path.join(DEPLOY_BUILD_DIR, '.env'), 'w') as f:
        f.write(env_content)

    # Copy sipgaji.sql for import
    shutil.copy(os.path.join(LOCAL_PROJECT_DIR, 'sipgaji.sql'), os.path.join(DEPLOY_BUILD_DIR, 'sipgaji.sql'))

    # Create import_db.php for executing database restoration
    import_db_content = """<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'sql211.infinityfree.com';
$user = 'if0_42559479';
$pass = 'skuy12345678';
$db   = 'if0_42559479_sipgaji';

echo "<h2>Starting Database Restoration...</h2>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "<p style='color:green;'>Connected to MySQL Database: $db successfully!</p>";

    $sqlFile = __DIR__ . '/sipgaji.sql';
    if (!file_exists($sqlFile)) {
        die("<p style='color:red;'>File sipgaji.sql not found!</p>");
    }

    $sql = file_get_contents($sqlFile);
    
    // Disable FK checks and execute queries
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    
    // Split statements by semicolon
    $statements = array_filter(array_map('trim', explode(";\n", $sql)));
    
    $successCount = 0;
    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            $pdo->exec($stmt);
            $successCount++;
        }
    }
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1; executed queries: $successCount");
    echo "<h3 style='color:green;'>SUCCESS: Database restored cleanly with $successCount queries executed!</h3>";
} catch (Exception $e) {
    echo "<h3 style='color:red;'>ERROR: " . htmlspecialchars($e->getMessage()) . "</h3>";
}
?>"""
    with open(os.path.join(DEPLOY_BUILD_DIR, 'import_db.php'), 'w') as f:
        f.write(import_db_content)

    print("Build Directory Prepared successfully!")

def upload_via_ftp():
    print("\n--- 2. Connecting to FTP ---")
    ftp = ftplib.FTP()
    ftp.connect(FTP_HOST, 21, timeout=30)
    ftp.login(FTP_USER, FTP_PASS)
    print("FTP Login Successful!")

    ftp.cwd(REMOTE_ROOT)
    print(f"Current Remote Dir: {ftp.pwd()}")

    def upload_dir(local_path, remote_path):
        for item in os.listdir(local_path):
            l_item = os.path.join(local_path, item)
            r_item = item
            if os.path.isdir(l_item):
                try:
                    ftp.mkd(r_item)
                except Exception:
                    pass # Directory might exist
                ftp.cwd(r_item)
                upload_dir(l_item, r_item)
                ftp.cwd('..')
            else:
                with open(l_item, 'rb') as f:
                    ftp.storbinary(f'STOR {r_item}', f)
                print(f"Uploaded: {os.path.relpath(l_item, DEPLOY_BUILD_DIR)}")

    print("\n--- 3. Uploading Files to InfinityFree htdocs ---")
    upload_dir(DEPLOY_BUILD_DIR, REMOTE_ROOT)
    ftp.quit()
    print("All files uploaded via FTP successfully!")

def trigger_db_import():
    print("\n--- 4. Triggering Remote Database Import ---")
    url = f"{DOMAIN}/import_db.php"
    print(f"Fetching: {url}")
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req) as resp:
            content = resp.read().decode('utf-8')
            print("Response from server:")
            print(content)
    except Exception as e:
        print("HTTP Request Error:", e)

if __name__ == "__main__":
    prepare_build()
    upload_via_ftp()
    trigger_db_import()
