document.addEventListener("DOMContentLoaded", function() {
    if (!window.dashboardData) return;

    const ctx = document.getElementById('dashboardChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Kelas 7', 'Kelas 8', 'Kelas 9'],
            datasets: [{
                label: 'Jumlah Guru',
                data: [
                    window.dashboardData.kelas7,
                    window.dashboardData.kelas8,
                    window.dashboardData.kelas9
                ],
                backgroundColor: ['#509CDB', '#3B7AC6', '#152259']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
