<?= loadPartial('head'); ?>
<?= loadPartial('navbar'); ?>
<section>
<div class="container mx-auto p-4 mt-4">
  <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3"><?= $status ?></div>
    <p class="text-center text-2xl mb-4"><?= $message ?></p>
    <p class="text-center">
      <a href="/listings">Go Back to Listings</a>
    </p>
  </div>
</section>
<?= loadPartial('footer'); ?>