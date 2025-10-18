document.addEventListener('DOMContentLoaded', function() {
     
    const listaPerguntas = document.getElementById('lista-perguntas');
    const listaPostagens = document.getElementById('lista-postagens');
    
    function carregarMaisItens(event) {
        event.preventDefault(); 

        const button = event.target;
        const tipoAba = button.getAttribute('data-aba');
        let offset = parseInt(button.getAttribute('data-offset'));
        const limite = 5; 

        button.disabled = true;
        button.textContent = 'Carregando...';

        const filtroMateria = document.getElementById('filtro-materia').value;
        const ordenacao = document.getElementById('ordenacao').value;

        const url = 'carregar_itens.php?aba=' + tipoAba + '&offset=' + offset + '&materia=' + filtroMateria + '&ordenacao=' + ordenacao;
        
        fetch(url)
            .then(function(response) { 
                return response.text();
            })
            .then(function(data) { 
                const partes = data.split('|||'); 
                const htmlItens = partes[0];
                const count = parseInt(partes[1]); 

                const listaContainer = tipoAba === 'perguntas' ? listaPerguntas : listaPostagens;
                
                if (!listaContainer) {
                    console.error("Contêiner da lista não encontrado:", tipoAba);
                    return;
                } 

                listaContainer.insertAdjacentHTML('beforeend', htmlItens);

                offset += count;
                button.setAttribute('data-offset', offset);

                if (count < limite) {
                    button.style.display = 'none'; 
                }

                button.disabled = false;
                button.textContent = tipoAba === 'perguntas' ? 'Ver Mais Perguntas' : 'Ver Mais Postagens';
            })
            .catch(function(error) {
                button.disabled = false;
                button.textContent = 'Erro ao carregar!';
            });
    }

    const btnVerMaisPerguntas = document.getElementById('btn-ver-mais-perguntas');
    const btnVerMaisPostagens = document.getElementById('btn-ver-mais-postagens');

    if (btnVerMaisPerguntas) {
        btnVerMaisPerguntas.addEventListener('click', carregarMaisItens);
    }
    
    if (btnVerMaisPostagens) {
        btnVerMaisPostagens.addEventListener('click', carregarMaisItens);
    }
});