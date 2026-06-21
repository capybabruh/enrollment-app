<?php ob_start(); ?>
<h1>Enrollment Management</h1>
<a class="btn primary" href="/enrollments/create">+ Create Enrollment</a>

<form method="get" action="/enrollments" class="toolbar">
    <input type="hidden" name="page" value="1">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search code/student/course">
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="direction" value="<?= e($direction) ?>">
    <select name="status_filter" onchange="this.form.submit()">
        <option value="">-- All Status --</option>
        <?php foreach (['pending', 'paid', 'cancelled'] as $s): ?>
            <option value="<?= e($s) ?>" <?= ($status_filter ?? '') === $s ? 'selected' : '' ?>><?= e($s) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Search</button>
</form>

<table>
<thead>
<tr>
    <th>ID</th>
    <th><a href="/enrollments?<?= e(query_string(['sort' => 'enrollment_code', 'direction' => ($sort === 'enrollment_code' && $direction === 'asc') ? 'desc' : 'asc', 'page' => 1])) ?>">
        Code <?= $sort === 'enrollment_code' ? ($direction === 'asc' ? '▲' : '▼') : '' ?>
    </a></th>
    <th><a href="/enrollments?<?= e(query_string(['sort' => 'student_name', 'direction' => ($sort === 'student_name' && $direction === 'asc') ? 'desc' : 'asc', 'page' => 1])) ?>">
        Student <?= $sort === 'student_name' ? ($direction === 'asc' ? '▲' : '▼') : '' ?>
    </a></th>
    <th>Course</th>
    <th><a href="/enrollments?<?= e(query_string(['sort' => 'fee_amount', 'direction' => ($sort === 'fee_amount' && $direction === 'asc') ? 'desc' : 'asc', 'page' => 1])) ?>">
        Fee <?= $sort === 'fee_amount' ? ($direction === 'asc' ? '▲' : '▼') : '' ?>
    </a></th>
    <th>Status</th>
    <th><a href="/enrollments?<?= e(query_string(['sort' => 'created_at', 'direction' => ($sort === 'created_at' && $direction === 'asc') ? 'desc' : 'asc', 'page' => 1])) ?>">
        Created at <?= $sort === 'created_at' ? ($direction === 'asc' ? '▲' : '▼') : '' ?>
    </a></th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($enrollments as $enrollment): ?>
<tr>
    <td><?= e($enrollment['id']) ?></td>
    <td><?= e($enrollment['enrollment_code']) ?></td>
    <td><?= e($enrollment['student_name']) ?></td>
    <td><?= e($enrollment['course_name']) ?></td>
    <td><?= number_format((float) $enrollment['fee_amount'], 0, '.', ',') ?></td>
    <td><span class="badge badge-<?= e($enrollment['status']) ?>"><?= e($enrollment['status']) ?></span></td>
    <td><?= e($enrollment['created_at']) ?></td>
    <td>
        <a href="/enrollments/edit?id=<?= e($enrollment['id']) ?>">Edit</a>
        <form method="post" action="/enrollments/delete" class="inline" onsubmit="return confirm('Delete this enrollment?')">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e($enrollment['id']) ?>">
            <button type="submit" class="link danger">Delete</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php if (empty($enrollments)): ?>
<tr><td colspan="8" style="text-align:center;color:#6b7280">No enrollments found.</td></tr>
<?php endif; ?>
</tbody>
</table>

<div class="pagination">
    <span>Total: <?= e($total) ?> records</span>
    <?php if ($page > 1): ?>
        <a href="/enrollments?<?= e(query_string(['page' => $page - 1])) ?>">Prev</a>
    <?php endif; ?>
    <span>Page <?= e($page) ?> / <?= e($totalPages) ?></span>
    <?php if ($page < $totalPages): ?>
        <a href="/enrollments?<?= e(query_string(['page' => $page + 1])) ?>">Next</a>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); $title = 'Enrollment Management'; require __DIR__ . '/../layout.php'; ?>
