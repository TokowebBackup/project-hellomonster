<?php
// app/Views/Pagers/default_full.php
?>
<nav aria-label="Pagination Navigation" class="flex flex-wrap justify-center gap-2 mt-4">

    <?php
    $links = $pager->links(); // ambil semua link halaman yang sudah digenerate
    $range = 2;

    if (!empty($links)) {
        // cari halaman aktif
        $current = 1;
        $total   = count($links);
        foreach ($links as $l) {
            if (!empty($l['active'])) {
                $current = (int) $l['title'];
                break;
            }
        }

        // helper buat link page
        function pageLinkFromLinks($links, $page)
        {
            foreach ($links as $l) {
                if ((int)$l['title'] === (int)$page) {
                    return $l['uri'];
                }
            }
            return '#';
        }
    ?>

        <!-- Tombol Prev -->
        <?php if ($pager->hasPrevious()) : ?>
            <a href="<?= $pager->getPrevious() ?>" rel="prev" aria-label="Previous"
                class="inline-flex items-center px-3 py-1 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
                &laquo;<span class="ml-1">Prev</span>
            </a>
        <?php else : ?>
            <span class="inline-flex items-center px-3 py-1 rounded-l-md border border-gray-300 bg-gray-100 text-gray-300 cursor-not-allowed select-none">
                &laquo;<span class="ml-1">Prev</span>
            </span>
        <?php endif; ?>


        <!-- Halaman -->
        <?php
        // halaman pertama
        if ($current > $range + 2) {
            $uri = pageLinkFromLinks($links, 1);
            echo '<a href="' . $uri . '" class="inline-flex items-center px-4 py-1 border border-gray-300 bg-white text-gray-700 hover:bg-blue-100 rounded">1</a>';
            echo '<span class="px-2 text-gray-400 select-none">...</span>';
        }

        // halaman tengah
        for ($i = max(1, $current - $range); $i <= min($total, $current + $range); $i++) {
            $uri = pageLinkFromLinks($links, $i);
            if ($i == $current) {
                echo '<span aria-current="page" class="z-10 inline-flex items-center px-4 py-1 border border-blue-600 bg-blue-600 text-white font-semibold select-none">'
                    . $i . '</span>';
            } else {
                echo '<a href="' . $uri . '" class="inline-flex items-center px-4 py-1 border border-gray-300 bg-white text-gray-700 hover:bg-blue-100 rounded">'
                    . $i . '</a>';
            }
        }

        // halaman terakhir
        if ($current < $total - $range - 1) {
            $uri = pageLinkFromLinks($links, $total);
            echo '<span class="px-2 text-gray-400 select-none">...</span>';
            echo '<a href="' . $uri . '" class="inline-flex items-center px-4 py-1 border border-gray-300 bg-white text-gray-700 hover:bg-blue-100 rounded">'
                . $total . '</a>';
        }
        ?>


        <!-- Tombol Next -->
        <?php if ($pager->hasNext()) : ?>
            <a href="<?= $pager->getNext() ?>" rel="next" aria-label="Next"
                class="inline-flex items-center px-3 py-1 rounded-r-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
                <span class="mr-1">Next</span>&raquo;
            </a>
        <?php else : ?>
            <span class="inline-flex items-center px-3 py-1 rounded-r-md border border-gray-300 bg-gray-100 text-gray-300 cursor-not-allowed select-none">
                <span class="mr-1">Next</span>&raquo;
            </span>
        <?php endif; ?>

    <?php } ?>
</nav>