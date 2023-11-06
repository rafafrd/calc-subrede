<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NerdFix - Conversor CIDR</title>
  <link rel="icon" href="./public/img/favicon.png" type="image/x-icon">
  <script src="./src/script/main.js"></script>
  <link rel="stylesheet" href="./public/style/style.css">
  <link rel="stylesheet" href="./public/style/header.css">
  <link rel="stylesheet" href="./public/style/footer.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <nav class="nav">
      <img src="./public/img/Logo.png" class="logo" alt="Logo Nerdfix" />
      <div>
        <input type="checkbox" id="menu" >
        <label for="menu" class="label-menu">
          <img src="./public/img/menu.svg" alt="Menu" class="menu-img" />
        </label>
        <ul class="nav-items">
          <a href="./src/pages/index.html" class="nav-item">Início</a>
          <a href="./src/pages/classe.html" class="nav-item">Descobrir Classe do IP</a>
          <a href="./src/pages/cidr.html" class="nav-item">Converter CIDR</a>
        </ul>
      </div>
    </nav>
  </header>
  <main>

    <!-- TODO: DEIXAR AS CAIXAS UMA DO LADO DA OUTRA, MANTER O TITULO CENTRALIZADO ENTRE ELAS -->

    <form method="POST" action="" onsubmit="return validarCidr(document.getElementById('cidr').value);">
      <div class="input-container">
        <h1 class="title">Conversor CIDR</h1>
        <label for="cidr" class="label">Digite a notação CIDR:</label>
        <input type="text" id="cidr" name="cidr" placeholder="Exemplo: 16" class="input-ip">
      </div>
      <input type="submit" name="submitCidr" value="Converter para IPv4" class="button">
    </form>

    <form method="POST" action="" onsubmit="return validarMascara(document.getElementById('ipv4').value);">
      <div class="input-container">
        <label for="ipv4" class="label">Digite o endereço IPv4:</label>
        <input type="text" id="ipv4" name="ipv4" placeholder="por exemplo: 255.255.0.0" class="input-ip">
      </div>
      <input type="submit" name="submitIpv4" value="Converter para CIDR" class="button">
    </form>                                               

    <div class="result" id="result">

      <div id="mensagemErroCidr" class="mensagemErro">Notação CIDR inválida!</div>

      <div id="mensagemErroMascara" class="mensagemErro">Máscara IP inválida!</div>
    
      <!-- TODO: ESTILIZAR O RESULTADO, DEIXAR MAIOR, E CENTRALIZAR NA ALTURA -->
      <?php
            function ipv4ParaBinario($ipv4) {
                $octetos = explode('.', $ipv4);
                $binarioOctetos = [];

                foreach ($octetos as $octeto) {
                    $binarioOctetos[] = str_pad(decbin($octeto), 8, '0', STR_PAD_LEFT);
                }

                return implode('.', $binarioOctetos);
            }

            function cidrParaIpv4($cidr) {
                $ipBits = array();
                $ipDecimal = array();

                for ($i = 0; $i < $cidr; $i++) {
                    if ($i % 8 == 0) {
                        // Adicione um novo item na matriz quando o índice for múltiplo de 8
                        $ipBits[] = '1';
                    } else {
                        // Adicione "1" à posição existente
                        $ipBits[count($ipBits) - 1] .= '1';
                    }
                }

                # verifica se o array tem 4 itens e adiciona e não tiver
                while (sizeof($ipBits) < 4) {
                    array_push($ipBits, '');
                }

                # verifica se o item do array tem 8 caracteres e adiciona o 0 caso não houver
                for ($i = 0; $i < count($ipBits); $i++) {
                    while (strlen($ipBits[$i]) < 8) {
                        $ipBits[$i] .= "0";
                    }

                    # faz a conversão para inteiro depois para decimal
                    $inteiro = intval($ipBits[$i]);
                    $decimal = bindec($inteiro);
                    $ipDecimal[$i] = $decimal;
                }

                $cidrNotacao = "/$cidr";

                echo "<br>Máscara em bits: $ipBits[0].$ipBits[1].$ipBits[2].$ipBits[3]";
                echo "<br>Máscara em IPv4: $ipDecimal[0].$ipDecimal[1].$ipDecimal[2].$ipDecimal[3]";
                echo "<br>Notação CIDR: $cidrNotacao";
            }

            function ipv4ParaCidr($ipv4) {
                $binario = ipv4ParaBinario($ipv4);

                $contagem = substr_count($binario, '1');

                echo "<br>Máscara em Bits: $binario";
                echo "<br>Máscara em Ipv4: $ipv4";
                echo "<br>Notação CIDR: /$contagem";
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST["submitCidr"]) && isset($_POST["cidr"])) {
                    $cidr = $_POST["cidr"];
                    cidrParaIpv4($cidr);
                } elseif (isset($_POST["submitIpv4"]) && isset($_POST["ipv4"])) {
                    $ipv4 = $_POST["ipv4"];
                    ipv4ParaCidr($ipv4);
                } elseif (isset($_POST["reset"])) {
                    // O botão "Limpar" foi pressionado, não faz nada
                }
            }

            if (isset($_POST["reset"])) {
                // O botão "Limpar" foi pressionado, então você pode limpar os resultados
                echo "<script>location.href = location.pathname;</script>"; // Redireciona para a mesma página para limpar os resultados em PHP
            }

        ?>
    </div>

    <div class="explicacao">
        <h2>O que é Notação CIDR?</h2>
        <p>A notação CIDR (Classless Inter-Domain Routing) é um sistema de notação que permite especificar blocos de endereços IP e suas máscaras de sub-rede de 
            forma mais flexível do que a notação de máscara de sub-rede tradicional. Ela é usada para definir sub-redes e alocar endereços IP de maneira eficiente. 
            <br><br>A notação CIDR inclui o endereço IP e o número de bits na máscara de sub-rede, separados por uma barra ("/"). 
            Por exemplo, "192.168.1.0/24" indica que os primeiros 24 bits do endereço IP são a parte da rede, e os 8 bits restantes são a parte do host. 
            Isso simplifica o gerenciamento de endereços IP em redes, permitindo a alocação mais precisa de sub-redes e endereços.</p>
    </div>

  </main>
  <footer>
    <img src="./public/img/github-original.svg" alt="github" />
    <p>Copyright © 2023 Caio Franson - Rafael Augusto</p>
  </footer>
</body>
</html>