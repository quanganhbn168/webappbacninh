// Story cards use Bootstrap's collapse. Closing a long one from its footer would
// leave the reader far below it, so bring the card back into view.
document.querySelectorAll('.story-card .collapse').forEach(panel => {
    panel.addEventListener('hidden.bs.collapse', () => {
        const card = panel.closest('.story-card');
        if (card.getBoundingClientRect().top < 0) card.scrollIntoView({ block: 'start' });
    });
});
