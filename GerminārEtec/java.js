document.addEventListener('DOMContentLoaded', () => {

    const menuButton = document.querySelector('#menu');
    const opcoesDiv = document.querySelector('.opcoes');
    const conteudoPerguntas = document.querySelector('#abaperguntas');
    const conteudoPosts = document.querySelector('#abapostagens');
    const perguntasButton = document.querySelector('#perguntas');
    const postagensButton = document.querySelector('#postagens');

    if (menuButton && opcoesDiv) {
        menuButton.addEventListener('click', function() {
            if (opcoesDiv.style.display === "none") {
                opcoesDiv.style.display = "block";
            } else {
                opcoesDiv.style.display = 'none';
            }
        });
    }

    if (perguntasButton && postagensButton && conteudoPerguntas && conteudoPosts) {
        perguntasButton.addEventListener('click', function() {
            conteudoPosts.style.display = 'none';
            conteudoPerguntas.style.display = 'block';
        });

        postagensButton.addEventListener('click', function() {
            conteudoPerguntas.style.display = 'none';
            conteudoPosts.style.display = 'block';
        });
    }

    const btnAbrir = document.getElementById('btn-abrir-resposta');
    const formResposta = document.getElementById('form-resposta');

    if (btnAbrir && formResposta) {
        btnAbrir.addEventListener('click', () => {
            if (formResposta.style.display === 'none') {
                formResposta.style.display = 'block';
                btnAbrir.textContent = 'Esconder'; 
            } else {
                formResposta.style.display = 'none';
                btnAbrir.textContent = 'Adicionar resposta'; 
            }
        });
    }

    const botoesVerMais = document.querySelectorAll('.ver-mais');

    botoesVerMais.forEach(botao => {
        botao.addEventListener('click', () => {
            const respostaDiv = botao.closest('.resposta');
            const textoTruncado = respostaDiv.querySelector('.texto-truncado');
            const textoCompleto = respostaDiv.querySelector('.texto-completo');

            if (textoCompleto.style.display === 'none' || textoCompleto.style.display === '') {
                textoCompleto.style.display = 'inline';
                if(textoTruncado) {
                    textoTruncado.style.display = 'none';
                }
                botao.textContent = 'Mostrar menos';
            } else {
                textoCompleto.style.display = 'none';
                if(textoTruncado) {
                    textoTruncado.style.display = 'inline';
                }
                botao.textContent = 'Ler mais';
            }
        });
    });
});