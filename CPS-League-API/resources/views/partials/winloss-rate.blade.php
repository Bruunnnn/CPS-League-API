
<html>

<head>
    <title>Win Rate Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<link rel="stylesheet" href="{{ asset('/css/winloss-rate.css') }}">

<div class="winloss-summary">
    <div class="winloss-header">
        <div class="winloss-title">Win-loss Ratio:</div>
    </div>

    <!-- buttons for toggling solo/flex -->
    <input type="radio" id="soloToggle" name="rankedToggle" checked>
    <input type="radio" id="flexToggle" name="rankedToggle">



        <!--<canvas id="winLossChart" width="600" height="300"></canvas> -->
<!--
        <script>
            const ctx = document.getElementById('winLossChart').getContext('2d');

            const winLossChart = new Chart(ctx, {
                type: 'line', // You can change to 'line' if preferred
                data: {
                    labels: ['Solo', 'Flex'],
                    datasets: [
                        {
                            label: 'Wins',
                            data: [{{ $soloWins }}, {{ $flexWins }}],
            backgroundColor: 'rgba(75, 192, 192, 0.6)'
            },
            {
                label: 'Losses',
                    data: [{{ $soloLosses }}, {{ $flexLosses }}],
                backgroundColor: 'rgba(255, 99, 132, 0.6)'
            }
            ]
            },
            options: {
                responsive: true,
                    scales: {
                    y: {
                        beginAtZero: true,
                            ticks: {
                            precision: 0
                        }
                    }
                }
            }
            });
        </script>
-->

    <div class="winloss-content">
        <!-- Pie chart for solo -->
        <div class="pie-chart solo" style="--win: {{ round($soloWinratePercent, 1) }}%;">
            <div class="pie-center">
                <div>{{ round($soloWinratePercent, 1) }}% WR</div>
                <div class="ranked-type">Solo</div>
                <!-- Display Solo or Flex -->
            </div>
        </div>





        <!-- Pie chart for flex -->
        <div class="pie-chart flex" style="--win: {{ round($flexWinratePercent, 1) }}%;">
            <div class="pie-center">
                <div>{{ round($flexWinratePercent, 1) }}% WR</div>
                <div class="ranked-type">Flex</div>
            </div>
        </div>


        <!-- Win-loss numbers for solo -->
        <div class="winloss-numbers solo">
            <div>Wins: {{ $soloWins }}</div>
            <div>Losses: {{ $soloLosses }}</div>
            <div>Total Games: {{ $totalSoloGames }}</div>
        </div>

        <!-- Win-loss numbers for flex -->
        <div class="winloss-numbers flex">
            <div>Wins: {{ $flexWins }}</div>
            <div>Losses: {{ $flexLosses }}</div>
            <div>Total Games: {{ $totalFlexGames }}</div>
        </div>
    </div>

    <div class="toggle-buttons">
        <label for="soloToggle" class="toggle-btn">Ranked Solo</label>
        <label for="flexToggle" class="toggle-btn">Ranked Flex</label>
    </div>
</div>


</body>




</html>

