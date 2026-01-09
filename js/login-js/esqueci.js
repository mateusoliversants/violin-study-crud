const esqueciForm = document.getElementById('esqueciForm');
const email = document.getElementById('email');
const novaSenha = document.getElementById('novaSenha');
const confirmSenha = document.getElementById('confirmSenha');
const mensagem = document.getElementById('mensagem');

function buscarUsuario() {
    return JSON.parse(localStorage.getItem('users') || '[]');
}

function salvarUsuario(usuarioArray) {
    localStorage.setItem('users', JSON.stringify(usuarioArray));
}

function usuarioEmailIndex(emailStr) {
    const usuarios = buscarUsuario();
    return usuarios.findIndex(u => u.email && u.email.toLowerCase() === emailStr.toLowerCase());
}

function mostrarMsg(text, type = 'danger') {
    mensagem.innerText = text;
    mensagem.className = `mb-3 text-center text-${type}`;
}

esqueciForm.addEventListener('submit', function(e){
    e.preventDefault();

    const emailInput = email.value.trim();
    const newSenha = novaSenha.value;
    const confirmSenhaInput = confirmSenha.value;

    if(!emailInput || !newSenha || !confirmSenhaInput){
        mostrarMsg('Preencha todos os campos', 'danger');
        return;
    }

    if(newSenha !== confirmSenhaInput){
        mostrarMsg('A senha deve ser a mesma nos 2 campos', 'danger');
        return;
    }

    const index = usuarioEmailIndex(emailInput);
    if(index === -1){
        mostrarMsg('Email não encontrado', 'danger');
        return;
    }

    const usuarios = buscarUsuario();
    usuarios[index].senha = newSenha;
    salvarUsuario(usuarios);

    mostrarMsg('Senha atualizada! Redirecionando para o login...', 'success');

    setTimeout(() => {
        window.location.href = '/res-login.html';
    }, 1500);
});