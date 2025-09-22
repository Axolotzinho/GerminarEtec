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
</head>
<body>
<div class="container">
    <p class="text-center text-primary">Fazer pergunta</p>
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
                        require "conexao_banco.php";
                        $sql_materias = "SELECT id_materia, nome_materia FROM materias ORDER BY nome_materia ASC";
                        $resultado_materias = mysqli_query($conexao_banco, $sql_materias);
                        if (mysqli_num_rows($resultado_materias) > 0) {
                            while ($materia = mysqli_fetch_assoc($resultado_materias)) {
                                echo "<option value='" . htmlspecialchars($materia['id_materia']) . "'>" . htmlspecialchars($materia['nome_materia']) . "</option>";
                            }
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
</body>
</html>