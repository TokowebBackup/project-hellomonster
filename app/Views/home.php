<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="space-y-5 w-[500px] max-w-full px-6 text-gray-900 relative z-10">
  <p class="font-secondary text-2xl leading-snug text-left">
    <?= lang('Text.enter_email_message') ?>
  </p>

  <form id="emailForm" action="/membership/check" method="post" class="space-y-3">
    <?= csrf_field() ?>

    <input
      type="email"
      name="email"
      placeholder="<?= lang('Text.placeholder_email') ?>"
      required
      class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary font-secondary">
    <button
      type="submit"
      class="w-full py-2 px-4 bg-primary text-white text-base font-semibold rounded-md hover:bg-blue-700 transition">
      <?= lang('Text.next') ?>
    </button>
  </form>

  <a
    href="/membership"
    class="block w-full py-2 text-center bg-softgray text-gray-700 rounded-md hover:bg-gray-300 font-semibold font-secondary">
    <?= lang('Text.buy_membership') ?>
  </a>
</div>

<!-- ✅ Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-white/80 z-[9999] hidden flex items-center justify-center">
  <div class="flex items-center space-x-2 text-primary">
    <svg class="animate-spin h-16 w-16 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor"
        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>
    <span>Loading...</span>
  </div>
</div>

<script>
  document.getElementById('emailForm').addEventListener('submit', function() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
  });
</script>

<?= $this->endSection() ?>