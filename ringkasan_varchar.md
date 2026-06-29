# Ringkasan Analisis Optimasi VARCHAR — Skripsi Absensi

## Tabel `users` & `password_reset_tokens` & `sessions`
`create_users_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `users.name` | 255 | 100 | Nama orang wajar max 100 karakter |
| `users.email` | 255 | 100 | Format email standar tidak mungkin > 100 |
| `users.password` | 255 | 255 | ✅ Tetap — bcrypt hash bisa sampai 60–72 char, aman di 255 |
| `password_reset_tokens.email` | 255 | 100 | Sama seperti email user |
| `password_reset_tokens.token` | 255 | 100 | Token reset biasanya 60–64 char |
| `sessions.id` | 255 | 255 | ✅ Tetap — session ID bisa bervariasi panjangnya |

---

## Tabel `classes`
`create_class_models_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `name` | 255 | 20 | Format: "X RPL 1", "XII IPA 3" — sangat pendek |
| `grade` | 255 | 5 | Isinya cuma: X, XI, XII |

`add_major_and_desc_to_classes_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `major` | 100 | 100 | ✅ Sudah sesuai |

---

## Tabel `students`
`create_students_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `nisn` | 255 | 10 | NISN resmi = 10 digit angka |
| `nis` | 255 | 20 | NIS sekolah max sekitar 10–20 digit |
| `name` | 255 | 100 | Nama siswa wajar max 100 karakter |
| `barcode_data` | 255 | 50 | Data barcode/QR relatif pendek |
| `phone_number` | 255 | 20 | Nomor HP internasional max ~15 digit + kode negara |

`add_photo_to_students_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `photo` | 255 | 150 | Path/nama file foto, 150 sudah lebih dari cukup |

`add_address_to_students_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `address` | 500 | 300 | Alamat lengkap, 300 karakter sudah sangat cukup |

`add_email_to_students_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `email` | 255 | 100 | Format email standar |

`add_birth_fields_to_students_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `birth_place` | 100 | 100 | ✅ Sudah sesuai |

---

## Tabel `homeroom_teachers`
`add_scan_token_to_homeroom_teachers_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `scan_token` | 80 | 80 | ✅ Sudah sesuai — token UUID/hash memang ~64–80 char |

---

## Tabel `parents`
`create_parent_models_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `name` | 255 | 100 | Nama orang tua |
| `relation_status` | 255 | 10 | Isinya cuma: Ayah, Ibu, Wali |
| `phone_number` | 255 | 20 | Nomor HP |

---

## Tabel `absences`
`create_absences_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `reason` | 255 | 200 | Keterangan izin/sakit, 200 cukup |
| `recorded_by` | 255 | 50 | Isinya: "Sistem", "Admin", nama singkat |

`add_audit_fields_to_absences_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `corrected_by` | 255 | 100 | Nama user yang melakukan koreksi |

---

## Tabel `settings`
`create_settings_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `key` | 255 | 50 | Setting key selalu pendek, misal: `attendance_start_time` |
| `description` | 255 | 200 | Deskripsi setting yang sedikit lebih panjang |

---

## Tabel `izin_requests`
`create_izin_requests_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `attachment_path` | 255 | 200 | Path file surat/bukti izin |

---

## Tabel `announcements`
`create_announcements_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `title` | 255 | 150 | Judul pengumuman wajar max 150 karakter |

---

## Tabel `subjects`
`create_subjects_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `name` | 255 | 100 | Nama mata pelajaran |
| `code` | 255 | 20 | Kode mapel, misal: MTK, BIN, IPA, PKN |

---

## Tabel `schedules`
`create_schedules_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `day` | 255 | 10 | Isinya: Senin–Sabtu, max 6 karakter |

---

## Tabel `personal_access_tokens`
`create_personal_access_tokens_table.php`

| Field | Sebelum | Sesudah | Alasan |
|-------|---------|---------|--------|
| `token` | 64 | 64 | ✅ Sudah sesuai |

---

## Rekap Perubahan (yang perlu diubah)

| No | File Migration | Field | Dari | Ke |
|----|---------------|-------|------|----|
| 1 | `create_users_table` | `users.name` | 255 | 100 |
| 2 | `create_users_table` | `users.email` | 255 | 100 |
| 3 | `create_users_table` | `password_reset_tokens.email` | 255 | 100 |
| 4 | `create_users_table` | `password_reset_tokens.token` | 255 | 100 |
| 5 | `create_class_models_table` | `classes.name` | 255 | 20 |
| 6 | `create_class_models_table` | `classes.grade` | 255 | 5 |
| 7 | `create_students_table` | `students.nisn` | 255 | 10 |
| 8 | `create_students_table` | `students.nis` | 255 | 20 |
| 9 | `create_students_table` | `students.name` | 255 | 100 |
| 10 | `create_students_table` | `students.barcode_data` | 255 | 50 |
| 11 | `create_students_table` | `students.phone_number` | 255 | 20 |
| 12 | `add_photo_to_students_table` | `students.photo` | 255 | 150 |
| 13 | `add_address_to_students_table` | `students.address` | 500 | 300 |
| 14 | `add_email_to_students_table` | `students.email` | 255 | 100 |
| 15 | `create_parent_models_table` | `parents.name` | 255 | 100 |
| 16 | `create_parent_models_table` | `parents.relation_status` | 255 | 10 |
| 17 | `create_parent_models_table` | `parents.phone_number` | 255 | 20 |
| 18 | `create_absences_table` | `absences.reason` | 255 | 200 |
| 19 | `create_absences_table` | `absences.recorded_by` | 255 | 50 |
| 20 | `add_audit_fields_to_absences_table` | `absences.corrected_by` | 255 | 100 |
| 21 | `create_settings_table` | `settings.key` | 255 | 50 |
| 22 | `create_settings_table` | `settings.description` | 255 | 200 |
| 23 | `create_izin_requests_table` | `izin_requests.attachment_path` | 255 | 200 |
| 24 | `create_announcements_table` | `announcements.title` | 255 | 150 |
| 25 | `create_subjects_table` | `subjects.name` | 255 | 100 |
| 26 | `create_subjects_table` | `subjects.code` | 255 | 20 |
| 27 | `create_schedules_table` | `schedules.day` | 255 | 10 |
