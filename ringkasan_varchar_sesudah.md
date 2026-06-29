# Ringkasan VARCHAR Sesudah Perubahan — Skripsi Absensi

---

## Tabel `users`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `name` | VARCHAR | **100** | Nama user |
| `email` | VARCHAR | **100** | Email login |
| `email_verified_at` | TIMESTAMP | — | — |
| `password` | VARCHAR | 255 | Bcrypt hash |
| `remember_token` | VARCHAR | 100 | Auto oleh Laravel |
| `role` | ENUM | — | super_admin / wali_kelas / orang_tua |
| `is_approved` | BOOLEAN | — | Status persetujuan |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Tabel `password_reset_tokens`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `email` | VARCHAR | **100** | Primary Key |
| `token` | VARCHAR | **100** | Token reset password |
| `created_at` | TIMESTAMP | — | — |

---

## Tabel `sessions`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | VARCHAR | 255 | Session ID (tetap, bisa panjang) |
| `user_id` | BIGINT | — | FK ke users |
| `ip_address` | VARCHAR | 45 | IPv4/IPv6 |
| `user_agent` | TEXT | — | — |
| `payload` | LONGTEXT | — | — |
| `last_activity` | INTEGER | — | — |

---

## Tabel `personal_access_tokens`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `tokenable_type` | VARCHAR | — | Morph |
| `tokenable_id` | BIGINT | — | Morph |
| `name` | TEXT | — | Nama token |
| `token` | VARCHAR | **64** | Token hash (sudah sesuai) |
| `abilities` | TEXT | — | — |
| `last_used_at` / `expires_at` | TIMESTAMP | — | — |

---

## Tabel `classes`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `name` | VARCHAR | **20** | Contoh: X RPL 1 |
| `grade` | VARCHAR | **5** | Contoh: X, XI, XII |
| `major` | VARCHAR | **100** | Contoh: RPL, TKJ, IPA |
| `description` | TEXT | — | Deskripsi kelas |
| `status` | ENUM | — | active / inactive |
| `dismissal_time` | TIME | — | Jam pulang |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Tabel `students`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `nisn` | VARCHAR | **10** | Nomor Induk Siswa Nasional |
| `nis` | VARCHAR | **20** | Nomor Induk Siswa (nullable) |
| `name` | VARCHAR | **100** | Nama lengkap siswa |
| `email` | VARCHAR | **100** | Email siswa (nullable) |
| `gender` | ENUM | — | Laki-laki / Perempuan |
| `class_id` | BIGINT | — | FK ke classes |
| `photo` | VARCHAR | **150** | Path/nama file foto |
| `barcode_data` | VARCHAR | **50** | Data unik barcode/QR |
| `phone_number` | VARCHAR | **20** | Nomor HP siswa (nullable) |
| `address` | VARCHAR | **300** | Alamat lengkap (nullable) |
| `birth_place` | VARCHAR | **100** | Tempat lahir (nullable) |
| `birth_date` | DATE | — | Tanggal lahir (nullable) |
| `status` | ENUM | — | active / inactive |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Tabel `homeroom_teachers`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `user_id` | BIGINT | — | FK ke users |
| `scan_token` | VARCHAR | **80** | Token untuk scan QR (nullable) |
| `class_id` | BIGINT | — | FK ke classes (nullable) |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Tabel `parents`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `user_id` | BIGINT | — | FK ke users |
| `name` | VARCHAR | **100** | Nama lengkap orang tua |
| `relation_status` | VARCHAR | **10** | Ayah / Ibu / Wali |
| `phone_number` | VARCHAR | **20** | Nomor HP |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Tabel `absences`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `student_id` | BIGINT | — | FK ke students |
| `attendance_time` | DATETIME | — | Waktu absensi masuk |
| `checkout_time` | DATETIME | — | Waktu absensi keluar (nullable) |
| `status` | ENUM | — | Hadir / Terlambat / Sakit / Izin / Alfa |
| `late_duration` | INTEGER | — | Durasi terlambat (menit, nullable) |
| `reason` | VARCHAR | **200** | Keterangan (nullable) |
| `recorded_by` | VARCHAR | **50** | Dicatat oleh (nullable) |
| `is_manual_corrected` | BOOLEAN | — | Flag koreksi manual |
| `corrected_by` | VARCHAR | **100** | Nama yang koreksi (nullable) |
| `correction_note` | TEXT | — | Catatan koreksi (nullable) |
| `notes` | TEXT | — | Catatan tambahan (nullable) |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Tabel `settings`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `key` | VARCHAR | **50** | Kunci setting (unique) |
| `value` | TEXT | — | Nilai setting (nullable) |
| `description` | VARCHAR | **200** | Deskripsi setting (nullable) |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Tabel `izin_requests`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `student_id` | BIGINT | — | FK ke students |
| `request_date` | DATE | — | Tanggal izin |
| `type` | ENUM | — | Sakit / Izin |
| `reason` | TEXT | — | Keterangan dari orang tua |
| `attachment_path` | VARCHAR | **200** | Path file bukti (nullable) |
| `status` | ENUM | — | Pending / Approved / Rejected |
| `approved_by` | BIGINT | — | FK ke users (nullable) |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Tabel `announcements`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `title` | VARCHAR | **150** | Judul pengumuman |
| `content` | TEXT | — | Isi pengumuman |
| `target_type` | ENUM | — | all / class |
| `target_id` | BIGINT | — | FK ke classes (nullable) |
| `is_active` | BOOLEAN | — | Status aktif |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Tabel `subjects`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `name` | VARCHAR | **100** | Nama mata pelajaran |
| `code` | VARCHAR | **20** | Kode mapel (nullable), misal: MTK, BIN |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Tabel `schedules`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `class_id` | BIGINT | — | FK ke classes |
| `subject_id` | BIGINT | — | FK ke subjects |
| `day` | VARCHAR | **10** | Senin / Selasa / ... / Sabtu |
| `start_time` | TIME | — | Jam mulai |
| `end_time` | TIME | — | Jam selesai |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Tabel `holidays`

| Kolom | Tipe | Ukuran | Keterangan |
|-------|------|--------|------------|
| `id` | BIGINT UNSIGNED | — | Primary Key |
| `date` | DATE | — | Tanggal hari libur |
| `created_at` / `updated_at` | TIMESTAMP | — | — |

---

## Rekap Cepat — Semua VARCHAR Sesudah Perubahan

| Tabel | Kolom | Ukuran Akhir |
|-------|-------|-------------|
| users | name | **100** |
| users | email | **100** |
| users | password | 255 |
| password_reset_tokens | email | **100** |
| password_reset_tokens | token | **100** |
| sessions | id | 255 |
| sessions | ip_address | 45 |
| personal_access_tokens | token | 64 |
| classes | name | **20** |
| classes | grade | **5** |
| classes | major | **100** |
| students | nisn | **10** |
| students | nis | **20** |
| students | name | **100** |
| students | email | **100** |
| students | photo | **150** |
| students | barcode_data | **50** |
| students | phone_number | **20** |
| students | address | **300** |
| students | birth_place | 100 |
| homeroom_teachers | scan_token | 80 |
| parents | name | **100** |
| parents | relation_status | **10** |
| parents | phone_number | **20** |
| absences | reason | **200** |
| absences | recorded_by | **50** |
| absences | corrected_by | **100** |
| settings | key | **50** |
| settings | description | **200** |
| izin_requests | attachment_path | **200** |
| announcements | title | **150** |
| subjects | name | **100** |
| subjects | code | **20** |
| schedules | day | **10** |
