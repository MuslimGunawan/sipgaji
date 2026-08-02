import ftplib

FTP_HOST = "ftpupload.net"
FTP_USER = "if0_42559479"
FTP_PASS = "skuy12345678"
REMOTE_ROOT = "/htdocs"

try:
    ftp = ftplib.FTP()
    ftp.connect(FTP_HOST, 21, timeout=30)
    ftp.login(FTP_USER, FTP_PASS)
    ftp.cwd(REMOTE_ROOT)

    files_to_delete = ['unzip_and_import.php', 'deploy.zip', 'sipgaji.sql']
    for f in files_to_delete:
        try:
            ftp.delete(f)
            print(f"Deleted remote temporary file: {f}")
        except Exception as e:
            print(f"File {f} already deleted or not found.")

    ftp.quit()
    print("Cleanup completed!")
except Exception as e:
    print("Cleanup error:", e)
