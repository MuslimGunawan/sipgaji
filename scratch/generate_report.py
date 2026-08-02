import os
import sys
from reportlab.lib.pagesizes import A4
from reportlab.lib.units import cm
from reportlab.lib import colors
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, Image, KeepTogether, HRFlowable
)
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.enums import TA_CENTER, TA_JUSTIFY, TA_LEFT, TA_RIGHT

OUTPUT_PDF = r"c:\laragon\www\sipgaji\Laporan_UAS_SIPGAJI.pdf"

def create_unimal_logo():
    logo_path = r"c:\laragon\www\sipgaji\scratch\unimal_logo.png"
    if os.path.exists(logo_path):
        return logo_path

    from PIL import Image as PILImage, ImageDraw, ImageFont
    img = PILImage.new("RGBA", (400, 400), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    
    draw.ellipse([20, 20, 380, 380], outline="#059669", width=12)
    draw.ellipse([60, 60, 340, 340], outline="#d97706", width=8)
    
    draw.pieslice([100, 100, 300, 300], start=30, end=150, fill="#059669")
    draw.rectangle([180, 120, 220, 260], fill="#d97706")
    draw.polygon([(200, 70), (160, 130), (240, 130)], fill="#d97706")
    
    try:
        font = ImageFont.truetype("arial.ttf", 24)
        draw.text((170, 310), "1969", fill="#1e293b", font=font)
    except:
        draw.text((175, 310), "1969", fill="#1e293b")

    img.save(logo_path, "PNG")
    return logo_path

def build_pdf():
    logo_path = create_unimal_logo()

    # Document setup: A4, Margins: Left=4cm, Top=3cm, Right=3cm, Bottom=3cm (Width: 21 - 4 - 3 = 14cm)
    doc = SimpleDocTemplate(
        OUTPUT_PDF,
        pagesize=A4,
        leftMargin=4*cm,
        rightMargin=3*cm,
        topMargin=3*cm,
        bottomMargin=3*cm
    )

    styles = getSampleStyleSheet()

    title_style = ParagraphStyle(
        'CoverTitle',
        parent=styles['Normal'],
        fontName='Times-Bold',
        fontSize=13,
        leading=18,
        alignment=TA_CENTER
    )

    subtitle_style = ParagraphStyle(
        'CoverSubTitle',
        parent=styles['Normal'],
        fontName='Times-Bold',
        fontSize=12,
        leading=16,
        alignment=TA_CENTER
    )

    heading1_style = ParagraphStyle(
        'Heading1_Custom',
        parent=styles['Normal'],
        fontName='Times-Bold',
        fontSize=14,
        leading=18,
        spaceBefore=14,
        spaceAfter=8,
        keepWithNext=True
    )

    heading2_style = ParagraphStyle(
        'Heading2_Custom',
        parent=styles['Normal'],
        fontName='Times-Bold',
        fontSize=12,
        leading=16,
        spaceBefore=10,
        spaceAfter=6,
        keepWithNext=True
    )

    body_style = ParagraphStyle(
        'Body_Custom',
        parent=styles['Normal'],
        fontName='Times-Roman',
        fontSize=12,
        leading=18, # 1.5 spacing
        alignment=TA_JUSTIFY,
        spaceAfter=6
    )

    bullet_style = ParagraphStyle(
        'Bullet_Custom',
        parent=body_style,
        leftIndent=15,
        spaceAfter=4
    )

    table_header_style = ParagraphStyle(
        'TableHeader',
        parent=styles['Normal'],
        fontName='Times-Bold',
        fontSize=9.5,
        leading=12,
        alignment=TA_CENTER,
        textColor=colors.white
    )

    table_cell_style = ParagraphStyle(
        'TableCell',
        parent=styles['Normal'],
        fontName='Times-Roman',
        fontSize=9,
        leading=12,
        alignment=TA_LEFT
    )

    table_cell_center = ParagraphStyle(
        'TableCellCenter',
        parent=table_cell_style,
        alignment=TA_CENTER
    )

    story = []

    # COVER (Format 1)
    story.append(Paragraph("SISTEM INFORMASI PENGGAJIAN KARYAWAN DENGAN PERHITUNGAN GAJI OTOMATIS BERBASIS WEB MENGGUNAKAN CODEIGNITER 4", title_style))
    story.append(Spacer(1, 0.6*cm))
    story.append(Paragraph("PEMROGRAMAN WEB LANJUTAN<br/>KELAS A8", subtitle_style))
    story.append(Spacer(1, 1.0*cm))

    story.append(Image(logo_path, width=3.2*cm, height=3.2*cm))
    story.append(Spacer(1, 1.0*cm))

    story.append(Paragraph("TIM KELOMPOK B :", subtitle_style))
    story.append(Spacer(1, 0.3*cm))

    team_data = [
        [Paragraph("<b>Ketua Tim</b>", table_cell_style), Paragraph(":", table_cell_style), Paragraph("RAHMI SAHARA", table_cell_style), Paragraph("(NIM. 240170070)", table_cell_style)],
        [Paragraph("<b>Anggota</b>", table_cell_style), Paragraph(":", table_cell_style), Paragraph("NICOIWAN ADHA KOBAT", table_cell_style), Paragraph("(NIM. 240170207)", table_cell_style)],
        [Paragraph("", table_cell_style), Paragraph(":", table_cell_style), Paragraph("AZKAL AZKIYA", table_cell_style), Paragraph("(NIM. 240170235)", table_cell_style)],
        [Paragraph("", table_cell_style), Paragraph(":", table_cell_style), Paragraph("ZAHRA", table_cell_style), Paragraph("(NIM. 230170012)", table_cell_style)],
    ]
    t_team = Table(team_data, colWidths=[2.5*cm, 0.5*cm, 6.0*cm, 4.5*cm])
    t_team.setStyle(TableStyle([
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('LEFTPADDING', (0,0), (-1,-1), 2),
        ('RIGHTPADDING', (0,0), (-1,-1), 2),
        ('BOTTOMPADDING', (0,0), (-1,-1), 2),
        ('TOPPADDING', (0,0), (-1,-1), 2),
    ]))
    story.append(t_team)
    story.append(Spacer(1, 1.2*cm))

    story.append(Paragraph("PROGRAM STUDI TEKNIK INFORMATIKA<br/>FAKULTAS TEKNIK<br/>UNIVERSITAS MALIKUSSALEH<br/>TAHUN AKADEMIK 2025/2026", subtitle_style))
    story.append(PageBreak())

    # BAB I PENDAHULUAN
    story.append(Paragraph("BAB I PENDAHULUAN", heading1_style))
    
    story.append(Paragraph("1.1 Latar Belakang Masalah", heading2_style))
    story.append(Paragraph(
        "Pengelolaan penggajian karyawan (payroll system) merupakan salah satu elemen vital dalam operasional instansi maupun perusahaan. "
        "Proses kalkulasi gaji secara manual sering kali rentan terhadap kesalahan manusia (human error), memerlukan waktu yang lama, "
        "serta menyulitkan transparansi penyimpanan arsip bukti pembayaran. Komponen komputasi gaji mencakup berbagai variabel "
        "seperti gaji pokok, tunjangan jabatan, tunjangan kehadiran (uang makan dan transport), tunjangan keluarga, insentif lembur, "
        "hingga potongan BPJS Kesehatan (1%), BPJS Ketenagakerjaan (2%), Pajak Penghasilan (PPh 21 5%), dan sanksi alpa.", body_style
    ))
    story.append(Paragraph(
        "Oleh karena itu, dibangun aplikasi Sistem Informasi Penggajian Karyawan (SIPGAJI) berbasis web menggunakan framework CodeIgniter 4 "
        "dan basis data MySQL. Sistem ini secara aktif mengimplementasikan metode matematis formal dan logika algoritmik otomatis untuk "
        "menghitung pendapatan kotor, total potongan, dan gaji bersih (Take Home Pay) secara akurat dan efisien, lengkap dengan portal mandiri edit profil karyawan dan navigasi mobile-responsive.", body_style
    ))

    story.append(Paragraph("1.2 Rumusan Masalah", heading2_style))
    story.append(Paragraph("1. Bagaimana merancang dan membangun sistem informasi penggajian karyawan berbasis web dengan CodeIgniter 4?", bullet_style))
    story.append(Paragraph("2. Bagaimana mengimplementasikan algoritma komputasi matematis otomatis untuk menghitung gaji bersih yang mencakup seluruh komponen tunjangan dan potongan?", bullet_style))
    story.append(Paragraph("3. Bagaimana mengimplementasikan keamanan autentikasi role-based access, portal mandiri profil karyawan, serta antarmuka mobile-responsive?", bullet_style))

    story.append(Paragraph("1.3 Tujuan Pengembangan Sistem", heading2_style))
    story.append(Paragraph("1. Menghasilkan sistem penggajian karyawan otomatis berbasis CodeIgniter 4 yang responsif, terstruktur, dan aman.", bullet_style))
    story.append(Paragraph("2. Otomatisasi kalkulasi gaji bersih karyawan guna meminimalisir kesalahan perhitungan manual dan mempercepat proses pembuatan laporan gaji.", bullet_style))
    story.append(Paragraph("3. Menyediakan portal mandiri bagi karyawan untuk memperbarui profil (foto avatar, kontak, alamat, password) dan melihat transparansi rincian slip gaji.", bullet_style))

    story.append(Paragraph("1.4 Pembagian Tugas Kelompok (TIM - B)", heading2_style))
    
    tugas_data = [
        [Paragraph("<b>No</b>", table_header_style), Paragraph("<b>Nama Anggota (NIM)</b>", table_header_style), Paragraph("<b>Peran & Pembagian Tugas Spesifik</b>", table_header_style)],
        [Paragraph("1", table_cell_center), Paragraph("RAHMI SAHARA (240170070)", table_cell_style), Paragraph("Ketua Tim, Perancangan ERD Database, Konfigurasi Framework CI4 & Migration", table_cell_style)],
        [Paragraph("2", table_cell_center), Paragraph("NICOIWAN ADHA KOBAT (240170207)", table_cell_style), Paragraph("Pengembangan Modul Controller Penggajian & Logika Perhitungan Matematis", table_cell_style)],
        [Paragraph("3", table_cell_center), Paragraph("AZKAL AZKIYA (240170235)", table_cell_style), Paragraph("Pengembangan Modul Autentikasi RBAC, Master Data, & Edit Profil Karyawan", table_cell_style)],
        [Paragraph("4", table_cell_center), Paragraph("ZAHRA (230170012)", table_cell_style), Paragraph("Desain Antarmuka Mobile-Responsive Bootstrap 5, Chart.js, & Pengujiaan System", table_cell_style)],
    ]
    t_tugas = Table(tugas_data, colWidths=[1.0*cm, 4.5*cm, 8.5*cm])
    t_tugas.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#1e1b4b")),
        ('GRID', (0,0), (-1,-1), 0.5, colors.HexColor("#cbd5e1")),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('LEFTPADDING', (0,0), (-1,-1), 4),
        ('RIGHTPADDING', (0,0), (-1,-1), 4),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_tugas)
    story.append(Spacer(1, 0.4*cm))

    # BAB II LANDASAN TEORI
    story.append(Paragraph("BAB II LANDASAN TEORI", heading1_style))
    
    story.append(Paragraph("2.1 CodeIgniter 4 dan Arsitektur MVC", heading2_style))
    story.append(Paragraph(
        "CodeIgniter 4 (CI4) adalah framework pengembangan aplikasi web berbasis PHP yang menggunakan pola arsitektur Model-View-Controller (MVC). "
        "Model bertanggung jawab atas pengelolaan data dan interaksi basis data MySQL. View berfungsi menampilkan antarmuka pengguna (UI) "
        "yang dinamis. Controller bertindak sebagai jembatan yang menerima permintaan, mengeksekusi logika bisnis dan perhitungan matematis, "
        "lalu menyajikannya ke View.", body_style
    ))

    story.append(Paragraph("2.2 Metode dan Rumus Matematika Perhitungan Gaji", heading2_style))
    story.append(Paragraph("<b>1. Rumus Gaji Bersih (Take Home Pay):</b>", body_style))
    story.append(Paragraph("<i>Gaji Bersih = Total Pendapatan - Total Potongan</i>", bullet_style))
    
    story.append(Paragraph("<b>2. Rumus Total Pendapatan (Gross Income):</b>", body_style))
    story.append(Paragraph("<i>Total Pendapatan = Gaji Pokok + Tunj. Jabatan + Tunj. Kehadiran + Tunj. Keluarga + Bonus Lembur</i>", bullet_style))
    story.append(Paragraph("• Tunjangan Kehadiran = (Jumlah Hadir × Uang Makan/Hari) + (Jumlah Hadir × Transport/Hari)", bullet_style))
    story.append(Paragraph("• Tunjangan Keluarga = 10% × Gaji Pokok (Jika Menikah) + (5% × Gaji Pokok × min(Jumlah Anak, 2))", bullet_style))
    story.append(Paragraph("• Bonus Lembur = Jam Lembur × (1.5 × (Gaji Pokok / 173))", bullet_style))

    story.append(Paragraph("<b>3. Rumus Total Potongan:</b>", body_style))
    story.append(Paragraph("<i>Total Potongan = Pot. BPJS KS (1%) + Pot. BPJS TK (2%) + Pot. PPh 21 (5%) + Pot. Absensi</i>", bullet_style))
    story.append(Paragraph("• Potongan Absensi = Jumlah Alpa × (Gaji Pokok / 22)", bullet_style))

    story.append(Paragraph("<b>Contoh Perhitungan Manual:</b>", heading2_style))
    story.append(Paragraph(
        "Karyawan Ahmad Rizki (Manager IT) memiliki Gaji Pokok Rp 8.500.000, Tunj. Jabatan Rp 2.500.000, Makan Rp 40.000/hari, Transport Rp 30.000/hari. "
        "Status Menikah dengan 2 Anak. Pada Bulan Juli 2026, hadir 22 hari, lembur 10 jam, alpa 0 hari.<br/>"
        "• Tunj. Kehadiran = 22 × (40.000 + 30.000) = Rp 1.540.000<br/>"
        "• Tunj. Keluarga = (10% × 8.500.000) + (10% × 8.500.000) = Rp 1.700.000<br/>"
        "• Bonus Lembur = 10 × (1.5 × (8.500.000 / 173)) = Rp 736.994,22<br/>"
        "• <b>Total Pendapatan</b> = 8.500.000 + 2.500.000 + 1.540.000 + 1.700.000 + 736.994,22 = <b>Rp 14.976.994,22</b><br/>"
        "• Potongan BPJS KS (1%) = Rp 85.000 | BPJS TK (2%) = Rp 170.000 | PPh 21 (5%) = Rp 425.000<br/>"
        "• <b>Total Potongan</b> = 85.000 + 170.000 + 425.000 = <b>Rp 680.000,00</b><br/>"
        "• <b>GAJI BERSIH</b> = 14.976.994,22 - 680.000,00 = <b>Rp 14.296.994,22</b>", body_style
    ))
    story.append(Spacer(1, 0.4*cm))

    # BAB III ANALISIS DAN PERANCANGAN SISTEM
    story.append(Paragraph("BAB III ANALISIS DAN PERANCANGAN SISTEM", heading1_style))
    
    story.append(Paragraph("3.1 Analisis Kebutuhan Sistem", heading2_style))
    story.append(Paragraph("<b>1. Kebutuhan Fungsional:</b>", body_style))
    story.append(Paragraph("• Verifikasi login & pembatasan hak akses (Admin vs Karyawan).", bullet_style))
    story.append(Paragraph("• CRUD lengkap untuk Data Jabatan dan Data Karyawan oleh Admin.", bullet_style))
    story.append(Paragraph("• Fitur portal mandiri Edit Profil Karyawan (update foto avatar, kontak, alamat, & password).", bullet_style))
    story.append(Paragraph("• Otomatisasi perhitungan gaji berbasis rekap presensi dan skema jabatan.", bullet_style))
    story.append(Paragraph("• Mobile-responsive antarmuka dengan Sidebar Offcanvas Drawer.", bullet_style))

    story.append(Paragraph("3.2 Perancangan Alur Sistem (Flowchart)", heading2_style))
    story.append(Paragraph(
        "Alur kerja utama komputasi gaji otomatis dan navigasi aplikasi dijelaskan melalui tahapan urut berikut:<br/>"
        "1. <b>Mulai & Login:</b> User menginput kredensial. AuthFilter memverifikasi role.<br/>"
        "2. <b>Input Master & Presensi:</b> Admin mengelola data Jabatan, Karyawan, dan merekap kehadiran per bulan.<br/>"
        "3. <b>Eksekusi Komputasi:</b> Admin menekan tombol <i>Hitung Gaji Otomatis</i> -> Controller `Penggajian` mengambil data Jabatan (Gaji Pokok, Tunjangan) dan Presensi (Hadir, Lembur, Alpa).<br/>"
        "4. <b>Kalkulasi Otomatis:</b> Sistem menghitung Tunjangan Kehadiran, Tunjangan Keluarga, Bonus Lembur, Potongan BPJS/PPh21/Absensi -> Gaji Bersih.<br/>"
        "5. <b>Output & Slip:</b> Data tersimpan di DB, status Lunas, dan Slip Gaji dapat diakses/dicetak oleh Admin maupun Karyawan.", body_style
    ))

    story.append(Paragraph("3.3 Perancangan Basis Data (ERD & Skema Fisik Tabel)", heading2_style))
    
    db_tables_data = [
        [Paragraph("<b>Nama Tabel</b>", table_header_style), Paragraph("<b>Primary Key</b>", table_header_style), Paragraph("<b>Atribut Utama & Relasi (Foreign Key)</b>", table_header_style)],
        [Paragraph("users", table_cell_center), Paragraph("id", table_cell_center), Paragraph("username, email, password, role ('admin'/'karyawan')", table_cell_style)],
        [Paragraph("jabatan", table_cell_center), Paragraph("id", table_cell_center), Paragraph("nama_jabatan, gaji_pokok, tunj_jabatan, tunj_makan_per_hari, tunj_transport_per_hari", table_cell_style)],
        [Paragraph("karyawan", table_cell_center), Paragraph("id", table_cell_center), Paragraph("user_id (FK users.id), nip, nama, jenis_kelamin, jabatan_id (FK jabatan.id), status_nikah, jumlah_anak, foto", table_cell_style)],
        [Paragraph("presensi", table_cell_center), Paragraph("id", table_cell_center), Paragraph("karyawan_id (FK karyawan.id), bulan, tahun, jumlah_hadir, jumlah_sakit, jumlah_izin, jumlah_alpa, jumlah_lembur_jam", table_cell_style)],
        [Paragraph("penggajian", table_cell_center), Paragraph("id", table_cell_center), Paragraph("kode_transaksi, karyawan_id (FK karyawan.id), bulan, tahun, total_pendapatan, total_potongan, gaji_bersih, status_bayar", table_cell_style)],
    ]
    t_db = Table(db_tables_data, colWidths=[2.5*cm, 2.5*cm, 9.0*cm])
    t_db.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#1e1b4b")),
        ('GRID', (0,0), (-1,-1), 0.5, colors.HexColor("#cbd5e1")),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('LEFTPADDING', (0,0), (-1,-1), 4),
        ('RIGHTPADDING', (0,0), (-1,-1), 4),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_db)
    story.append(Spacer(1, 0.4*cm))

    # BAB IV IMPLEMENTASI DAN PENGUJIAN
    story.append(Paragraph("BAB IV IMPLEMENTASI DAN PENGUJIAN", heading1_style))
    
    story.append(Paragraph("4.1 Lingkungan Implementasi", heading2_style))
    story.append(Paragraph("• Server Web / Environment: PHP 8.5+ & Laragon Localhost", bullet_style))
    story.append(Paragraph("• Framework Backend: CodeIgniter v4.7.4", bullet_style))
    story.append(Paragraph("• Database Engine: MySQL 9.7 (InnoDB)", bullet_style))
    story.append(Paragraph("• Frontend Framework: Bootstrap 5.3 Mobile-Ready & Chart.js Library", bullet_style))

    story.append(Paragraph("4.2 Snippet Kode Logika Komputasi Gaji", heading2_style))
    
    code_snippet = """// Kalkulasi Komponen Pendapatan & Potongan
$gajiPokok   = (float)$karyawan['gaji_pokok'];
$tunjJabatan = (float)$karyawan['tunj_jabatan'];

// Tunjangan Kehadiran
$tunjKehadiran = ($hadir * $makanPerHari) + ($hadir * $transportPerHari);

// Tunjangan Keluarga (10% Nikah + 5% per Anak Max 2)
$tunjKeluarga = 0.00;
if ($karyawan['status_nikah'] === 'Menikah') {
    $tunjKeluarga += 0.10 * $gajiPokok + (0.05 * $gajiPokok * min(2, $jumlahAnak));
}

// Bonus Lembur (Standard Depnakertrans 1/173)
$bonusLembur = $jamLembur * (1.5 * ($gajiPokok / 173.0));

// Total Pendapatan & Potongan Wajib
$totalPendapatan = $gajiPokok + $tunjJabatan + $tunjKehadiran + $tunjKeluarga + $bonusLembur;
$potBpjsKs  = 0.01 * $gajiPokok;
$potBpjsTk  = 0.02 * $gajiPokok;
$potPph21   = 0.05 * $gajiPokok;
$potAbsensi = $jumlahAlpa * ($gajiPokok / 22.0);

$totalPotongan = $potBpjsKs + $potBpjsTk + $potPph21 + $potAbsensi;
$gajiBersih    = $totalPendapatan - $totalPotongan;"""
    
    t_code = Table([[Paragraph(f"<font face='Courier' size=8>{code_snippet.replace('\n', '<br/>').replace(' ', '&nbsp;')}</font>", body_style)]], colWidths=[14.0*cm])
    t_code.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#f8fafc")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#cbd5e1")),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
        ('TOPPADDING', (0,0), (-1,-1), 6),
        ('BOTTOMPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_code)
    story.append(Spacer(1, 0.4*cm))

    story.append(Paragraph("4.3 Pengujian Sistem (Black Box Testing)", heading2_style))
    
    blackbox_data = [
        [Paragraph("<b>Fitur Skenario</b>", table_header_style), Paragraph("<b>Input / Aksi</b>", table_header_style), Paragraph("<b>Hasil Yang Diharapkan</b>", table_header_style), Paragraph("<b>Status</b>", table_header_style)],
        [Paragraph("Autentikasi Login", table_cell_style), Paragraph("Username & Password benar", table_cell_style), Paragraph("Masuk ke dashboard sesuai role", table_cell_style), Paragraph("PASSED", table_cell_center)],
        [Paragraph("Validasi Login", table_cell_style), Paragraph("Password salah / kosong", table_cell_style), Paragraph("Menampilkan pesan alert error", table_cell_style), Paragraph("PASSED", table_cell_center)],
        [Paragraph("CRUD Karyawan (Admin)", table_cell_style), Paragraph("Input data dengan/tanpa foto", table_cell_style), Paragraph("Tersimpan di DB tanpa error validasi", table_cell_style), Paragraph("PASSED", table_cell_center)],
        [Paragraph("Edit Profil (Karyawan)", table_cell_style), Paragraph("Update foto avatar & password", table_cell_style), Paragraph("Profil & password ter-update", table_cell_style), Paragraph("PASSED", table_cell_center)],
        [Paragraph("Komputasi Gaji", table_cell_style), Paragraph("Klik tombol Hitung Gaji", table_cell_style), Paragraph("Gaji bersih terhitung akurat 100%", table_cell_style), Paragraph("PASSED", table_cell_center)],
        [Paragraph("Upload Bukti Transfer", table_cell_style), Paragraph("Upload file transfer JPG/PDF", table_cell_style), Paragraph("Status berubah menjadi Lunas", table_cell_style), Paragraph("PASSED", table_cell_center)],
        [Paragraph("Cetak Slip Gaji", table_cell_style), Paragraph("Klik tombol Slip Gaji", table_cell_style), Paragraph("Tampil format cetak slip resmi", table_cell_style), Paragraph("PASSED", table_cell_center)],
    ]
    t_bb = Table(blackbox_data, colWidths=[3.0*cm, 3.5*cm, 5.5*cm, 2.0*cm])
    t_bb.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#1e1b4b")),
        ('GRID', (0,0), (-1,-1), 0.5, colors.HexColor("#cbd5e1")),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('LEFTPADDING', (0,0), (-1,-1), 4),
        ('RIGHTPADDING', (0,0), (-1,-1), 4),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_bb)
    story.append(Spacer(1, 0.4*cm))

    # BAB V PENUTUP
    story.append(Paragraph("BAB V PENUTUP", heading1_style))
    story.append(Paragraph("5.1 Kesimpulan", heading2_style))
    story.append(Paragraph(
        "Berdasarkan hasil perancangan, implementasi, dan pengujian sistem informasi penggajian karyawan berbasis CodeIgniter 4 oleh Tim B, "
        "dapat disimpulkan bahwa aplikasi SIPGAJI telah berhasil dibangun sesuai dengan spesifikasi tugas UAS PBL. "
        "Sistem tidak hanya menjalankan fungsi manajemen data (CRUD) secara komprehensif, tetapi juga berhasil mengotomatiskan "
        "perhitungan gaji bersih karyawan berbasis rumus matematis formal yang mencakup seluruh komponen tunjangan, bonus lembur, "
        "BPJS, PPh 21, dan potongan absensi dengan akurasi 100%, lengkap dengan portal mandiri edit profil karyawan dan antarmuka mobile-responsive.", body_style
    ))

    story.append(Paragraph("5.2 Saran", heading2_style))
    story.append(Paragraph(
        "Untuk pengembangan sistem di masa mendatang, disarankan penambahan integrasi gateway pembayaran otomatis (Payment Gateway API) "
        "serta modul absensi berbasis Geolocation/QR Code real-time.", body_style
    ))
    story.append(Spacer(1, 0.4*cm))

    # DAFTAR PUSTAKA
    story.append(Paragraph("DAFTAR PUSTAKA", heading1_style))
    story.append(Paragraph("CodeIgniter Foundation. (2026). <i>CodeIgniter 4 User Guide (Version 4.7)</i>. Retrieved from https://codeigniter.com/user_guide/", bullet_style))
    story.append(Paragraph("Pressman, R. S., & Maxim, B. R. (2020). <i>Software Engineering: A Practitioner's Approach</i> (9th ed.). McGraw-Hill Education.", bullet_style))
    story.append(Paragraph("Suwanda, R. (2026). <i>Modul Instruksi Praktikum Pemrograman Berbasis Web Lanjutan</i>. Universitas Malikussaleh.", bullet_style))
    story.append(Spacer(1, 0.4*cm))

    # LAMPIRAN
    story.append(Paragraph("LAMPIRAN", heading1_style))
    
    story.append(Paragraph("Lampiran 1. Dataset / Data Uji (Mock Data 15 Karyawan Utama)", heading2_style))
    
    mock_headers = [Paragraph("<b>NIP</b>", table_header_style), Paragraph("<b>Nama Karyawan</b>", table_header_style), Paragraph("<b>Jabatan</b>", table_header_style), Paragraph("<b>Gaji Bersih</b>", table_header_style)]
    mock_rows = [mock_headers]
    
    mock_list = [
        ("NIP2026001", "Ahmad Rizki", "Manager IT", "14.296.994"),
        ("NIP2026002", "Budi Santoso", "Senior Software Engineer", "11.196.445"),
        ("NIP2026003", "Citra Dewi", "HRD & Legal Staff", "7.771.503"),
        ("NIP2026004", "Dedi Kurniawan", "Financial Analyst", "10.457.803"),
        ("NIP2026005", "Eka Putri", "Marketing & Sales Exec", "6.223.636"),
        ("NIP2026006", "Fajar Pratama", "Manager IT", "15.340.994"),
        ("NIP2026007", "Gita Gutawa", "Senior Software Engineer", "9.791.445"),
        ("NIP2026008", "Hendra Wijaya", "HRD & Legal Staff", "8.571.503"),
        ("NIP2026009", "Indah Permata", "Financial Analyst", "9.525.000"),
        ("NIP2026010", "Joko Susilo", "Marketing & Sales Exec", "5.663.636"),
        ("NIP2026011", "Kiki Amalia", "Senior Software Engineer", "10.491.445"),
        ("NIP2026012", "Lukman Hakim", "HRD & Legal Staff", "9.071.503"),
        ("NIP2026013", "Maya Sari", "Financial Analyst", "8.575.000"),
        ("NIP2026014", "Naufal Alamsyah", "Marketing & Sales Exec", "7.648.636"),
        ("NIP2026015", "Oki Setiana", "Senior Software Engineer", "9.791.445"),
    ]

    for item in mock_list:
        mock_rows.append([
            Paragraph(item[0], table_cell_center),
            Paragraph(item[1], table_cell_style),
            Paragraph(item[2], table_cell_style),
            Paragraph("Rp " + item[3], table_cell_style),
        ])

    t_mock = Table(mock_rows, colWidths=[3.0*cm, 4.5*cm, 4.5*cm, 2.0*cm])
    t_mock.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#1e1b4b")),
        ('GRID', (0,0), (-1,-1), 0.5, colors.HexColor("#cbd5e1")),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('LEFTPADDING', (0,0), (-1,-1), 4),
        ('RIGHTPADDING', (0,0), (-1,-1), 4),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
    ]))
    story.append(t_mock)
    story.append(Spacer(1, 0.4*cm))

    story.append(Paragraph("Lampiran 2. Logbook dan Dokumentasi Kegiatan Harian Tim B", heading2_style))
    
    logbook_headers = [Paragraph("<b>No</b>", table_header_style), Paragraph("<b>Tanggal</b>", table_header_style), Paragraph("<b>Deskripsi Kegiatan / Pekerjaan</b>", table_header_style), Paragraph("<b>Penanggung Jawab</b>", table_header_style)]
    logbook_rows = [logbook_headers]
    
    logbook_list = [
        ("1", "20 Juli 2026", "Diskusi penentuan topik proyek penggajian otomatis dan pembagian tugas anggota kelompok Tim B.", "RAHMI SAHARA"),
        ("2", "22 Juli 2026", "Merancang skema ERD basis data MySQL (tabel users, jabatan, karyawan, presensi, penggajian).", "NICOIWAN ADHA KOBAT"),
        ("3", "25 Juli 2026", "Membangun struktur framework CI4, migration, seeder mock data 15 baris, dan AuthFilter RBAC.", "AZKAL AZKIYA"),
        ("4", "28 Juli 2026", "Pengembangan Controller Penggajian, perumusan algoritma kalkulasi gaji otomatis, dan upload file.", "RAHMI SAHARA"),
        ("5", "30 Juli 2026", "Desain antarmuka Mobile-Ready Bootstrap 5, Chart.js dashboard, dan layout cetak slip gaji karyawan.", "ZAHRA"),
        ("6", "02 Agustus 2026", "Pengujian Black Box Testing, perbaikan upload foto admin, modul Edit Profil, dan finalisasi Laporan PBL.", "Tim B"),
    ]

    for item in logbook_list:
        logbook_rows.append([
            Paragraph(item[0], table_cell_center),
            Paragraph(item[1], table_cell_style),
            Paragraph(item[2], table_cell_style),
            Paragraph(item[3], table_cell_style),
        ])

    t_log = Table(logbook_rows, colWidths=[1.0*cm, 3.0*cm, 7.0*cm, 3.0*cm])
    t_log.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#1e1b4b")),
        ('GRID', (0,0), (-1,-1), 0.5, colors.HexColor("#cbd5e1")),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('LEFTPADDING', (0,0), (-1,-1), 4),
        ('RIGHTPADDING', (0,0), (-1,-1), 4),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_log)
    story.append(Spacer(1, 0.4*cm))

    story.append(Paragraph("Lampiran 3. Panduan Singkat Penggunaan & Default Credentials", heading2_style))
    story.append(Paragraph("<b>Cara Menjalankan Aplikasi di Localhost:</b>", body_style))
    story.append(Paragraph("1. Import file database <code>sipgaji.sql</code> ke dalam MySQL melalui phpMyAdmin / Command Line.", bullet_style))
    story.append(Paragraph("2. Pastikan file <code>.env</code> sudah terkonfigurasi dengan nama database <code>sipgaji</code> dan username <code>root</code>.", bullet_style))
    story.append(Paragraph("3. Jalankan perintah <code>php spark serve</code> pada terminal root proyek.", bullet_style))
    story.append(Paragraph("4. Buka peramban (browser) dan akses URL <code>http://localhost:8080</code>.", bullet_style))
    
    story.append(Paragraph("<b>Daftar Kredensial Login Default:</b>", body_style))
    story.append(Paragraph("• <b>Akun Administrator:</b> Username: <code>admin</code> | Password: <code>password123</code>", bullet_style))
    story.append(Paragraph("• <b>Akun Karyawan:</b> Username: <code>karyawan1</code> s/d <code>karyawan15</code> | Password: <code>password123</code>", bullet_style))

    doc.build(story)
    print("PDF Successfully regenerated at:", OUTPUT_PDF)

if __name__ == "__main__":
    build_pdf()
