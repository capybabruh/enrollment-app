-- database/seed.sql
-- Dữ liệu mẫu để test list/search/pagination (>=15 bản ghi mỗi bảng)

USE web_php_lab05_enrollment;

INSERT INTO users (name, email, password_hash, role) VALUES
('Admin User', 'admin@example.com', '$2y$10$examplehashadmin', 'admin'),
('Advisor Staff', 'advisor@example.com', '$2y$10$examplehashstaff', 'staff');

INSERT INTO students (full_name, email, phone, status, note) VALUES
('Anna Nguyen', 'anna@example.com', '0909000001', 'new', 'Quan tâm khóa PHP Web'),
('Ben Tran', 'ben@example.com', '0909000002', 'contacted', 'Đang cân nhắc lịch học'),
('Chris Le', 'chris@example.com', '0909000003', 'enrolled', 'Đã đóng học phí'),
('Duyen Pham', 'duyen@example.com', '0909000004', 'dropped', 'Bận công việc, tạm dừng'),
('Minh Ho', 'minh@example.com', '0909000005', 'new', 'Đăng ký tư vấn qua form'),
('Khoa Vo', 'khoa@example.com', '0909000006', 'contacted', 'Hỏi học phí khóa Java'),
('Linh Dang', 'linh@example.com', '0909000007', 'enrolled', 'Khóa Python cơ bản'),
('Nam Bui', 'nam@example.com', '0909000008', 'new', 'Quan tâm khóa AI'),
('Phuong Hoang', 'phuong@example.com', '0909000009', 'contacted', 'Yêu cầu callback'),
('Quang Huynh', 'quang@example.com', '0909000010', 'enrolled', 'Khóa DevOps'),
('Thu Phan', 'thu@example.com', '0909000011', 'new', 'Đăng ký từ Facebook Ads'),
('Tuan Dao', 'tuan@example.com', '0909000012', 'dropped', 'Không phản hồi'),
('Uyen Mai', 'uyen@example.com', '0909000013', 'enrolled', 'Khóa Data Analyst'),
('Viet Cao', 'viet@example.com', '0909000014', 'contacted', 'Đang chờ xác nhận lịch'),
('Xuan Ly', 'xuan@example.com', '0909000015', 'new', 'Quan tâm khóa Mobile App'),
('Yen Truong', 'yen@example.com', '0909000016', 'enrolled', 'Khóa UI/UX Design');

INSERT INTO enrollments (enrollment_code, student_name, student_email, course_name, fee_amount, status) VALUES
('ENR-2026-0001', 'Anna Nguyen', 'anna@example.com', 'PHP Web Development', 2500000, 'pending'),
('ENR-2026-0002', 'Ben Tran', 'ben@example.com', 'Java Backend', 850000, 'paid'),
('ENR-2026-0003', 'Chris Le', 'chris@example.com', 'Python Foundation', 1200000, 'cancelled'),
('ENR-2026-0004', 'Minh Ho', 'minh@example.com', 'AI Fundamentals', 3200000, 'paid'),
('ENR-2026-0005', 'Khoa Vo', 'khoa@example.com', 'Java Backend', 850000, 'pending'),
('ENR-2026-0006', 'Linh Dang', 'linh@example.com', 'Python Foundation', 1200000, 'paid'),
('ENR-2026-0007', 'Nam Bui', 'nam@example.com', 'AI Fundamentals', 3200000, 'pending'),
('ENR-2026-0008', 'Phuong Hoang', 'phuong@example.com', 'DevOps Essentials', 2800000, 'paid'),
('ENR-2026-0009', 'Quang Huynh', 'quang@example.com', 'DevOps Essentials', 2800000, 'pending'),
('ENR-2026-0010', 'Thu Phan', 'thu@example.com', 'Mobile App with Flutter', 2600000, 'paid'),
('ENR-2026-0011', 'Tuan Dao', 'tuan@example.com', 'PHP Web Development', 2500000, 'cancelled'),
('ENR-2026-0012', 'Uyen Mai', 'uyen@example.com', 'Data Analyst', 3000000, 'paid'),
('ENR-2026-0013', 'Viet Cao', 'viet@example.com', 'UI/UX Design', 2200000, 'pending'),
('ENR-2026-0014', 'Xuan Ly', 'xuan@example.com', 'Mobile App with Flutter', 2600000, 'pending'),
('ENR-2026-0015', 'Yen Truong', 'yen@example.com', 'UI/UX Design', 2200000, 'paid');
