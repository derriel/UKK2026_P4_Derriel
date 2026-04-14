-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table db_p4derriel.authors: ~0 rows (approximately)
INSERT INTO `authors` (`id`, `name`, `email`, `birth_date`, `nationality`, `biography`, `created_at`, `updated_at`) VALUES
	(1, 'Farid Gaban', 'faridgaban@gmail.com', '1999-04-05', 'Indonesia', 'Jurnalis senior Indonesia, pernah menjadi redaktur di media nasional seperti Republika. Ia dikenal luas melalui ekspedisi jurnalistik seperti Ekspedisi Zamrud Khatulistiwa (2009–2010) dan karya buku tentang lingkungan serta kelautan. Ia juga pernah meliput konflik internasional seperti perang Bosnia.', '2026-04-13 22:13:10', '2026-04-13 22:13:10');

-- Dumping data for table db_p4derriel.author_book: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.books: ~1 rows (approximately)
INSERT INTO `books` (`id`, `title`, `author_id`, `isbn`, `stock`, `description`, `category`, `publication_year`, `created_at`, `updated_at`, `publisher_id`, `category_id`, `cover_image`) VALUES
	(6, 'Reset Indonesia', 1, '978-623-6063-74-3', 5, 'Buku Reset Indonesia: Gagasan tentang Indonesia Baru merupakan karya nonfiksi yang ditulis oleh empat jurnalis lintas generasi berdasarkan pengalaman mereka melakukan ekspedisi ke berbagai wilayah di Indonesia. Buku ini mengangkat kondisi nyata masyarakat dari berbagai daerah, mulai dari persoalan lingkungan, pendidikan, ketimpangan sosial, hingga kualitas demokrasi.', 'Sosial & Politik', NULL, '2026-04-13 22:23:38', '2026-04-13 22:39:44', 1, 1, 'books/vtawWlhCGcXbWkOYolUvlpwbsLl5pAS3RNQWV5pV.jpg');

-- Dumping data for table db_p4derriel.borrowings: ~0 rows (approximately)
INSERT INTO `borrowings` (`id`, `user_id`, `book_id`, `role_id`, `borrow_date`, `due_date`, `return_date`, `returned_at`, `status`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 1, 6, 3, '2026-04-14', '2026-04-21', NULL, '2026-04-13 23:20:32', 'returned', 'Pengajuan pengembalian disetujui oleh petugas/admin.', '2026-04-13 22:35:05', '2026-04-13 23:20:32'),
	(2, 1, 6, 3, '2026-04-14', '2026-04-21', NULL, NULL, 'borrowed', 'Pengajuan peminjaman disetujui oleh petugas/admin.', '2026-04-13 23:20:45', '2026-04-13 23:26:04');

-- Dumping data for table db_p4derriel.cache: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.cache_locks: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.categories: ~0 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Sosial & Politik', '2026-04-13 22:39:33', '2026-04-13 22:39:33');

-- Dumping data for table db_p4derriel.class_rooms: ~3 rows (approximately)
INSERT INTO `class_rooms` (`id`, `name`, `grade`, `jurusan`, `description`, `capacity`, `created_at`, `updated_at`) VALUES
	(1, 'Kelas 10', '10', '', 'Siswa kelas 10', 30, '2026-04-13 19:45:58', '2026-04-13 19:45:58'),
	(2, 'Kelas 11', '11', '', 'Siswa kelas 11', 28, '2026-04-13 19:45:58', '2026-04-13 19:45:58'),
	(3, 'Kelas 12', '12', '', 'Siswa kelas 12', 25, '2026-04-13 19:45:58', '2026-04-13 19:45:58');

-- Dumping data for table db_p4derriel.failed_jobs: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.jobs: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.job_batches: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.migrations: ~25 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(4, '0001_01_01_000000_create_users_table', 1),
	(5, '0001_01_01_000001_create_cache_table', 1),
	(6, '0001_01_01_000002_create_jobs_table', 1),
	(7, '2026_04_08_171754_create_roles_table', 2),
	(8, '2026_04_08_171747_add_role_id_to_users_table', 3),
	(9, '2026_04_08_171806_create_books_table', 4),
	(10, '2026_04_08_171826_create_members_table', 5),
	(11, '2026_04_08_171759_create_borrowings_table', 6),
	(12, '2026_04_08_172809_add_returned_at_to_borrowings_table', 7),
	(13, '2026_04_10_000000_add_identity_number_to_members_table', 8),
	(16, '2026_04_10_061424_add_cover_image_to_books_table', 9),
	(17, '2026_04_13_072032_add_photo_to_members_table', 10),
	(18, '2026_04_14_015802_add_last_seen_to_users_table', 10),
	(19, '2026_04_14_023158_create_class_rooms_table', 11),
	(20, '2026_04_14_023848_add_class_room_id_to_members_table', 11),
	(21, '2026_04_14_030329_add_jurusan_to_class_rooms_table', 12),
	(22, '2026_04_14_030333_rename_members_table_to_siswa', 12),
	(23, '2026_04_14_030347_update_foreign_key_constraint_for_siswa_table', 13),
	(24, '2026_04_14_044747_drop_photo_from_siswa_table', 14),
	(25, '2026_04_14_235959_add_logo_to_publishers_table', 15),
	(26, '2026_04_14_235959_add_author_and_publisher_id_to_books_table', 16),
	(27, '2026_04_15_000000_make_book_author_nullable', 17),
	(28, '2026_04_15_000001_drop_old_author_publisher_columns_from_books_table', 18),
	(29, '2026_04_15_000002_create_categories_table', 19),
	(30, '2026_04_14_235959_add_request_statuses_to_borrowings_table', 20),
	(31, '2026_04_14_140000_create_racks_table', 21);

-- Dumping data for table db_p4derriel.password_reset_tokens: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.publishers: ~0 rows (approximately)
INSERT INTO `publishers` (`id`, `name`, `city`, `phone`, `logo`, `created_at`, `updated_at`) VALUES
	(1, 'Gramedia', 'Bandung', '(021) 53650110', 'publishers/IlXV6t6WF3oILsu1Jhf3jUquv63aQTJrSnZ2k1nt.jpg', '2026-04-13 22:10:21', '2026-04-13 22:10:21');

-- Dumping data for table db_p4derriel.racks: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.roles: ~3 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'Administrator with full access', NULL, NULL),
	(2, 'petugas', 'Petugas with book management access', NULL, NULL),
	(3, 'member', 'Regular member with borrowing access', NULL, NULL);

-- Dumping data for table db_p4derriel.sessions: ~3 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('1mZPigfMnRepN7EOQo3g7gIVWYWjihv85COeZvWO', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVWtsYW92dkwzVzVXbjlXR1o4Vm5WblMyVXZVVXp2NG1LWmhOMlJLRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQtcGV0dWdhcyI7fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1776149184),
	('e2UyIYzzK5PqeMd4gsUaSW3hI58SkJULL1x9x2HV', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGdUR0x2SHJLSTB2TGY5bU5vbjltZjgzR2dWMnl3RUZHbmo4bXNONyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib29rcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==', 1776149105),
	('PSLe6CrOEbH4XXUJdSPr445gkn5bvb3pWnt0e34B', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNU0zZFFnZ0VGN1lOendsZVNQd0ZNa2xndnNRZGFwMWtZUnVzN0RUNyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1776151326);

-- Dumping data for table db_p4derriel.siswa: ~5 rows (approximately)
INSERT INTO `siswa` (`id`, `id_siswa`, `nis`, `name`, `email`, `phone`, `address`, `kelas`, `jurusan`, `birth_date`, `gender`, `join_date`, `status`, `created_at`, `updated_at`, `class_room_id`) VALUES
	(7, 'SIS001', '3273081203000001', 'Ahmad Rahman', 'ahmad@example.com', '081234567890', 'Jl. Sudirman No. 123, Jakarta', 'XII', 'RPL', '1995-05-15', 'male', '2024-01-15', 'active', NULL, NULL, NULL),
	(8, 'SIS002', '3275022404000002', 'Siti Nurhaliza', 'siti@example.com', '081987654321', 'Jl. Thamrin No. 456, Jakarta', 'XI', 'TKJ', '1998-08-22', 'female', '2024-02-10', 'active', NULL, NULL, NULL),
	(9, 'SIS003', '3272051705000003', 'Budi Santoso', 'budi@example.com', '081345678901', 'Jl. Gatot Subroto No. 789, Jakarta', 'X', 'MM', '2000-12-05', 'male', '2024-03-01', 'active', NULL, NULL, NULL),
	(10, 'SIS004', '3271061806000004', 'Maya Sari', 'maya@example.com', '081456789012', 'Jl. MH Thamrin No. 321, Jakarta', 'XII', 'AK', '1997-11-18', 'female', '2024-01-20', 'active', NULL, NULL, NULL),
	(11, 'SIS005', '3274073007000005', 'Rudi Hartono', 'rudi@example.com', '081567890123', 'Jl. Jendral Sudirman No. 654, Jakarta', 'XI', 'RPL', '1990-07-30', 'male', '2024-02-28', 'inactive', NULL, NULL, NULL);

-- Dumping data for table db_p4derriel.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `photo`, `created_at`, `updated_at`, `role_id`, `last_seen`) VALUES
	(1, 'Admin User', 'admin@perpustakaan.com', '2026-04-08 10:24:15', '$2y$12$4Vy1GVHcVfhjmruNaT2au.2vpLtyIcKbvvYaWBm90KdAGeQRFsgV2', 'LiJOVCz5I483rVwepo0PA6TNClSZNbSPBhESkeRzSOd7Xc9MrjtFq6ThuTdV', NULL, '2026-04-08 10:24:16', '2026-04-14 00:21:00', 3, '2026-04-14 00:21:00'),
	(2, 'Petugas', 'petugas@perpustakaan.com', '2026-04-08 10:24:17', '$2y$12$jgBMAAAYZDwiCadjDKdaQOV4y7i/04v3qujU2nwHA52ukdAxS6tSS', '8t945jfS9Znz8KDSR1FufdSDfRs7mjdvYegdGMvVqJQZTZVhqNBpMIEujxqM', NULL, '2026-04-08 10:24:17', '2026-04-13 23:46:24', 2, '2026-04-13 23:46:24'),
	(4, 'admin', 'admin@ukk2026.com', NULL, '$2y$12$15.Vo9mg/7Lbd6nYCo83a.jWMNhRrnKotSEwXDbr5RdFBZ9dlV8sm', NULL, NULL, NULL, '2026-04-13 23:45:05', 1, '2026-04-13 23:45:05');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
