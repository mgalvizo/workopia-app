<!-- Set flash message for success -->
<?php if (isset($_SESSION['success_message'])): ?>
  <div class="message bg-green-100 p-3 my-3">
    <?= $_SESSION['success_message']; ?>
  </div>
  <!-- Immediately unset flash message   -->
  <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<!-- Set flash message for error -->
<?php if (isset($_SESSION['error_message'])): ?>
  <div class="message bg-red-100 p-3 my-3">
    <?= $_SESSION['error_message']; ?>
  </div>
  <!-- Immediately unset flash message   -->
  <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>