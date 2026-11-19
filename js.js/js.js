document.addEventListener('DOMContentLoaded', function () {

    const livro = document.getElementById('livroContainer');

    if (!livro) return;

    let animacaoFolha = false;

    const mudarParOriginal = mudarPar;
    const irParaPaginaOriginal = irParaPagina;

    mudarPar = function (direcao) {

        if (animacaoFolha) return;

        const paginaAtual = document.querySelector(
            '.pagina-parativa[data-pagina-par="' + parAtual + '"]'
        );

        const novoPar = parAtual + direcao;

        if (novoPar < 0 || novoPar > maxPar) return;

        const novaPagina = document.querySelector(
            '.pagina-parativa[data-pagina-par="' + novoPar + '"]'
        );

        if (!paginaAtual || !novaPagina) {
            mudarParOriginal(direcao);
            return;
        }

        animacaoFolha = true;

        livro.style.perspective = '1500px';

        paginaAtual.style.transformOrigin = 'center center';
        paginaAtual.style.transformStyle = 'preserve-3d';
        paginaAtual.style.backfaceVisibility = 'hidden';
        paginaAtual.style.transition = 'transform 0.7s ease-in-out';

        if (direcao > 0) {
            paginaAtual.style.transform = 'rotateY(-90deg)';
        } else {
            paginaAtual.style.transform = 'rotateY(90deg)';
        }

        setTimeout(function () {

            mudarParOriginal(direcao);

            const paginaNova = document.querySelector(
                '.pagina-parativa[data-pagina-par="' + parAtual + '"]'
            );

            if (paginaNova) {
                paginaNova.style.transform = 'rotateY(0deg)';
            }

            paginaAtual.style.transition = 'none';
            paginaAtual.style.transform = 'rotateY(0deg)';

            setTimeout(function () {

                paginaAtual.style.transition = '';
                paginaAtual.style.transform = '';
                paginaAtual.style.transformOrigin = '';
                paginaAtual.style.transformStyle = '';
                paginaAtual.style.backfaceVisibility = '';

                if (paginaNova) {
                    paginaNova.style.transform = '';
                }

                animacaoFolha = false;

            }, 50);

        }, 700);
    };

    irParaPagina = function (numPagina) {

        if (animacaoFolha) return;

        irParaPaginaOriginal(numPagina);

    };

});