<html>

<head>
    <title>Win_Rate_Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            margin: 40px;
        }
        canvas{
            max-width: 900px;
            height: 400px;
        }

        pre {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
        }

        #chart-container{
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            max-width: 900px;
            margin: auto;
        }
        canvas{
            width: 100% !important;
            height: auto !important;
        }

    </style>
</head>

<body>

<h2>Win_rate Progression - Grouped based on group_types </h2>


<div id="chart-container">
    <!-- Chart container -->
    <canvas id="winRateChart"></canvas>
</div>

<!-- Debug output -->
<h3> debug groupedHistory</h3>
<!-- <pre> {{print_r($groupedRankedHistory, true)}} </pre> -->


<script>

    let groupedRankedHistory = {!! json_encode($groupedRankedHistory ?? []) !!};



    const labels = Array.from({ length: 10}, (_, i) => `Match ${i + 1}`);

    // Build datasets dynamically
    const datasets = groupedRankedHistory.map(group => ({
        label: group.queue_type.replace('RANKED_','').replace('_SR',''),
        data: group.win_rates,
        fill: true,
        borderWidth: 2,
        tension: 0.2
        //   backgroundColor: 'rgba(75,192,192,0.2)',
        //   borderColor: 'rgba(75,192,192,1)'
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
                y:{
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Win rate (%)'
                    }
                },
                x:{
                    title: {
                        display: true,
                        text: 'Match Order (Oldest -> Newest)'
                    }
                }
            }
        }
    });

    console.log("groupedRankedHistory", groupedRankedHistory);

</script>

</body>
</html>

