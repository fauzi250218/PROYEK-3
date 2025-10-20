// Efek masuk lembut tiap tile
document.addEventListener('DOMContentLoaded', () => {
    const tiles = document.querySelectorAll('.kelas-tile');

    tiles.forEach((tile, index) => {
        tile.style.opacity = 0;
        tile.style.transform = 'translateY(20px)';
        setTimeout(() => {
            tile.style.transition = 'all 0.5s ease';
            tile.style.opacity = 1;
            tile.style.transform = 'translateY(0)';
        }, 100 * index);
    });
});
