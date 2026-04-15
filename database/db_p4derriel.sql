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
INSERT INTO `authors` (`id`, `name`, `email`, `birth_date`, `nationality`, `biography`, `bio`, `photo`, `created_at`, `updated_at`) VALUES
	(1, 'Farid Gaban', 'faridgaban@gmail.com', '1997-09-08', 'Indonesia', 'Jurnalis senior Indonesia, pernah menjadi redaktur di media nasional seperti Republika. Ia dikenal luas melalui ekspedisi jurnalistik seperti Ekspedisi Zamrud Khatulistiwa (2009–2010) dan karya buku tentang lingkungan serta kelautan.', NULL, NULL, '2026-04-14 21:42:12', '2026-04-14 21:42:12');

-- Dumping data for table db_p4derriel.books: ~0 rows (approximately)
INSERT INTO `books` (`id`, `title`, `isbn`, `stock`, `description`, `publication_year`, `created_at`, `updated_at`, `cover_image`, `author_id`, `publisher_id`, `category_id`, `fine_per_day`, `is_active`) VALUES
	(1, 'Reset Indonesia', '978-623-6063-74-3', 4, 'Buku Reset Indonesia: Gagasan tentang Indonesia Baru merupakan karya nonfiksi yang ditulis oleh empat jurnalis lintas generasi berdasarkan pengalaman mereka melakukan ekspedisi ke berbagai wilayah di Indonesia. Buku ini mengangkat kondisi nyata masyarakat dari berbagai daerah, mulai dari persoalan lingkungan, pendidikan, ketimpangan sosial, hingga kualitas demokrasi.', '2019', '2026-04-14 23:01:11', '2026-04-14 23:55:10', 'books/ue7KiFOW4RyXz82mk3NY2YQnFmTQOtzfblKN169k.jpg', 1, 1, 1, 1000, 1);

-- Dumping data for table db_p4derriel.borrowings: ~0 rows (approximately)
INSERT INTO `borrowings` (`id`, `user_id`, `book_id`, `role_id`, `borrow_date`, `due_date`, `return_date`, `returned_at`, `status`, `notes`, `fine`, `fine_status`, `paid_at`, `created_at`, `updated_at`) VALUES
	(1, 2, 1, 3, '2026-04-15', '2026-04-16', NULL, NULL, 'borrowed', 'Pengajuan peminjaman disetujui oleh petugas/admin.', 0.00, 'unpaid', NULL, '2026-04-14 23:11:21', '2026-04-14 23:55:10');

-- Dumping data for table db_p4derriel.cache: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.cache_locks: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.categories: ~0 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'Sosial & Politik', NULL, '2026-04-14 21:49:41', '2026-04-14 21:49:41');

-- Dumping data for table db_p4derriel.class_rooms: ~3 rows (approximately)
INSERT INTO `class_rooms` (`id`, `name`, `grade`, `jurusan`, `description`, `capacity`, `created_at`, `updated_at`) VALUES
	(1, 'Kelas 10', '10', 'Teknik Komputer dan Jaringan', 'Siswa kelas 10', 30, '2026-04-14 21:38:31', '2026-04-14 21:38:31'),
	(2, 'Kelas 11', '11', 'Teknik Komputer dan Jaringan', 'Siswa kelas 11', 28, '2026-04-14 21:38:31', '2026-04-14 21:38:31'),
	(3, 'Kelas 12', '12', 'Teknik Komputer dan Jaringan', 'Siswa kelas 12', 25, '2026-04-14 21:38:31', '2026-04-14 21:38:31');

-- Dumping data for table db_p4derriel.failed_jobs: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.fines: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.jobs: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.job_batches: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.migrations: ~11 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_04_08_171754_create_roles_table', 1),
	(5, '2026_04_08_171800_add_role_id_to_users_table', 1),
	(6, '2026_04_08_171807_create_books_table', 1),
	(7, '2026_04_08_171810_create_borrowings_table', 1),
	(8, '2026_04_08_171830_create_members_table', 1),
	(9, '2026_04_08_172809_add_returned_at_to_borrowings_table', 1),
	(10, '2026_04_10_000000_add_identity_number_to_members_table', 1),
	(11, '2026_04_10_061424_add_cover_image_to_books_table', 1),
	(12, '2026_04_13_072032_add_photo_to_members_table', 1),
	(13, '2026_04_14_015802_add_last_seen_to_users_table', 1),
	(14, '2026_04_14_023158_create_class_rooms_table', 1),
	(15, '2026_04_14_023848_add_class_room_id_to_members_table', 1),
	(16, '2026_04_14_030329_add_jurusan_to_class_rooms_table', 1),
	(17, '2026_04_14_030333_rename_members_table_to_siswa', 1),
	(18, '2026_04_14_030347_update_foreign_key_constraint_for_siswa_table', 1),
	(19, '2026_04_14_044747_drop_photo_from_siswa_table', 1),
	(20, '2026_04_14_140000_create_racks_table', 1),
	(21, '2026_04_14_235959_add_request_statuses_to_borrowings_table', 1),
	(22, '2026_04_15_000001_add_fine_to_borrowings_table', 1),
	(23, '2026_04_16_000001_create_categories_table', 1),
	(24, '2026_04_16_000002_create_authors_table', 1),
	(25, '2026_04_16_000003_create_publishers_table', 1),
	(26, '2026_04_16_000004_create_fines_table', 1),
	(27, '2026_04_16_000005_add_relations_to_books_table', 1),
	(28, '2026_04_15_044814_add_logo_to_publishers_table', 2);

-- Dumping data for table db_p4derriel.password_reset_tokens: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.publishers: ~0 rows (approximately)
INSERT INTO `publishers` (`id`, `name`, `city`, `address`, `phone`, `logo`, `email`, `created_at`, `updated_at`) VALUES
	(1, 'Gramedia', 'Jakarta', NULL, '+62 811-1450-436', 'publishers/E2AviYKnuvghPWnnvUblXR4MSL5NN0rQKNsb1qH6.jpg', NULL, '2026-04-14 21:48:43', '2026-04-14 21:48:43');

-- Dumping data for table db_p4derriel.racks: ~0 rows (approximately)
INSERT INTO `racks` (`id`, `name`, `location`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'Rak 01', NULL, 'blabla', '2026-04-14 22:59:26', '2026-04-14 22:59:26');

-- Dumping data for table db_p4derriel.roles: ~3 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'Administrator with full access', NULL, NULL),
	(2, 'petugas', 'Librarian with book management access', NULL, NULL),
	(3, 'pengguna', 'Regular member with borrowing access', NULL, NULL);

-- Dumping data for table db_p4derriel.sessions: ~1 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('MYoym05nVHybk7elWaUwPAte54Yo2fPbLv8Jegl6', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYXUwaU9PcVI3ZTR5YTJ2cTZ5RThGaVp2bWU3eGp6TWl5cFV5Mk5tQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZXBvcnRzL2ZpbHRlcj9tb250aD0wNCZ5ZWFyPTIwMjYiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1776237496),
	('tKeK7tsRZctzQF6QznukRNhzyqQTI5PlitrNvUaT', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidE0zWTcySGhEblBuSEhOMEVWTWtaY3NQcnYwcFNoeGRtZ3diaTNhQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC93ZWxjb21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1776237063),
	('W4DFvdLAikMyPIIHS0GWjqzxDP87nM42wtKl0CkG', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ3c2dFp2OTJFekFkUUM4dWxRWEZMaVQwOGpZUWV6eGZaMENWS2pFOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZXBvcnRzL2V4cG9ydD9tb250aD0wNCZ5ZWFyPTIwMjYiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1776237479);

-- Dumping data for table db_p4derriel.siswa: ~0 rows (approximately)

-- Dumping data for table db_p4derriel.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `last_seen`) VALUES
	(1, 'Admin Perpustakaan', 'admin@ukk2026.com', NULL, '$2y$12$RqqjaCSwRLVEI/HhR/rPzOvJ5saq25DgFqYTLZD8WEASNp6Opb1uW', NULL, '2026-04-14 21:39:40', '2026-04-15 00:17:59', 1, '2026-04-15 00:17:59'),
	(2, 'Pengguna Perpustakaan', 'pengguna@ukk2026.com', NULL, '$2y$12$EDj1vvaEuULmL0wH0cloI.HctPpxvopSjKDewdoGZhFjyiDidSUHK', NULL, '2026-04-14 21:40:45', '2026-04-15 00:11:03', 3, '2026-04-15 00:11:03'),
	(3, 'Petugas Perpustakaan', 'petugas@perpustakaan.com', NULL, '$2y$12$ypl2rd0tWUyl6CpEGZoZKupVa0P10qtfmiF7jcd8AGnLPHl2WjrBO', NULL, '2026-04-14 23:02:27', '2026-04-15 00:18:16', 2, '2026-04-15 00:18:16');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
