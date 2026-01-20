document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("laporanChart").getContext("2d");

    if (!ctx) return;

    const labels = JSON.parse(document.querySelector('script[data-labels]').getAttribute('data-labels'));
    const dataPoints = JSON.parse(document.querySelector('script[data-data]').getAttribute('data-data'));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Laporan',
                data: dataPoints,
                fill: true,
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                borderColor: '#28a745',
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#28a745',
                pointHoverBackgroundColor: '#28a745',
                pointHoverBorderColor: '#fff',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.parsed.y} laporan`
                    },
                    backgroundColor: '#1b4d3e',
                    titleColor: '#fff',
                    bodyColor: '#fff'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e9ecef' },
                    ticks: { stepSize: 1 }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#333' }
                }
            }
        }
    });
});
