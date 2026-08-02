import ftplib
import pymysql
import os

print("--- Testing FTP Connection ---")
try:
    ftp = ftplib.FTP()
    ftp.connect("ftpupload.net", 21, timeout=10)
    ftp.login("if0_42559479", "skuy12345678")
    print("FTP Login Successful! Remote Dir List:")
    print(ftp.nlst())
    ftp.quit()
except Exception as e:
    print("FTP Error:", e)

print("\n--- Testing MySQL Connection ---")
try:
    conn = pymysql.connect(
        host="sql211.infinityfree.com",
        user="if0_42559479",
        password="skuy12345678",
        database="if0_42559479_sipgaji",
        port=3306,
        connect_timeout=10
    )
    print("MySQL Connection Successful!")
    conn.close()
except Exception as e:
    print("MySQL Error:", e)
