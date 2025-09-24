<?php
// Footer parcial con cierre de contenedores y scripts de Bootstrap
?>
</main>
<footer class="bg-light text-center text-lg-start mt-4">
  <div class="text-center p-3">&copy; <?= date('Y') ?> Mi aplicación</div>
</footer>

<!-- Bootstrap 5 JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php if (!empty($_SESSION['flash'])): ?>
  <?php
    // escape for JS
    $flashMsg = str_replace("\n", "\\n", addslashes($_SESSION['flash']));
    unset($_SESSION['flash']);
  ?>
  <script>
    (function(){
      try {
        var toastEl = document.getElementById('flashToast');
        if (toastEl) {
          var body = toastEl.querySelector('.toast-body');
          if (body) body.textContent = "<?= $flashMsg ?>";
          var toast = new bootstrap.Toast(toastEl, { delay: 5000 });
          toast.show();
        }
      } catch (e) {
        console && console.error && console.error('Toast init error', e);
      }
    })();
  </script>
<?php endif; ?>
</body>
</html>
