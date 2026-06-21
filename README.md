# Mini Course Enrollment DB App

Ứng dụng quản lý **học viên đăng ký tư vấn** (Students) và **lượt đăng ký khóa học** (Enrollments), xây dựng bằng PHP thuần theo kiến trúc `Browser → public/index.php → Router → Controller → Repository → PDO → MySQL → View/Redirect`.

Bài thực hành Lab05 — PHP Database CRUD: PDO, Repository, Pagination, Unique & Index.

## Yêu cầu môi trường

- PHP >= 8.1 (có extension `pdo_mysql`)
- MySQL hoặc MariaDB
- Git

## Cài đặt

### 1. Clone project

```bash
git clone https://github.com/capybabruh/enrollment-app.git
cd enrollment-app
```

### 2. Tạo database

Chạy lần lượt 2 file SQL trong MySQL Workbench / phpMyAdmin / CLI:

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql
```

`schema.sql` tạo database `web_php_lab05_enrollment` cùng 4 bảng: `users`, `students`, `enrollments`, `enrollment_payments`.
`seed.sql` thêm sẵn 16 students + 15 enrollments mẫu.

### 3. Cấu hình kết nối DB

Mở `config/database.php`, chỉnh lại `username`/`password` cho đúng MySQL trên máy bạn:

```php
return [
    'host' => 'localhost',
    'database' => 'web_php_lab05_enrollment',
    'username' => 'root',
    'password' => 'mật_khẩu_của_bạn',
    'charset' => 'utf8mb4',
];
```

### 4. Chạy server

```bash
php -S localhost:8000 -t public
```

Mở trình duyệt: [http://localhost:8000](http://localhost:8000)

### 5. (Tuỳ chọn) Seed thêm dữ liệu lớn để test pagination/EXPLAIN

```bash
php database/seed_data.php
```

Sinh thêm 120 students + 120 enrollments ngẫu nhiên.

## Cấu trúc thư mục

```
enrollment-app/
├── app/
│   ├── Controllers/      # HomeController, HealthController, StudentController, EnrollmentController, AuthController
│   ├── Core/             # Database, Router, helpers, DuplicateRecordException, AuthMiddleware
│   ├── Repositories/     # StudentRepository, EnrollmentRepository (toàn bộ SQL nằm ở đây)
│   ├── Services/         # StudentService (Controller -> Service -> Repository)
│   └── Views/            # students/, enrollments/, errors/, auth/, layout.php, dashboard.php
├── config/
│   ├── app.php
│   └── database.php
├── database/
│   ├── schema.sql        # Tạo DB + 4 bảng
│   ├── seed.sql           # 15+ bản ghi mẫu mỗi bảng
│   └── seed_data.php      # Sinh 120+120 bản ghi để test pagination/EXPLAIN
├── public/
│   ├── index.php          # Front Controller
│   └── assets/style.css
└── storage/logs/app.log   # Log lỗi DB (không hiển thị SQLSTATE cho user)
```

## Route chính

| Method | URL | Ý nghĩa |
|---|---|---|
| GET | `/` | Dashboard |
| GET | `/health` | JSON kiểm tra kết nối DB |
| GET | `/students` | List + search + pagination + sort + filter status |
| GET | `/students/create` | Form tạo học viên |
| POST | `/students/store` | Tạo học viên |
| GET | `/students/edit?id=` | Form sửa |
| POST | `/students/update` | Cập nhật |
| POST | `/students/delete` | Xóa (soft delete) |
| GET | `/enrollments` | List + search + pagination + sort + filter status |
| GET | `/enrollments/create` | Form tạo đăng ký |
| POST | `/enrollments/store` | Tạo đăng ký |
| GET | `/enrollments/edit?id=` | Form sửa |
| POST | `/enrollments/update` | Cập nhật |
| POST | `/enrollments/delete` | Xóa (soft delete) |
| ANY | URL không tồn tại | 404 Not Found |
| — | Sai HTTP method | 405 Method Not Allowed |

## Tính năng đã hoàn chỉnh

- PDO chuẩn: `charset=utf8mb4`, `ERRMODE_EXCEPTION`, `FETCH_ASSOC`, `EMULATE_PREPARES=false`
- Toàn bộ SQL dùng `prepare()` + `execute()`, không nối chuỗi input vào câu lệnh
- Sort/direction qua whitelist, không lấy thẳng từ `$_GET`
- Search + pagination + filter theo status
- Sort toggle hai chiều (▲▼) trên toàn bộ cột có thể sort
- Bắt lỗi trùng unique key (`email`, `enrollment_code`), báo lỗi thân thiện và giữ lại dữ liệu đã nhập
- PRG Pattern: mọi POST thành công đều redirect
- Soft delete (`deleted_at`) thay vì xóa thật
- CSRF token cho mọi form POST
- Logging lỗi DB vào `storage/logs/app.log`, không hiển thị SQLSTATE cho người dùng cuối
- Trang lỗi 404 / 405 / 500 riêng biệt
- `seed_data.php` sinh 120+120 bản ghi để test pagination và `EXPLAIN`

## Tính năng đang dang dở (ghi nhận trung thực)

- **Middleware login**: đã viết `AuthMiddleware.php`, `AuthController.php`, view `auth/login.php`, nhưng **route `/login` chưa được đăng ký** trong `public/index.php` và chưa route quản trị nào gọi `AuthMiddleware::handle()`. Cần hoàn thiện trước khi coi là xong.
- **Service layer**: mới áp dụng cho module Students (`StudentService`), module Enrollments vẫn gọi thẳng `EnrollmentRepository` từ Controller — chưa đồng bộ.
- **Transaction**: đã thiết kế bảng `enrollment_payments` (lịch sử thanh toán từng phần, FK tới `enrollments`) nhưng chưa viết logic `beginTransaction()/commit()/rollBack()` xử lý insert đồng thời.

## Test nhanh

```bash
# Health check
curl -i http://localhost:8000/health

# Test 404
curl -i http://localhost:8000/khong-ton-tai

# Test 405 (route /students/delete chỉ nhận POST)
curl -i http://localhost:8000/students/delete?id=1

# Test sort injection (phải fallback về mặc định, không lỗi)
curl -i "http://localhost:8000/students?sort=id+DESC%3B+DROP+TABLE+students%3B--"
```

## Tác giả

Bài thực hành Lab05 — môn PHP Web.
