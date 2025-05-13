<html>

<head>
    <title>Graphs</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<h2>Match History: Win/Loss for the 5 latest games</h2>
<canvas id="matchHistoryGraph" width="600" height="300"></canvas>

<script>

    // Logic for Match history win/loss graph

    const matchHistoryContext = document.getElementById('matchHistoryGraph').getContext('2d');
    const matchGraph = new Chart(matchHistoryContext, {
        type: 'line',
        data: {
            labels: {!!json_encode($matchLabels ?? [])!!},
            datasets: [{
                label: 'Win (1 / Loss (0))',
                data: {!!json_encode($matchWinValues ?? [])!!},
                borderColor: 'green',
                    borderWidth: 2,
                fill: false,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    min: 0,
                        max: 1,
                        ticks:{
                        stepSize: 1
                    },
                        title: {
                        display: true,
                            text: 'Result'
                    }
                }
            }
        }
    });


</script>
</body>
</html>
