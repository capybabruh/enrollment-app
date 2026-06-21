<?php ob_start(); ?>
<div style="text-align:center;padding:60px 0">
    <h1 style="font-size:72px;margin:0;color:#e2e8f0">500</h1>
    <h2>Something Went Wrong</h2>
    <p>Sorry, we could not process your request right now. Please try again later.</p>
    <a class="btn primary" href="/">Back to Dashboard</a>
</div>
<?php $content = ob_get_clean(); $title = 'Server Error'; require __DIR__ . '/../layout.php'; ?>
