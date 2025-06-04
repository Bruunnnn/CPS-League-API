<head>
    <title>Win_Rate_Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href={{asset('/css/graph.css')}}>
</head>
<body>

<div id="chart-container">
    <h2>Win-loss rate over time</h2>
    <canvas id="winRateChart"></canvas>
</div>

<!-- Debug output
<h3> debug groupedHistory</h3>
 <pre> {{print_r($groupedRankedHistory, JSON_PRETTY_PRINT)}} </pre>
 -->


<script>

    let groupedRankedHistory = {!! json_encode($groupedRankedHistory ?? []) !!};

    const labels = Array.from({ length: 10}, (_, i) => `Match ${i + 1}`);

    const datasets = groupedRankedHistory.map(group => ({
        label: group.queue_type.replace('RANKED_','').replace('_SR',''),
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
                    position: 'bottom',
                    labels: {
                        color: 'white'
                    }
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
                        text: 'Win rate (%)',
                        color: 'white'
                    },
                    ticks: {
                        color: 'white'
                    },
                    grid: {
                        color: 'darkslategrey'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Match Order (Oldest -> Newest)',
                        color: 'white'
                    },
                    ticks: {
                        color: 'white'
                    },
                    grid: {
                        color: 'darkslategrey'
                    }
                }
            }

        }
    });
</script>
</body>
