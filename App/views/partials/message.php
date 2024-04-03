<?php
use Framework\Session;
?>

<!-- Set flash message for success and immediately unsets it, we pass only success_message but the method adds a flash_ as a prefix -->
<?php $successMessage = Session::getFlashMessage('success_message'); ?>
<?php if ($successMessage !== null): ?>
  <div class="message bg-green-100 p-3 my-3">
    <?= $successMessage ?>
  </div>
<?php endif; ?>

<!-- Set flash message for error and immediately unsets it, we pass only error_message but the method adds a flash_ as a prefix -->
<?php $errorMessage = Session::getFlashMessage('error_message'); ?>
<?php if ($errorMessage !== null): ?>
  <div class="message bg-red-100 p-3 my-3">
    <?= $errorMessage ?>
  </div>
<?php endif; ?>