-- SQL schema for PadelPro

-- Tabel pengguna menyimpan data semua user dengan berbagai role
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_lengkap` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `no_telepon` VARCHAR(50) DEFAULT NULL,
  `role` ENUM('pelanggan','kasir','admin_keuangan','owner') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel courts menyimpan master data lapangan
CREATE TABLE IF NOT EXISTS `courts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_lapangan` VARCHAR(255) NOT NULL,
  `harga_per_jam` DECIMAL(10,2) NOT NULL,
  `status` ENUM('tersedia','perbaikan') DEFAULT 'tersedia',
  `foto_lapangan` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel produk F&B
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_produk` VARCHAR(255) NOT NULL,
  `harga_jual` DECIMAL(10,2) NOT NULL,
  `stok` INT NOT NULL DEFAULT 0,
  `kategori` VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel bookings menyimpan reservasi lapangan
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_user` INT NOT NULL,
  `id_court` INT NOT NULL,
  `tanggal_booking` DATE NOT NULL,
  `jam_mulai` TIME NOT NULL,
  `jam_selesai` TIME NOT NULL,
  `durasi` INT NOT NULL,
  `total_harga` DECIMAL(10,2) NOT NULL,
  `uang_muka` DECIMAL(10,2) DEFAULT 0,
  `sisa_pembayaran` DECIMAL(10,2) DEFAULT 0,
  `status_pembayaran` ENUM('dp','lunas','belum_bayar') DEFAULT 'belum_bayar',
  `status_booking` ENUM('pending','confirmed','selesai','batal') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_bookings_user` FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bookings_court` FOREIGN KEY (`id_court`) REFERENCES `courts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel sales menyimpan transaksi penjualan F&B
CREATE TABLE IF NOT EXISTS `sales` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_kasir` INT NOT NULL,
  `nomor_nota` VARCHAR(100) NOT NULL UNIQUE,
  `total_belanja` DECIMAL(10,2) NOT NULL,
  `tanggal_transaksi` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_sales_kasir` FOREIGN KEY (`id_kasir`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel sale_details menyimpan detail item yang dibeli
CREATE TABLE IF NOT EXISTS `sale_details` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_sale` INT NOT NULL,
  `id_product` INT NOT NULL,
  `jumlah` INT NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  CONSTRAINT `fk_sale_details_sale` FOREIGN KEY (`id_sale`) REFERENCES `sales`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_sale_details_product` FOREIGN KEY (`id_product`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel payments mencatat pembayaran dari booking atau penjualan
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_booking` INT DEFAULT NULL,
  `id_sale` INT DEFAULT NULL,
  `jumlah_bayar` DECIMAL(10,2) NOT NULL,
  `metode_pembayaran` ENUM('tunai','transfer') NOT NULL,
  `id_kasir` INT NOT NULL,
  `tanggal_bayar` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_payments_booking` FOREIGN KEY (`id_booking`) REFERENCES `bookings`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_payments_sale` FOREIGN KEY (`id_sale`) REFERENCES `sales`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_payments_kasir` FOREIGN KEY (`id_kasir`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;