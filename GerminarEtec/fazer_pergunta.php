<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    require "conexao_banco.php";
    
    $titulo_pergunta = mysqli_real_escape_string($conexao_banco, $_POST['titulo_pergunta']);
    $corpo_pergunta = mysqli_real_escape_string($conexao_banco, $_POST['corpo_pergunta']);
    $id_materia = mysqli_real_escape_string($conexao_banco, $_POST['materia']);
    $id_usuario = $_SESSION['id_usuario'];
    
    $sql_pergunta = "INSERT INTO pergunta (id_usuario, titulo_pergunta, pergunta) VALUES ('$id_usuario', '$titulo_pergunta', '$corpo_pergunta')";
    
    if (mysqli_query($conexao_banco, $sql_pergunta)) {
        
        $id_pergunta = mysqli_insert_id($conexao_banco);
        
        $sql_pergunta_materias = "INSERT INTO pergunta_materias (id_pergunta, id_materia) VALUES ('$id_pergunta', '$id_materia')";

        if (mysqli_query($conexao_banco, $sql_pergunta_materias)) {
            header("Location: pagina_principal.php");
            exit;
        } else {
            echo "Erro ao vincular a matéria à pergunta: " . mysqli_error($conexao_banco);
        }
        
    } else {
        echo "Erro ao criar a pergunta: " . mysqli_error($conexao_banco);
    }

    mysqli_close($conexao_banco);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fazer pergunta</title>
    <style>
        body {
            background-color: #F5EE9D;
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
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

        .main-content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 50px 20px;
        }

        .container {
            word-wrap: break-word;
            max-width: 700px;
            width: 90%;
            background-color: #F1B461;
            padding: 30px;
            border-radius: 20px;
            border: 2px solid #724B2C;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        
        .container p {
            font-size: 1.5em;
            font-weight: bold;
            color: #724B2C;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table td {
            padding: 8px 0;
            color: #724B2C;
            font-weight: bold;
            vertical-align: top;
        }

        input[type="text"], 
        textarea, 
        select {
            width: 95%; 
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #724B2C;
            border-radius: 5px;
            box-sizing: border-box; 
            background-color: #FFFFFF;
            font-size: 1em;
        }
        
        textarea {
            resize: vertical; 
        }
        
        input[type="submit"], 
        input[type="reset"] {
            padding: 10px 20px;
            border: 2px solid #724B2C;
            border-radius: 20px;
            background-color: #FFA06A;
            color: #724B2C;
            font-weight: bold;
            cursor: pointer;
            margin: 10px 5px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover, 
        input[type="reset"]:hover {
            background-color: #FF905A;
        }

        .container a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #724B2C;
            font-weight: bold;
            padding: 10px;
            border: 2px solid #724B2C;
            border-radius: 20px;
            background-color: #FFA06A;
            width: 150px;
            margin-left: auto;
            margin-right: auto;
            transition: background-color 0.3s ease;
        }
        
        .container a:hover {
            background-color: #FF905A;
        }
        
    </style>
</head>
<body>
    <header>
        <strong>GerminarEtec</strong>
    </header>

    <div class="main-content">
        <div class="container">
            <p class="text-primary">Fazer pergunta</p>
            <form name="pergunta" method="post" action="">
            <table>
                <tr>
                    <td><Label>Título da pergunta:</Label></td>
                    <td><input type="text" name="titulo_pergunta" autocomplete="off" size="50" required></td>
                </tr>
                <tr>
                    <td><Label>Matéria:</Label></td>
                    <td>
                        <select name="materia" required>
                            <option value="">Selecione a matéria</option>
                            <?php
                                if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                                    require "conexao_banco.php";
                                }
                                
                                $sql_materias = "SELECT id_materia, nome_materia FROM materias ORDER BY nome_materia ASC";
                                if (isset($conexao_banco)) {
                                    $resultado_materias = mysqli_query($conexao_banco, $sql_materias);
                                    if ($resultado_materias && mysqli_num_rows($resultado_materias) > 0) {
                                        while ($materia = mysqli_fetch_assoc($resultado_materias)) {
                                            echo "<option value='" . htmlspecialchars($materia['id_materia']) . "'>" . htmlspecialchars($materia['nome_materia']) . "</option>";
                                        }
                                    }
                                } else {
                                    echo "<option value='' disabled>Erro ao carregar matérias.</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label>Corpo da pergunta:</label></td>
                    <td><textarea name="corpo_pergunta" autocomplete="off" rows="10" cols="50" required></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                    <input type="submit" value="Enviar">
                    <input type="reset" value="Limpar">
                </td>
                </tr>
            </table>
            <a href="pagina_principal.php">Voltar</a>
            </form>
        </div>
    </div>
    
    <footer>
         &copy; <?php echo date("Y"); ?> GerminarEtec. Todos os direitos reservados.
        <br>
        germinaretec@gmail.com
        <br>
    </footer>
</body>
</html>