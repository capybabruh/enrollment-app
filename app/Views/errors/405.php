<?php ob_start(); ?>
<div style="text-align:center;padding:60px 0">
    <h1 style="font-size:72px;margin:0;color:#e2e8f0">405</h1>
    <h2>Method Not Allowed</h2>
    <p>This route does not support the HTTP method you used.</p>
    <a class="btn primary" href="/">Back to Dashboard</a>
</div>
<?php $content = ob_get_clean(); $title = '405 Method Not Allowed'; require __DIR__ . '/../layout.php'; ?>
