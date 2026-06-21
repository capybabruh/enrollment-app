<?php ob_start(); ?>
<div style="text-align:center;padding:60px 0">
    <h1 style="font-size:72px;margin:0;color:#e2e8f0">404</h1>
    <h2>Page Not Found</h2>
    <p>The page you are looking for does not exist.</p>
    <a class="btn primary" href="/">Back to Dashboard</a>
</div>
<?php $content = ob_get_clean(); $title = '404 Not Found'; require __DIR__ . '/../layout.php'; ?>
