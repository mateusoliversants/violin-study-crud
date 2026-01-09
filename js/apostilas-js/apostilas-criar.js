const formApostila = document.getElementById('formApostila');
const nomeApostila = document.getElementById('nomeApostila');
const dificuldade = document.getElementById('dificuldade');
const fileApostila = document.getElementById('fileApostila');

function buscarApostilas() {
    return JSON.parse(localStorage.getItem('apostilas') || '[]');
};

function salvarApostilas(lista) {
    localStorage.setItem('apostilas', JSON.stringify(lista));
};

formApostila.addEventListener('submit', function (e) {
    e.preventDefault();

    let apostilas = buscarApostilas();

    const NovaApostila = {
        nomeApostila: nomeApostila.value.trim(),
        dificuldade: dificuldade.value,
        fileApostila: fileApostila.value,
    };

    apostilas.push(NovaApostila);
    salvarApostilas(apostilas);

    alert('Nova apostila criada!');
    window.location.href = '/res-apostilas.html';
});