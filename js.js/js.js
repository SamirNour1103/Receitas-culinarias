let parAtual = 0;

function atualizarLivro() {
    const pares = document.querySelectorAll('.pagina-parativa');
    const totalPares = pares.length - 1;

    if (parAtual < 0) parAtual = 0;
    if (parAtual > totalPares) parAtual = totalPares;

    pares.forEach((par) => {
        const numPar = parseInt(par.getAttribute('data-pagina-par'));
        
        // Remove todas as classes de estado
        par.classList.remove('ativa', 'passada', 'futura');

        if (numPar === parAtual) {
            // Página atual visível
            par.classList.add('ativa');
        } else if (numPar < parAtual) {
            // Páginas que já passaram (ficam dobradas para a esquerda)
            par.classList.add('passada');
        } else {
            // Páginas futuras (esperando dobradas na direita)
            par.classList.add('futura');
        }
    });
}

function mudarPar(direcao) {
    parAtual += direcao;
    atualizarLivro();
}

function irParaPagina(numPagina) {
    parAtual = Math.ceil(numPagina / 2);
    atualizarLivro();
}

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const receitaIdParam = urlParams.get('id');
    
    // Se veio um ID de receita na URL, calcula o par correspondente
    if (receitaIdParam && typeof idParaIndiceMap !== 'undefined' && idParaIndiceMap[receitaIdParam] !== undefined) {
        parAtual = Math.ceil(idParaIndiceMap[receitaIdParam] / 2);
    }
    
    atualizarLivro();
});

document.addEventListener('DOMContentLoaded', () => {
  const mobileMenu = document.getElementById('mobile-menu');
  const nav = document.querySelector('.nav');

  if (mobileMenu && nav) {
    mobileMenu.addEventListener('click', () => {
      nav.classList.toggle('active');
      
      // Alterna o ícone entre barras (hambúrguer) e 'X' (fechar)
      const icon = mobileMenu.querySelector('i');
      if (icon) {
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-xmark');
      }
    });
  }
});