<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="<?= e(frontend_asset('assets/js/app.js')) ?>"></script>
<script src="<?= e(frontend_asset('assets/js/navigation.js')) ?>"></script>
<?php foreach (($extraScripts ?? []) as $script): ?>
<script src="<?= e(frontend_asset('assets/js/' . $script)) ?>"></script>
<?php endforeach; ?>
</body>
</html>


