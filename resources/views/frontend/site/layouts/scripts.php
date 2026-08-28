<script src="<?= e(frontend_asset('assets/js/app.js')) ?>"></script>
<script src="<?= e(frontend_asset('assets/js/navigation.js')) ?>"></script>
<?php foreach (($extraScripts ?? []) as $script): ?>
<script src="<?= e(frontend_asset('assets/js/' . $script)) ?>"></script>
<?php endforeach; ?>
<?= tracking_code('body_end') ?>
</body>
</html>


