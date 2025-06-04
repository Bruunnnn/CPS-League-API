
const labels = Array.from({length: 10}, (_, i) => `Match ${i + 1}`);

// Build datasets dynamically
const datasets = groupedRankedHistory.map(group => ({
    label: group.queue_type.replace('RANKED_', '').replace('_SR', ''),
    data: group.win_rates,
    fill: true,
    borderWidth: 2,
    tension: 0.2
}));

const graphContext = document.getElementById('winRateChart').getContext('2d');
new Chart(graphContext, {
    type: 'line',
    data: {
        labels: labels,
        datasets: datasets
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                enabled: true
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                title: {
                    display: true,
                    text: 'Win rate (%)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Match Order (Oldest -> Newest)'
                }
            }
        }
    }
});

