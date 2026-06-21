<?php ob_start(); ?>
<h1>Create Enrollment</h1>

<form method="post" action="/enrollments/store" class="card form-card">
    <?= csrf_field() ?>

    <label>Enrollment Code</label>
    <input type="text" name="enrollment_code" value="<?= e($old['enrollment_code'] ?? '') ?>" placeholder="e.g. ENR-2026-0001">
    <?php if (!empty($errors['enrollment_code'])): ?><p class="error"><?= e($errors['enrollment_code']) ?></p><?php endif; ?>

    <label>Student Name</label>
    <input type="text" name="student_name" value="<?= e($old['student_name'] ?? '') ?>">
    <?php if (!empty($errors['student_name'])): ?><p class="error"><?= e($errors['student_name']) ?></p><?php endif; ?>

    <label>Student Email</label>
    <input type="email" name="student_email" value="<?= e($old['student_email'] ?? '') ?>">
    <?php if (!empty($errors['student_email'])): ?><p class="error"><?= e($errors['student_email']) ?></p><?php endif; ?>

    <label>Course Name</label>
    <input type="text" name="course_name" value="<?= e($old['course_name'] ?? '') ?>" placeholder="e.g. PHP Web Development">
    <?php if (!empty($errors['course_name'])): ?><p class="error"><?= e($errors['course_name']) ?></p><?php endif; ?>

    <label>Fee Amount</label>
    <input type="number" name="fee_amount" value="<?= e($old['fee_amount'] ?? '') ?>" min="0" step="0.01">
    <?php if (!empty($errors['fee_amount'])): ?><p class="error"><?= e($errors['fee_amount']) ?></p><?php endif; ?>

    <label>Status</label>
    <select name="status">
        <?php foreach (['pending', 'paid', 'cancelled'] as $status): ?>
            <option value="<?= e($status) ?>" <?= ($old['status'] ?? 'pending') === $status ? 'selected' : '' ?>>
                <?= e($status) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['status'])): ?><p class="error"><?= e($errors['status']) ?></p><?php endif; ?>

    <div style="display:flex;gap:10px">
        <button class="btn primary" type="submit">Save Enrollment</button>
        <a class="btn" href="/enrollments">Back</a>
    </div>
</form>
<?php $content = ob_get_clean(); $title = 'Create Enrollment'; require __DIR__ . '/../layout.php'; ?>
