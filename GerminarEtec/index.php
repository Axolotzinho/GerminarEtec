<?php
require "conexao_banco.php";
session_start();
$mensagem_de_erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = mysqli_real_escape_string($conexao_banco, $_POST["nome_aluno"]);
    $email = mysqli_real_escape_string($conexao_banco, $_POST["email"]);
    $senha = $_POST["senha"];
    $hash_senha = password_hash($senha, PASSWORD_DEFAULT);

    $sql_verifica_email = "SELECT id_usuario FROM usuarios WHERE email = '$email'";
    $resultado_verifica_email = mysqli_query($conexao_banco, $sql_verifica_email);
    
    $sql_verifica_usuario = "SELECT id_usuario FROM usuarios WHERE nome_usuario = '$usuario'";
    $resultado_verifica_usuario = mysqli_query($conexao_banco, $sql_verifica_usuario);
    

    if (mysqli_num_rows($resultado_verifica_email) > 0) {
        $mensagem_de_erro = "Este e-mail já está cadastrado.";
    } elseif (mysqli_num_rows($resultado_verifica_usuario) > 0) {
        $mensagem_de_erro = "Já existe um usuário com o nome **$usuario**. Por favor, escolha outro nome.";
    } else {
        $sql = "INSERT INTO usuarios (nome_usuario, email, senha)";
        $sql.= "VALUES ('$usuario', '$email', '$hash_senha')";
        
        if (mysqli_query($conexao_banco, $sql)) {
            $id_novo_usuario = mysqli_insert_id($conexao_banco);
            
            $_SESSION['loggedin'] = true;
            $_SESSION['id_usuario'] = $id_novo_usuario;
            $_SESSION['nome_usuario'] = $usuario; 

            header("Location: pagina_principal.php");
            exit;
        } else {
            $mensagem_de_erro = "Erro interno ao cadastrar: " . mysqli_error($conexao_banco);
        }
    }
    mysqli_close($conexao_banco);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <style>
        body {
            background-color: #F5EE9D;
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
        }

        header, footer {
            background-color: #F1B461;
            padding: 20px;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
            border-bottom: 2px solid #724B2C;
            border-top: 2px solid #724B2C;
        }
        
        header {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        footer {
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }

        .container {
            background-color: #F1B461;
            width: 500px;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            border-radius: 20px;
            border: 2px solid #724B2C;
            margin: auto;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            display: none;
        }

        table {
            border-collapse: collapse;
        }
        td {
            padding: 8px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            border-radius: 20px;
            padding: 10px;
            border: 2px solid #724B2C;
        }

        input[type="submit"] {
            border-radius: 20px;
            padding: 10px 20px;
            border: 2px solid #724B2C;
            cursor: pointer;
            background-color: #FFA06A;
            color: #724B2C;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        
        input[type="submit"]:hover {
            background-color: #FF905A;
        }
        
        .container p a {
            color: #724B2C;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .container p a:hover {
            color: #FF905A;
        }

    </style>
</head>
<body>
    <header>
        <strong>GerminarEtec</strong>
    </header>

    <div class="container">
        <h1>Cadastro</h1>

        <?php if (!empty($mensagem_de_erro)): ?>
            <p style="color: #A00000; font-weight: bold;"><?php echo $mensagem_de_erro; ?></p>
        <?php endif; ?>

        <form name="Aluno" method="post" action="">
            <table>
                <tr>
                    <td><label for="nome_aluno">Nome do aluno:</label></td>
                    <td><input type="text" id="nome_aluno" name="nome_aluno" autocomplete="off" placeholder="NOME DO ALUNO" size="30" maxlength="50" required></td>
                </tr>
                <tr>
                    <td><label for="email">E-mail:</label></td>
                    <td><input type="email" id="email" name="email" autocomplete="off" placeholder="E-MAIL" size="30" maxlength="50" required></td>
                </tr>
                <tr>
                    <td><label for="senha">Senha:</label></td>
                    <td><input type="password" id="senha" name="senha" autocomplete="off" placeholder="SENHA" size="30" maxlength="20" required></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="cadastrar" value="CADASTRAR-SE"></td>
                </tr>
            </table>
        </form>
        <p>Já possui uma conta? <a href="login.php">Clique aqui</a></p>
    </div>

    <footer>
         &copy; <?php echo date("Y"); ?> GerminarEtec. Todos os direitos reservados.
        <br>
        germinaretec@gmail.com
        <br>
    </footer>
</body>
</html>