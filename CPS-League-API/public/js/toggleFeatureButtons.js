document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.feature-button');
    const sections = document.querySelectorAll('.feature-section');

    //  On page load: show match-history, hide others
    sections.forEach(section => {
        section.classList.remove('active');
        if (section.id === 'match-history-feature') {
            section.classList.add('active');
        }
    });

    //  Handle button clicks
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');

            sections.forEach(section => {
                if (section.id === targetId) {
                    section.classList.add('active');
                } else {
                    section.classList.remove('active');
                }
            });
        });
    });
});
