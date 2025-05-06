// DOM ensures we load our js correctly
document.addEventListener('DOMContentLoaded', function () {

    // connects script to the search button
    const form = document.getElementById('summonerSearchForm');

    // If it doesn't connect properly
    if (!form) return;

    // When searching
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Reads input and trims it, so that only the riot id is left
        const input = document.getElementById('summonerInput');
        const riotId = input.value.trim();

        // If it fails
        if (riotId === '') return;

        // If inputted with a #, replace it with a -
        let formattedId = riotId.replace('#', '-').toLowerCase();

        // Redirect to summoner page
        window.location.href = `/summoner/${formattedId}`;
    });
});

// Event listener for making the Enter button able to search
document.getElementById('summonerInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        document.getElementById('searchButton').click();
    }
});
