<?php ob_start(); ?>
<h1>Dashboard</h1>
<p>Mini Course Enrollment DB Management App — Lab05</p>

<div class="dashboard-grid">
    <div class="card">
        <h2>🗄️ Database</h2>
        <p>PDO + MySQL, charset utf8mb4, prepared statements, unique constraint &amp; index.</p>
        <a class="btn" href="/health">Check DB Health</a>
    </div>
    <div class="card">
        <h2>🎓 Student Management</h2>
        <p>Quản lý học viên tiềm năng: tạo, sửa, xoá (soft delete), search, phân trang, sort.</p>
        <a class="btn primary" href="/students">View Students</a>
        <a class="btn" href="/students/create">+ Create Student</a>
    </div>
    <div class="card">
        <h2>📚 Enrollment Management</h2>
        <p>Quản lý đăng ký khóa học: mã đăng ký unique, search, phân trang, duplicate detection.</p>
        <a class="btn primary" href="/enrollments">View Enrollments</a>
        <a class="btn" href="/enrollments/create">+ Create Enrollment</a>
    </div>
    <div class="card">
        <h2>⚡ Performance &amp; Security</h2>
        <p>Whitelist sort/direction, LIMIT/OFFSET dùng PDO::PARAM_INT, CSRF token, soft delete, index trên status &amp; created_at.</p>
    </div>
</div>
<?php $content = ob_get_clean(); $title = 'Dashboard'; require __DIR__ . '/layout.php'; ?>
