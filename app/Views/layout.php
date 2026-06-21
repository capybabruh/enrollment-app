<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title><?= e($title ?? 'Course Enrollment App') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<nav class="navbar">
    <a href="/">Dashboard</a>
    <a href="/students">Students</a>
    <a href="/students/create">+ Student</a>
    <a href="/enrollments">Enrollments</a>
    <a href="/enrollments/create">+ Enrollment</a>
    <a href="/health">Health</a>
</nav>
<main class="container">
    <?php if ($success = flash_get('success')): ?>
        <div class="alert success"><?= e($success) ?></div>
    <?php endif; ?>

    <?= $content ?? '' ?>
</main>
</body>
</html>
