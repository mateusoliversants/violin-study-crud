const form = document.getElementById("cadastroForm");

form.addEventListener("submit", function(e){
    e.preventDefault();

    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const senha = document.getElementById("senha").value;

    const usuario = {
        id: Date.now(),
        name: name,
        email: email,
        senha: senha
    };

    let users = JSON.parse(localStorage.getItem("users")) || [];

    users.push(usuario);

    localStorage.setItem("users", JSON.stringify(users));

    window.location.href = "/res-login.html";

    console.log("Usuário salvo!");
});
