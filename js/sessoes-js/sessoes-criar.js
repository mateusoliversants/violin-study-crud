const formCriar = document.getElementById('formCriar');
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

function converterHoras(horario){
    const [hora, minuto] = horario.split(":").map(Number);
    return hora * 60 + minuto;
};

formCriar.addEventListener('submit', function (e) {
    e.preventDefault();

    const inicialminutos = converterHoras(inicial.value);
    const finalminutos = converterHoras(final.value);

    const horasTotal = finalminutos - inicialminutos;
    const total = horasTotal / 60;

    let sessoes = buscarSessoes();

    const NovaSessao = {
        nome: nome.value.trim(),
        data: data.value,
        inicial: inicial.value,
        final: final.value,
        horas: total,
        selecao: selecao.value.trim(),
        conteudo: conteudo.value.trim(),
        objetivo: objetivo.value.trim(),
    };

    sessoes.push(NovaSessao);
    salvarSessoes(sessoes);

    alert('Nova sessão criada!');
    window.location.href = '/res-sessoes.html';
});