-- db name = sistem_informasi_pendaftaran

CREATE TABLE program_studi (
	kode CHAR(8) PRIMARY KEY,
	nama VARCHAR(255),
    jenjang VARCHAR(255),
    daya_tampung INT
);

CREATE TABLE mahasiswa (
    nisn CHAR(10) PRIMARY KEY,
    tahun_lulus YEAR,
    nama VARCHAR(255),
    no_hp VARCHAR(20),
    tanggal_lahir DATE,
    prodi_1_kode CHAR(8),
    prodi_2_kode CHAR(8),
    jalur_pendaftaran ENUM('Mandiri', 'SNBT', 'SNBP'),
    skor_ujian DECIMAL(5, 2),
    password VARCHAR(255),
    bukti_pembayaran TEXT,
    status_kelulusan ENUM("Lulus Pilihan 1", "Lulus Pilihan 2", "Tidak Lulus"),
    foto_profil TEXT,
    FOREIGN KEY (prodi_1_kode) REFERENCES program_studi(kode) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (prodi_2_kode) REFERENCES program_studi(kode) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE admins (
	id INT PRIMARY KEY AUTO_INCREMENT,
	nama VARCHAR(255) NOT NULL,
	password VARCHAR(255),
	UNIQUE (nama)
);

