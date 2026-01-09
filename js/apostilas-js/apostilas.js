const apostilasTbody = document.getElementById('apostilasTbody');

function buscarApostilas() {
    return JSON.parse(localStorage.getItem('apostilas') || '[]');
};

function salvarApostilas(apostilas) {
    localStorage.setItem('apostilas', JSON.stringify(apostilas));
}

function carregarApostilas() {
    const apostilas = buscarApostilas();
    if (apostilas.length === 0) {
        apostilasTbody.innerHTML = `
        <tr>
            <td colspan="2" class="text-center text-muted">Nenhuma apostila foi criada ainda</td>
        </tr>
        `;
        return;
    };
    
    apostilasTbody.innerHTML = apostilas.map((a, index) => `
        <tr data-index="${index}">
            <td>${a.nomeApostila || ''}</td>
            
            <td>
                <button class="btn btn-sm btn-warning btn-view" data-index="${index}">Visualizar</button>
                <button class="btn btn-sm btn-primary btn-edit" data-index="${index}">Editar</button>
                <button class="btn btn-sm btn-danger btn-delete" data-index="${index}">Excluir</button>
            </td>
        </tr>
    `).join('');
};

function deletarApostilas(index) {
    const apostilas = buscarApostilas();
    if (index < 0 || index >= apostilas.length) return;
    const ok = confirm(`Excluir apostila "${apostilas[index].nome}"?`);
    if (!ok) return;
    apostilas.splice(index, 1);
    salvarApostilas(apostilas);
    carregarApostilas();
};

apostilasTbody.addEventListener('click', function(e){
    const btn = e.target;
    if (btn.classList.contains('btn-delete')) {
        const idx = Number(btn.dataset.index);
        deletarApostilas(idx);
        return;
    };

    if (btn.classList.contains('btn-view')) {
        const idx = Number(btn.dataset.index);
        window.location.href = `/apostilas-crud/res-apostilas-visualizar.html?index=${idx}`;
        return;
    };

    if (btn.classList.contains('btn-edit')) {
        const idx = Number(btn.dataset.index);
        window.location.href = `/apostilas-crud/res-apostilas-alterar.html?index=${idx}`;
        return;
    };
});

carregarApostilas();