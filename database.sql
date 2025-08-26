
CREATE DATABASE IF NOT EXISTS kresnog2_padel_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kresnog2_padel_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_lengkap VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  no_telepon VARCHAR(20),
  role ENUM('pelanggan','kasir','admin_keuangan','owner') NOT NULL DEFAULT 'pelanggan',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE courts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_lapangan VARCHAR(100) NOT NULL,
  harga_per_jam DECIMAL(10,2) NOT NULL,
  status ENUM('tersedia','maintenance') NOT NULL DEFAULT 'tersedia',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_court INT NOT NULL,
  tanggal_booking DATE NOT NULL,
  jam_mulai TIME NOT NULL,
  jam_selesai TIME NOT NULL,
  durasi INT NOT NULL,
  total_harga DECIMAL(10,2) NOT NULL,
  status_booking ENUM('pending','confirmed','batal','selesai') DEFAULT 'pending',
  keterangan TEXT,
  status_pembayaran ENUM('belum_bayar','lunas') DEFAULT 'belum_bayar',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES users(id),
  FOREIGN KEY (id_court) REFERENCES courts(id)
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_produk VARCHAR(100) NOT NULL,
  harga_jual DECIMAL(10,2) NOT NULL,
  stok INT NOT NULL,
  kategori VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_kasir INT NOT NULL,
  nomor_nota VARCHAR(50) NOT NULL UNIQUE,
  total_belanja DECIMAL(10,2) NOT NULL,
  tanggal_transaksi DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_kasir) REFERENCES users(id)
);

CREATE TABLE sale_details (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_sale INT NOT NULL,
  id_product INT NOT NULL,
  jumlah INT NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (id_sale) REFERENCES sales(id),
  FOREIGN KEY (id_product) REFERENCES products(id)
);

CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_booking INT DEFAULT NULL,
  id_sale INT DEFAULT NULL,
  jumlah_bayar DECIMAL(10,2) NOT NULL,
  metode_pembayaran VARCHAR(50) NOT NULL,
  id_kasir INT NOT NULL,
  tanggal_pembayaran DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_booking) REFERENCES bookings(id),
  FOREIGN KEY (id_sale) REFERENCES sales(id),
  FOREIGN KEY (id_kasir) REFERENCES users(id)
);

CREATE TABLE cash_transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATETIME NOT NULL,
  type ENUM('in','out') NOT NULL,
  category ENUM('BON OPERASIONAL','BON TRANSFER BANK','DEBIT CREDIT CARD','MODAL') NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  note TEXT
);

CREATE TABLE store_status (
  id INT AUTO_INCREMENT PRIMARY KEY,
  store_date DATE NOT NULL,
  is_open TINYINT(1) NOT NULL DEFAULT 1,
  closed_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE member_data (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  kode_member CHAR(10) NOT NULL,
  alamat VARCHAR(255) DEFAULT NULL,
  kecamatan VARCHAR(100) DEFAULT NULL,
  kota VARCHAR(100) DEFAULT NULL,
  provinsi VARCHAR(100) DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
