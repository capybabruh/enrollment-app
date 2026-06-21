<?php
// database/seed_data.php
// Sinh thêm 100+ bản ghi để test pagination/EXPLAIN với dữ liệu lớn.
// Chạy: php database/seed_data.php

require __DIR__ . '/../app/Core/Database.php';
$config = require __DIR__ . '/../config/database.php';
$pdo = (new Database($config))->getConnection();

$firstNames = ['An', 'Binh', 'Chi', 'Dung', 'Ha', 'Hung', 'Lan', 'Mai', 'Nam', 'Phuong',
               'Quang', 'Thu', 'Tuan', 'Uyen', 'Viet', 'Xuan', 'Yen', 'Khoa', 'Linh', 'Minh'];
$lastNames  = ['Nguyen', 'Tran', 'Le', 'Pham', 'Hoang', 'Huynh', 'Phan', 'Vu', 'Dang', 'Bui'];
$studentStatuses = ['new', 'contacted', 'enrolled', 'dropped'];
$courses = ['PHP Web Development', 'Java Backend', 'Python Foundation', 'AI Fundamentals',
            'DevOps Essentials', 'Mobile App with Flutter', 'Data Analyst', 'UI/UX Design'];
$enrollmentStatuses = ['pending', 'paid', 'cancelled'];

$pdo->exec("DELETE FROM enrollments WHERE enrollment_code LIKE 'SEED-%'");
$pdo->exec("DELETE FROM students WHERE email LIKE '%@seed.test'");

echo "Seeding students...\n";
$stmtStudent = $pdo->prepare(
    "INSERT INTO students (full_name, email, phone, status, note, created_at)
     VALUES (:full_name, :email, :phone, :status, :note, :created_at)"
);
for ($i = 1; $i <= 120; $i++) {
    $first  = $firstNames[array_rand($firstNames)];
    $last   = $lastNames[array_rand($lastNames)];
    $name   = "$last $first";
    $email  = strtolower("student{$i}@seed.test");
    $phone  = '09' . str_pad((string) rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    $status = $studentStatuses[array_rand($studentStatuses)];
    $days   = rand(0, 180);
    $date   = date('Y-m-d H:i:s', strtotime("-{$days} days"));

    $stmtStudent->execute([
        'full_name'  => $name,
        'email'      => $email,
        'phone'      => $phone,
        'status'     => $status,
        'note'       => "Seed record #$i",
        'created_at' => $date,
    ]);
}
echo "  -> 120 students inserted.\n";

echo "Seeding enrollments...\n";
$stmtEnrollment = $pdo->prepare(
    "INSERT INTO enrollments (enrollment_code, student_name, student_email, course_name, fee_amount, status, created_at)
     VALUES (:enrollment_code, :student_name, :student_email, :course_name, :fee_amount, :status, :created_at)"
);
for ($i = 1; $i <= 120; $i++) {
    $first  = $firstNames[array_rand($firstNames)];
    $last   = $lastNames[array_rand($lastNames)];
    $name   = "$last $first";
    $code   = 'SEED-' . str_pad((string) $i, 5, '0', STR_PAD_LEFT);
    $email  = strtolower("enroll{$i}@seed.test");
    $course = $courses[array_rand($courses)];
    $fee    = rand(800, 3500) * 1000;
    $status = $enrollmentStatuses[array_rand($enrollmentStatuses)];
    $days   = rand(0, 180);
    $date   = date('Y-m-d H:i:s', strtotime("-{$days} days"));

    $stmtEnrollment->execute([
        'enrollment_code' => $code,
        'student_name'    => $name,
        'student_email'   => $email,
        'course_name'     => $course,
        'fee_amount'      => $fee,
        'status'          => $status,
        'created_at'      => $date,
    ]);
}
echo "  -> 120 enrollments inserted.\n";
echo "Done! Total: 120 students + 120 enrollments.\n";
