<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white shadow p-6 rounded">
        <h3 class="text-lg font-medium text-gray-800">Total Members</h3>
        <p class="text-3xl mt-2 text-primary font-bold"><?= $totalMembers ?? '0' ?></p>

        <canvas id="countryChart" height="200"></canvas>
    </div>
    <div class="bg-white shadow p-6 rounded">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Members Registration This Year</h3>
        <canvas id="membersChart" height="200"></canvas>
    </div>
</div>


<script>
    const ctx = document.getElementById('membersChart').getContext('2d');

    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    const data = <?= json_encode(array_values($monthlyCounts)) ?>;

    console.log(data)

    const membersChart = new Chart(ctx, {
        type: 'line', // ganti dari 'bar' jadi 'line'
        data: {
            labels: months,
            datasets: [{
                label: 'New Members',
                data: data,
                borderColor: 'rgba(237, 45, 86, 1)',
                backgroundColor: 'rgba(237, 45, 86, 0.3)',
                fill: true,
                tension: 0.3, // untuk curve garis lebih halus
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            },
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });
</script>

<script>
    const ctxCountry = document.getElementById('countryChart').getContext('2d');

    const countryLabels = <?= json_encode($countryLabels) ?>;
    const countryCounts = <?= json_encode($countryCounts) ?>;

    const backgroundColors = [
        '#ED2D56', '#016BAF', '#F8CD07', '#5D5DA9', '#35B043',
        '#FF9F1C', '#FF6F59', '#9CFF2E', '#2EFFD9', '#FF2E8D',
    ];

    const countryChart = new Chart(ctxCountry, {
        type: 'pie',
        data: {
            labels: countryLabels,
            datasets: [{
                data: countryCounts,
                backgroundColor: backgroundColors,
                borderColor: '#fff',
                borderWidth: 2,
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    enabled: true,
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>