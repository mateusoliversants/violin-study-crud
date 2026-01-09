const formEditar = document.getElementById('formEditar');
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

function salvarSessoes(lista) {
    localStorage.setItem('sessoes', JSON.stringify(lista));
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

formEditar.addEventListener('submit', function (e) {
    e.preventDefault();

    const inicialminutos = converterHoras(inicial.value);
    const finalminutos = converterHoras(final.value);
    const horasTotal = finalminutos - inicialminutos;
    const total = horasTotal / 60;

    let index = pegarIndex();
    let sessoes = buscarSessoes();

    sessoes[index] = {
        nome: nome.value.trim(),
        data: data.value,
        inicial: inicial.value,
        final: final.value,
        horas: total,
        selecao: selecao.value.trim(),
        conteudo: conteudo.value.trim(),
        objetivo: objetivo.value.trim(),
    };

    
    salvarSessoes(sessoes);

    alert('Sessão editada!');
    window.location.href = '/res-sessoes.html';
});

carregarSessao();