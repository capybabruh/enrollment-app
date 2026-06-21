<?php ob_start(); ?>
<h1>Edit Enrollment</h1>

<form method="post" action="/enrollments/update" class="card form-card">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= e($enrollment['id']) ?>">

    <label>Enrollment Code</label>
    <input type="text" name="enrollment_code" value="<?= e($old['enrollment_code'] ?? $enrollment['enrollment_code']) ?>">
    <?php if (!empty($errors['enrollment_code'])): ?><p class="error"><?= e($errors['enrollment_code']) ?></p><?php endif; ?>

    <label>Student Name</label>
    <input type="text" name="student_name" value="<?= e($old['student_name'] ?? $enrollment['student_name']) ?>">
    <?php if (!empty($errors['student_name'])): ?><p class="error"><?= e($errors['student_name']) ?></p><?php endif; ?>

    <label>Student Email</label>
    <input type="email" name="student_email" value="<?= e($old['student_email'] ?? $enrollment['student_email'] ?? '') ?>">
    <?php if (!empty($errors['student_email'])): ?><p class="error"><?= e($errors['student_email']) ?></p><?php endif; ?>

    <label>Course Name</label>
    <input type="text" name="course_name" value="<?= e($old['course_name'] ?? $enrollment['course_name']) ?>">
    <?php if (!empty($errors['course_name'])): ?><p class="error"><?= e($errors['course_name']) ?></p><?php endif; ?>

    <label>Fee Amount</label>
    <input type="number" name="fee_amount" value="<?= e($old['fee_amount'] ?? $enrollment['fee_amount']) ?>" min="0" step="0.01">
    <?php if (!empty($errors['fee_amount'])): ?><p class="error"><?= e($errors['fee_amount']) ?></p><?php endif; ?>

    <label>Status</label>
    <select name="status">
        <?php foreach (['pending', 'paid', 'cancelled'] as $status): ?>
            <option value="<?= e($status) ?>" <?= ($old['status'] ?? $enrollment['status']) === $status ? 'selected' : '' ?>>
                <?= e($status) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['status'])): ?><p class="error"><?= e($errors['status']) ?></p><?php endif; ?>

    <div style="display:flex;gap:10px">
        <button class="btn primary" type="submit">Update Enrollment</button>
        <a class="btn" href="/enrollments">Back</a>
    </div>
</form>
<?php $content = ob_get_clean(); $title = 'Edit Enrollment'; require __DIR__ . '/../layout.php'; ?>
