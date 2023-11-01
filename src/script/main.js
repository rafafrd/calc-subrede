// Função para exibir mensagem de erro e aplicar classe "shake"
function exibirErro(divErro, valido) {
    if (valido == true) {
        divErro.style.display = "none";
        divErro.classList.remove("shake");
    } else {
        divErro.style.display = "block";
        divErro.classList.add("shake");
    }
}

function validarIp(enderecoIp) {
    var divErro = document.getElementById("mensagemErro");
    var valido = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(enderecoIp);
    exibirErro(divErro, valido);
    return valido;
}

function validarMascara(mascara) {
    var divErro = document.getElementById("mensagemErroMascara");
    var valido = /^(255)\.(0|128|192|224|240|248|252|254|255)\.(0|128|192|224|240|248|252|254|255)\.(0|128|192|224|240|248|252|254|255)$/.test(mascara);
    
    if (valido == true) {
        var pedacos = mascara.split(".");
        var binario = pedacos.map(function (octeto) {
            return parseInt(octeto).toString(2).padStart(8, "0");
        }).join(".");

        valido = /^1*0*1*$/.test(binario.replace(/\./g, ""));
    }

    exibirErro(divErro, valido);
    return valido;
}

function validarCidr(cidr) {
    var divErro = document.getElementById("mensagemErroCidr");
    var valido = cidr >= 1 && cidr <= 30;
    exibirErro(divErro, valido);
    return valido;
}