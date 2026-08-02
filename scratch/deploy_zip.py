import os
import sys
import ftplib
import zipfile
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
ZIP_FILE_PATH = r"c:\laragon\www\sipgaji\deploy.zip"

def prepare_build():
    print("--- 1. Preparing Build & Packing Zip ---")
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

    # Ensure uploads exists in root deploy_build as well for shared hosting compatibility
    public_uploads = os.path.join(DEPLOY_BUILD_DIR, 'public', 'uploads')
    root_uploads = os.path.join(DEPLOY_BUILD_DIR, 'uploads')
    if os.path.exists(public_uploads):
        shutil.copytree(public_uploads, root_uploads, dirs_exist_ok=True)

    # Root index.php with CI4 v4.7 Boot::bootWeb
    index_php_content = """<?php
// CodeIgniter 4.7 InfinityFree Production Entry Point
use CodeIgniter\\Boot;
use Config\\Paths;

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

require FCPATH . '../app/Config/Paths.php';
$paths = new Paths();

require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
"""
    with open(os.path.join(DEPLOY_BUILD_DIR, 'index.php'), 'w') as f:
        f.write(index_php_content)

    # Root .htaccess with InfinityFree compatible URL rewriting & asset mapping
    htaccess_content = """<IfModule mod_rewrite.c>
    Options -Indexes
    RewriteEngine On

    # Map uploads folder directly
    RewriteRule ^uploads/(.*)$ public/uploads/$1 [L]

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L,QSA]
</IfModule>
"""
    with open(os.path.join(DEPLOY_BUILD_DIR, '.htaccess'), 'w') as f:
        f.write(htaccess_content)

    # Production .env
    env_content = """# Production Environment Config for InfinityFree
CI_ENVIRONMENT = production

app.forceGlobalSecureRequests = false
app.indexPage = ''

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

    # Copy sipgaji.sql
    shutil.copy(os.path.join(LOCAL_PROJECT_DIR, 'sipgaji.sql'), os.path.join(DEPLOY_BUILD_DIR, 'sipgaji.sql'))

    # Create Zip Archive
    if os.path.exists(ZIP_FILE_PATH):
        os.remove(ZIP_FILE_PATH)

    print("Creating ZIP file...")
    with zipfile.ZipFile(ZIP_FILE_PATH, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(DEPLOY_BUILD_DIR):
            for file in files:
                abs_path = os.path.join(root, file)
                rel_path = os.path.relpath(abs_path, DEPLOY_BUILD_DIR)
                zipf.write(abs_path, rel_path)

    print(f"Zip created successfully! Size: {os.path.getsize(ZIP_FILE_PATH) / (1024*1024):.2f} MB")

    # Create unzip_and_import.php script
    unzip_script_content = """<?php
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
        
        $statements = array_filter(array_map('trim', explode(";\n", $sql)));
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
?>"""

    with open(r"c:\laragon\www\sipgaji\unzip_and_import.php", "w") as f:
        f.write(unzip_script_content)

def upload_zip_via_ftp():
    print("\n--- 2. Connecting to FTP ---")
    ftp = ftplib.FTP()
    ftp.connect(FTP_HOST, 21, timeout=60)
    ftp.login(FTP_USER, FTP_PASS)
    print("FTP Login Successful!")

    ftp.cwd(REMOTE_ROOT)
    print(f"Current Remote Dir: {ftp.pwd()}")

    # Upload deploy.zip
    print("Uploading deploy.zip...")
    with open(ZIP_FILE_PATH, 'rb') as f:
        ftp.storbinary('STOR deploy.zip', f)
    print("Uploaded deploy.zip!")

    # Upload unzip_and_import.php
    print("Uploading unzip_and_import.php...")
    with open(r"c:\laragon\www\sipgaji\unzip_and_import.php", 'rb') as f:
        ftp.storbinary('STOR unzip_and_import.php', f)
    print("Uploaded unzip_and_import.php!")

    ftp.quit()

if __name__ == "__main__":
    prepare_build()
    upload_zip_via_ftp()
