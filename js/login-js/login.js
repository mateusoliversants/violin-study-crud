document.getElementById("loginForm").addEventListener("submit", function(f){
    f.preventDefault();

    let loginEmail = document.getElementById("loginEmail").value;
    let loginSenha = document.getElementById("loginSenha").value;

    let users = JSON.parse(localStorage.getItem("users")) || [];

    let usuarioEncontrado = users.find(u => u.email === loginEmail && u.senha === loginSenha);

    if(usuarioEncontrado){
        alert("Login realizado!");
        localStorage.setItem("logado", JSON.stringify(usuarioEncontrado));

        window.location.href = "res-sessoes.html";
    }
    
    else{
        alert("Usuário ou senha incorretos")
    };
});
