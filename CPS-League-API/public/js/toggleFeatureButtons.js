document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.feature-button');
    const sections = document.querySelectorAll('.feature-section');

    // 👇 On page load: show match-history, hide others
    sections.forEach(section => {
        if (section.id === 'match-history-feature') {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    });

    // 👇 Handle button clicks
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');

            sections.forEach(section => {
                if (section.id === targetId) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    });
});
