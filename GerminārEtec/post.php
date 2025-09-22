<?php
session_start();
require 'conexao_banco.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_post = mysqli_real_escape_string($conexao_banco, $_GET['id']);
    
    $sql_post = "SELECT 
                    p.titulo_post, 
                    p.conteudo,
                    u.nome_usuario,
                    m.nome_materia
                 FROM post p
                 INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                 LEFT JOIN postagem_materias pm ON p.id_post = pm.id_post
                 LEFT JOIN materias m ON pm.id_materia = m.id_materia
                 WHERE p.id_post = '$id_post'";
                 
    $resultado_post = mysqli_query($conexao_banco, $sql_post);
    
    if ($resultado_post && mysqli_num_rows($resultado_post) > 0) {
        $linha_post = mysqli_fetch_assoc($resultado_post);
        
        $titulo_post = htmlspecialchars($linha_post['titulo_post']);
        $conteudo_post = nl2br(htmlspecialchars($linha_post['conteudo']));
        $nome_usuario = htmlspecialchars($linha_post['nome_usuario']);
        $nome_materia = htmlspecialchars($linha_post['nome_materia']);

    } else {
        echo "<h1>Postagem não encontrada.</h1>";
        exit;
    }

} else {
    header("Location: pagina_principal.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_post; ?></title>
</head>
<body>
    <div class="container">
        <h1><?php echo $titulo_post; ?></h1>
        <div class="post-meta">
            <span>Por: <?php echo $nome_usuario; ?></span>
            <?php if (!empty($nome_materia)): ?>
                <span class="materia"> | Matéria: <?php echo $nome_materia; ?></span>
            <?php endif; ?>
        </div>
        <div class="post-content">
            <p><?php echo $conteudo_post; ?></p>
        </div>
        <hr>
        <a href="pagina_principal.php">Voltar para a página principal</a>
    </div>
</body>
</html>
<?php
mysqli_close($conexao_banco);
?>