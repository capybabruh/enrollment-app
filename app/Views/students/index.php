<?php ob_start(); ?>
<h1>Student Management</h1>
<a class="btn primary" href="/students/create">+ Create Student</a>

<form method="get" action="/students" class="toolbar">
    <input type="hidden" name="page" value="1">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search name/email/phone">
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="direction" value="<?= e($direction) ?>">
    <select name="status_filter" onchange="this.form.submit()">
        <option value="">-- All Status --</option>
        <?php foreach (['new', 'contacted', 'enrolled', 'dropped'] as $s): ?>
            <option value="<?= e($s) ?>" <?= ($status_filter ?? '') === $s ? 'selected' : '' ?>><?= e($s) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Search</button>
</form>

<table>
<thead>
<tr>
    <th>ID</th>
    <th><a href="/students?<?= e(query_string(['sort' => 'full_name', 'direction' => ($sort === 'full_name' && $direction === 'asc') ? 'desc' : 'asc', 'page' => 1])) ?>">
        Full Name <?= $sort === 'full_name' ? ($direction === 'asc' ? '▲' : '▼') : '' ?>
    </a></th>
    <th>Email</th>
    <th>Phone</th>
    <th>Status</th>
    <th><a href="/students?<?= e(query_string(['sort' => 'created_at', 'direction' => ($sort === 'created_at' && $direction === 'asc') ? 'desc' : 'asc', 'page' => 1])) ?>">
        Created at <?= $sort === 'created_at' ? ($direction === 'asc' ? '▲' : '▼') : '' ?>
    </a></th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($students as $student): ?>
<tr>
    <td><?= e($student['id']) ?></td>
    <td><?= e($student['full_name']) ?></td>
    <td><?= e($student['email']) ?></td>
    <td><?= e($student['phone']) ?></td>
    <td><span class="badge badge-<?= e($student['status']) ?>"><?= e($student['status']) ?></span></td>
    <td><?= e($student['created_at']) ?></td>
    <td>
        <a href="/students/edit?id=<?= e($student['id']) ?>">Edit</a>
        <form method="post" action="/students/delete" class="inline" onsubmit="return confirm('Delete this student?')">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e($student['id']) ?>">
            <button type="submit" class="link danger">Delete</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php if (empty($students)): ?>
<tr><td colspan="7" style="text-align:center;color:#6b7280">No students found.</td></tr>
<?php endif; ?>
</tbody>
</table>

<div class="pagination">
    <span>Total: <?= e($total) ?> records</span>
    <?php if ($page > 1): ?>
        <a href="/students?<?= e(query_string(['page' => $page - 1])) ?>">Prev</a>
    <?php endif; ?>
    <span>Page <?= e($page) ?> / <?= e($totalPages) ?></span>
    <?php if ($page < $totalPages): ?>
        <a href="/students?<?= e(query_string(['page' => $page + 1])) ?>">Next</a>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); $title = 'Student Management'; require __DIR__ . '/../layout.php'; ?>
