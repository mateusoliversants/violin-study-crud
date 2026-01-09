const sessoesTbody = document.getElementById('sessoesTbody');

function buscarSessoes() {
    return JSON.parse(localStorage.getItem('sessoes') || '[]');
};

function salvarSessoes(sessoes) {
    localStorage.setItem('sessoes', JSON.stringify(sessoes));
}

function carregarSessoes() {
    const sessoes = buscarSessoes();
    if (sessoes.length === 0) {
        sessoesTbody.innerHTML = `
        <tr>
            <td colspan="2" class="text-center text-muted">Nenhuma sessão foi criada ainda</td>
        </tr>
        `;
        return;
    };

    sessoesTbody.innerHTML = sessoes.map((s, index) => `
        <tr data-index="${index}">
            <td>${s.nome || ''}</td>
            
            <td>
                <button class="btn btn-sm btn-warning btn-view" data-index="${index}">Visualizar</button>
                <button class="btn btn-sm btn-primary btn-edit" data-index="${index}">Editar</button>
                <button class="btn btn-sm btn-danger btn-delete" data-index="${index}">Excluir</button>
            </td>
        </tr>
    `).join('');
};

function deletarSessao(index) {
    const sessoes = buscarSessoes();
    if (index < 0 || index >= sessoes.length) return;
    const ok = confirm(`Excluir sessão "${sessoes[index].nome}"?`);
    if (!ok) return;
    sessoes.splice(index, 1);
    salvarSessoes(sessoes);
    carregarSessoes();
};

sessoesTbody.addEventListener('click', function(e){
    const btn = e.target;
    if (btn.classList.contains('btn-delete')) {
        const idx = Number(btn.dataset.index);
        deletarSessao(idx);
        return;
    };

    if (btn.classList.contains('btn-view')) {
        const idx = Number(btn.dataset.index);
        window.location.href = `/sessoes-crud/res-sessoes-visualizar.html?index=${idx}`;
        return;
    };

    if (btn.classList.contains('btn-edit')) {
        const idx = Number(btn.dataset.index);
        window.location.href = `/sessoes-crud/res-sessoes-alterar.html?index=${idx}`;
        return;
    };
});

carregarSessoes();
