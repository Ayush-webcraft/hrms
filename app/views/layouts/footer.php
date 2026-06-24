<?php if (Auth::check()): ?>
        </main>
    </div>
</div>
<?php else: ?>
</main>
</div>
<?php endif; ?>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
