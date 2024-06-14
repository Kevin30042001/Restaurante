function buscarPlatillo() {
    const input = document.getElementById('buscar').value.toLowerCase();
    const flipCards = document.querySelectorAll('.flip-card');

    flipCards.forEach(card => {
        const title = card.querySelector('.flip-card-back h1').textContent.toLowerCase();
        if (title.includes(input)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
