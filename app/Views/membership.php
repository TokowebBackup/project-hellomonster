<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="font-bold text-xl mb-4"><?= lang('Text.join_us') ?></h2>

<div class="flex items-center justify-around mb-6">
  <img src="<?= base_url('assets/img/Hello-Monster_Branding-Phase-1 - 1-_page-00071e3.png') ?>" alt="Logo" class="w-56" />

  <img src="<?= base_url('assets/img/Hello-Monster_Branding-Phase-1 - 1-_page-0006e2a.png') ?>" class="w-24" />
</div>

<!-- ✅ Flash Messages -->
<?php if (session()->getFlashdata('message')) : ?>
  <div class="bg-green-100 border border-green-300 text-green-800 text-sm px-4 py-3 rounded mb-4">
    <?= session()->getFlashdata('message') ?>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
  <div class="bg-red-100 border border-red-300 text-red-800 text-sm px-4 py-3 rounded mb-4">
    <?= session()->getFlashdata('error') ?>
  </div>
<?php endif; ?>

<?php if (!session()->getFlashdata('message')) : ?>
  <a href="/membership/create" class="block w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-center font-semibold">
    <?= lang('Text.signup_title') ?>
  </a>

  <p class="text-sm mt-4">
    <?= lang('Text.already_account') ?>
    <a href="/membership/login" class="text-blue-500 hover:underline"><?= lang('Text.login') ?></a>
  </p>
<?php endif; ?>


<?= $this->endSection() ?>