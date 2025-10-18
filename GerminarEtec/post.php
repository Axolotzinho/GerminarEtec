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
        header("Location: pagina_principal.php");
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
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: calc(100vh - 120px); 
        }

        .container {
            max-width: 800px;
            width: 100%;
            padding: 30px;
            border: 2px solid #724B2C;
            border-radius: 20px;
            background-color: #FFF;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            margin: 20px 0;
            box-sizing: border-box;
        }
        
        h1 {
            color: #724B2C;
            border-bottom: 2px solid #F1B461;
            padding-bottom: 10px;
            margin-top: 0;
            word-wrap: break-word; 
        }

        .post-meta {
            font-size: 0.9em;
            color: #724B2C;
            margin-bottom: 20px;
            text-align: right;
            border-bottom: 1px dashed #F1B461;
            padding-bottom: 10px;
        }
        
        .post-meta span {
            font-weight: bold;
        }
        
        .post-meta .materia {
            font-weight: normal;
        }

        .post-content {
            line-height: 1.7;
            color: #333;
            margin-bottom: 30px;
        }

        .post-content p {
            white-space: pre-wrap;
            word-wrap: break-word; 
        }

        hr {
            border: 0;
            height: 1px;
            background-color: #724B2C;
            margin: 30px 0;
        }

        .main-content a {
            display: inline-block;
            text-decoration: none;
            color: #724B2C;
            font-weight: bold;
            padding: 10px 20px;
            border: 2px solid #724B2C;
            border-radius: 20px;
            background-color: #FFA06A;
            transition: background-color 0.3s ease;
        }
        
        .main-content a:hover {
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
            <h1><?php echo $titulo_post; ?></h1>
            <div class="post-meta">
                <span>Por: <?php echo $nome_usuario; ?></span>
                <?php 
                if (!empty($linha_post['nome_materia'])): 
                ?>
                    <span class="materia"> | Matéria: <strong><?php echo $nome_materia; ?></strong></span>
                <?php endif; ?>
            </div>
            <div class="post-content">
                <p><?php echo $conteudo_post; ?></p>
            </div>
            <hr>
            <a href="pagina_principal.php">Voltar para a página principal</a>
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
<?php
mysqli_close($conexao_banco);
?>