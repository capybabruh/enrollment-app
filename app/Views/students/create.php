<?php ob_start(); ?>
<h1>Create Student</h1>

<form method="post" action="/students/store" class="card form-card">
    <?= csrf_field() ?>

    <label>Full Name</label>
    <input type="text" name="full_name" value="<?= e($old['full_name'] ?? '') ?>">
    <?php if (!empty($errors['full_name'])): ?><p class="error"><?= e($errors['full_name']) ?></p><?php endif; ?>

    <label>Email</label>
    <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>">
    <?php if (!empty($errors['email'])): ?><p class="error"><?= e($errors['email']) ?></p><?php endif; ?>

    <label>Phone</label>
    <input type="text" name="phone" value="<?= e($old['phone'] ?? '') ?>">

    <label>Status</label>
    <select name="status">
        <?php foreach (['new', 'contacted', 'enrolled', 'dropped'] as $status): ?>
            <option value="<?= e($status) ?>" <?= ($old['status'] ?? 'new') === $status ? 'selected' : '' ?>>
                <?= e($status) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['status'])): ?><p class="error"><?= e($errors['status']) ?></p><?php endif; ?>

    <label>Note</label>
    <textarea name="note" rows="3"><?= e($old['note'] ?? '') ?></textarea>

    <div style="display:flex;gap:10px">
        <button class="btn primary" type="submit">Save Student</button>
        <a class="btn" href="/students">Back</a>
    </div>
</form>
<?php $content = ob_get_clean(); $title = 'Create Student'; require __DIR__ . '/../layout.php'; ?>
