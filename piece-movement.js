const squares = document.querySelectorAll('.square');
let selectedPiece = null;

squares.forEach(square => {
  square.addEventListener('mousedown', (e) => {
    const piece = e.target.querySelector('.piece');
    if (piece) {
      selectedPiece = piece;
      selectedPiece.style.pointerEvents = 'none';
      const { x, y } = square.getBoundingClientRect();
      selectedPiece.style.transform = `translate(${x}px, ${y}px)`;
    }
  });

  square.addEventListener('mouseup', (e) => {
    if (selectedPiece) {
      selectedPiece.style.pointerEvents = 'auto';
      const target = e.target;
      if (target.classList.contains('square') && target !== square) {
        const targetPiece = target.querySelector('.piece');
        if (targetPiece) {
          target.removeChild(targetPiece);
        }
        target.appendChild(selectedPiece);
      } else {
        const { x, y } = square.getBoundingClientRect();
        selectedPiece.style.transform = `translate(${x}px, ${y}px)`;
      }
      selectedPiece = null;
    }
  });
});