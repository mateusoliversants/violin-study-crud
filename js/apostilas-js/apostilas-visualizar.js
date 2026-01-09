const formApostila = document.getElementById('formApostila');
const nomeApostila = document.getElementById('nomeApostila');
const dificuldade = document.getElementById('dificuldade');
const fileApostila = document.getElementById('fileApostila');
const arquivoAtual = document.getElementById('arquivoAtual');

function buscarApostilas() {
    return JSON.parse(localStorage.getItem('apostilas') || '[]');
};

function pegarIndex() {
    const parametros = new URLSearchParams(window.location.search);
    return Number(parametros.get('index'));
};

function carregarApostilas() {
    const index = pegarIndex();
    const apostilas = buscarApostilas();
    const apostila = apostilas[index];

    if (!apostila) return;

    nomeApostila.value = apostila.nomeApostila;
    dificuldade.value = apostila.dificuldade;

    if (apostila.fileApostila) {
        arquivoAtual.innerText = `Arquivo atual: ${apostila.fileApostila}`;
    } else {
        arquivoAtual.innerText = 'Nenhum arquivo enviado';
    };
};

formApostila.addEventListener('submit', function (e) {
    e.preventDefault();
    
    window.location.href = '/res-apostilas.html';
});

carregarApostilas();