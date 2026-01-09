const formVisualizar = document.getElementById('formVisualizar');
const nome = document.getElementById('nome');
const data = document.getElementById('data');
const inicial = document.getElementById('inicial');
const final = document.getElementById('final');
const horas = document.getElementById('horas');
const selecao = document.getElementById('selecao');
const conteudo = document.getElementById('conteudo');
const objetivo = document.getElementById('objetivo');

function buscarSessoes() {
    return JSON.parse(localStorage.getItem('sessoes') || '[]');
};

function pegarIndex() {
    const parametros = new URLSearchParams(window.location.search);
    return Number(parametros.get('index'));
};

function carregarSessao() {
    const index = pegarIndex();
    const sessoes = buscarSessoes();
    const sessao = sessoes[index];

    nome.value = sessao.nome;
    data.value = sessao.data;
    inicial.value = sessao.inicial;
    final.value = sessao.final;
    horas.value = sessao.horas;
    selecao.value = sessao.selecao;
    conteudo.value = sessao.conteudo;
    objetivo.value = sessao.objetivo;
};

function converterHoras(horario){
    const [hora, minuto] = horario.split(":").map(Number);
    return hora * 60 + minuto;
};

formVisualizar.addEventListener('submit', function(v){
    v.preventDefault();

    window.location.href = '/res-sessoes.html';
});

carregarSessao();