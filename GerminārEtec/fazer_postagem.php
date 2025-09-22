<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    require "conexao_banco.php";

    $titulo_post = mysqli_real_escape_string($conexao_banco, $_POST['titulo_post']);
    $conteudo_post = mysqli_real_escape_string($conexao_banco, $_POST['conteudo_post']);
    
    $id_materia = mysqli_real_escape_string($conexao_banco, $_POST['materia']);
    
    $id_usuario = $_SESSION['id_usuario'];
    $sql = "INSERT INTO post (id_usuario, titulo_post, conteudo) VALUES ('$id_usuario', '$titulo_post', '$conteudo_post')";
    
    if (mysqli_query($conexao_banco, $sql)) {
        
        $id_post = mysqli_insert_id($conexao_banco);
        
        $sql_ligacao = "INSERT INTO postagem_materias (id_post, id_materia) VALUES ('$id_post', '$id_materia')";
        
        if (mysqli_query($conexao_banco, $sql_ligacao)) {
            header("Location: pagina_principal.php");
            exit;
        } else {
            echo "Erro ao vincular a matéria à postagem: " . mysqli_error($conexao_banco);
        }

    } else {
        echo "Erro: " . $sql . "<br>" . mysqli_error($conexao_banco);
    }

    mysqli_close($conexao_banco);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar post</title>
</head>
<body>
<div class="container">
    <p class="text-center text-primary">Fazer postagem</p>
    <form name="postagens" method="post" action="">
    <table>
        <tr>
            <td><Label>Título da postagem:</Label></td>
            <td><input type="text" name="titulo_post" autocomplete="off" size="50" required></td>
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
            <td><label>Corpo da postagem:</label></td>
            <td><textarea name="conteudo_post" autocomplete="off" rows="10" cols="50" required></textarea></td>
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
    
</body>
</html>