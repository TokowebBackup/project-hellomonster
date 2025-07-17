<?php
// app/Views/Pagers/default_full.php
?>
<nav aria-label="Pagination Navigation" class="inline-flex gap-1 justify-center">
    <?php if ($pager->hasPrevious()) : ?>
        <a href="<?= $pager->getPrevious() ?>" rel="prev" aria-label="Previous"
            class="inline-flex items-center px-3 py-1 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
            &laquo;
            <span class="ml-1">Prev</span>
        </a>
    <?php else : ?>
        <span
            class="inline-flex items-center px-3 py-1 rounded-l-md border border-gray-300 bg-gray-100 text-gray-300 cursor-not-allowed select-none">
            &laquo;
            <span class="ml-1">Prev</span>
        </span>
    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
        <?php if ($link['active']) : ?>
            <span aria-current="page"
                class="z-10 inline-flex items-center px-4 py-1 border border-blue-600 bg-blue-600 text-white font-semibold select-none">
                <?= $link['title'] ?>
            </span>
        <?php else : ?>
            <a href="<?= $link['uri'] ?>"
                class="inline-flex items-center px-4 py-1 border border-gray-300 bg-white text-gray-700 hover:bg-blue-100 rounded">
                <?= $link['title'] ?>
            </a>
        <?php endif ?>
    <?php endforeach ?>


    <?php if ($pager->hasNext()) : ?>
        <a href="<?= $pager->getNext() ?>" rel="next" aria-label="Next"
            class="inline-flex items-center px-3 py-1 rounded-r-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
            <span class="mr-1">Next</span>
            &raquo;
        </a>
    <?php else : ?>
        <span
            class="inline-flex items-center px-3 py-1 rounded-r-md border border-gray-300 bg-gray-100 text-gray-300 cursor-not-allowed select-none">
            <span class="mr-1">Next</span>
            &raquo;
        </span>
    <?php endif ?>
</nav>