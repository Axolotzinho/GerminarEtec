<?php
session_start();
require 'conexao_banco.php';

$id_materia = isset($_GET['materia']) && is_numeric($_GET['materia']) ? $_GET['materia'] : null;

$ordenacao = isset($_GET['ordenacao']) ? $_GET['ordenacao'] : 'recente';
$order_by = ($ordenacao == 'recente') ? 'DESC' : 'ASC';

$aba_ativa = isset($_GET['aba']) ? $_GET['aba'] : 'abaperguntas';

$sql_materias = "SELECT id_materia, nome_materia FROM materias ORDER BY nome_materia ASC";
$resultado_materias = mysqli_query($conexao_banco, $sql_materias);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página principal</title>
    <style>
        body {
            max-width: 1200px;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        #conteudo-abas {
            word-wrap: break-word;
            max-width: 700px;
        }
        #abas {
            display: flex;
            margin-bottom: 20px;
        }
        #container-filtros {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        #btn-aplicar-filtros {
            padding: 8px 12px;
            border: none;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        .top-right {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .top-right a {
            text-decoration: none;
            color: #fff;
            background-color: #dc3545;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .top-right a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="top-right">
        <a href="logout.php">Sair</a>
    </div>

    <div id="abas">
        <button id="perguntas">Perguntas</button>
        <button id="postagens">Postagens</button>
    </div>

    <div id="container-filtros">
        <form action="" method="get" id="form-filtro">
            <input type="hidden" name="aba" value="<?php echo htmlspecialchars($aba_ativa); ?>">
            
            <label for="filtro-materia">Filtrar por Matéria:</label>
            <select name="materia" id="filtro-materia">
                <option value="">Todas</option>
                <?php
                if (mysqli_num_rows($resultado_materias) > 0) {
                    while ($linha_materia = mysqli_fetch_assoc($resultado_materias)) {
                        $selected = ($linha_materia['id_materia'] == $id_materia) ? 'selected' : '';
                        echo "<option value='" . $linha_materia['id_materia'] . "' " . $selected . ">" . htmlspecialchars($linha_materia['nome_materia']) . "</option>";
                    }
                }
                ?>
            </select>
            
            <label for="ordenacao">Ordenar por:</label>
            <select name="ordenacao" id="ordenacao">
                <option value="recente" <?php echo ($ordenacao == 'recente') ? 'selected' : ''; ?>>Mais recentes</option>
                <option value="antigo" <?php echo ($ordenacao == 'antigo') ? 'selected' : ''; ?>>Mais antigos</option>
            </select>

            <button type="submit" id="btn-aplicar-filtros">Aplicar</button>
        </form>
    </div>
    
    <div id="conteudo-abas">
        <div id="abaperguntas" style="display: <?php echo ($aba_ativa == 'abaperguntas' ? 'block' : 'none'); ?>;">
            <h2>Perguntas</h2>
            <?php
            $limite_caracteres = 200;

            $sql = "SELECT p.id_pergunta, p.titulo_pergunta, p.pergunta, u.nome_usuario
                    FROM pergunta p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario";

            if ($id_materia) {
                $sql .= " INNER JOIN pergunta_materias pm ON p.id_pergunta = pm.id_pergunta WHERE pm.id_materia = '$id_materia'";
            }

            $sql .= " ORDER BY p.data_pergunta " . $order_by . " LIMIT 10";

            $resultado = mysqli_query($conexao_banco, $sql);

            if (mysqli_num_rows($resultado) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    $corpo_pergunta = htmlspecialchars($linha['pergunta']);
                    if (strlen($corpo_pergunta) > $limite_caracteres) {
                        $corpo_pergunta = substr($corpo_pergunta, 0, $limite_caracteres) . '...';
                    }

                    echo "<div>";
                    echo "<h3>" . htmlspecialchars($linha['titulo_pergunta']) . "</h3>";
                    echo "<p>" . $corpo_pergunta . "</p>";
                    echo "<p>Por: " . htmlspecialchars($linha['nome_usuario']) . "</p>";
                    echo "<p><a href='pergunta.php?id=" . htmlspecialchars($linha['id_pergunta']) . "'>Ver pergunta completa</a></p>";
                    echo "</div>";
                    echo "<hr>";
                }
            } else {
                echo "<p>Nenhuma pergunta foi encontrada ainda. Seja o primeiro a perguntar!</p>";
            }

            ?>
        </div>
        
        <div id="abapostagens" style="display: <?php echo ($aba_ativa == 'abapostagens' ? 'block' : 'none'); ?>;">
            <h2>Postagens</h2>
            <?php
            $limite_caracteres = 200;

            $sql = "SELECT p.id_post, p.titulo_post, p.conteudo, u.nome_usuario
                    FROM post p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario";
            
            if ($id_materia) {
                $sql .= " INNER JOIN postagem_materias pm ON p.id_post = pm.id_post WHERE pm.id_materia = '$id_materia'";
            }

            $sql .= " ORDER BY p.data_post " . $order_by . " LIMIT 10";

            $resultado = mysqli_query($conexao_banco, $sql);

            if (mysqli_num_rows($resultado) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    $corpo_post = htmlspecialchars($linha['conteudo']);
                    if (strlen($corpo_post) > $limite_caracteres) {
                        $corpo_post = substr($corpo_post, 0, $limite_caracteres) . '...';
                    }

                    echo "<div>";
                    echo "<h3>" . htmlspecialchars($linha['titulo_post']) . "</h3>";
                    echo "<p>" . $corpo_post . "</p>";
                    echo "<p>Por: " . htmlspecialchars($linha['nome_usuario']) . "</p>";
                    echo "<p><a href='post.php?id=" . htmlspecialchars($linha['id_post']) . "'>Ver post completo</a></p>";
                    echo "</div>";
                    echo "<hr>";
                }
            } else {
                echo "<p>Nenhuma postagem foi encontrada ainda. Seja o primeiro a postar!</p>";
            }
            mysqli_close($conexao_banco);
            ?>
        </div>
    </div>
    
    <div class="container-menu">
        <button id="menu">+</button>
        <div class="opcoes" style="display: none;">
            <a href="fazer_pergunta.php">Fazer pergunta</a>
            <a href="fazer_postagem.php">fazer postagem</a>
        </div>
    </div>
    <script src="java.js"></script>
</body>
</html>