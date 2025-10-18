<?php
session_start();
require 'conexao_banco.php';

$id_materia = isset($_GET['materia']) && is_numeric($_GET['materia']) ? $_GET['materia'] : null;

$ordenacao = isset($_GET['ordenacao']) ? $_GET['ordenacao'] : 'recente';
$order_by = ($ordenacao == 'recente') ? 'DESC' : 'ASC';

$aba_ativa = isset($_GET['aba']) ? $_GET['aba'] : 'abaperguntas';

$termo_busca = isset($_GET['busca']) ? mysqli_real_escape_string($conexao_banco, $_GET['busca']) : '';

$limite = 5;
$offset_inicial = 0; 

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
            background-color: #F5EE9D;
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden; 
        }
        
        hr {
            display: none !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            height: 0 !important;
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
            padding-top: 0;
            margin-top: 0;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        .content-wrapper {
            display: flex;
            flex-direction: column;
        }
        .content-and-image {
            display: flex;
            gap: 20px;
            align-items: flex-start; 
        }


        .btn-aba  {
            text-decoration: none;
            color: #724B2C;
            background-color: #FFA06A;
            padding: 8px 12px;
            border-radius: 5px;
            border: 2px solid #724B2C;
            font-size: 14px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        .btn-aba:hover, .btn-ver-mais:hover {
            background-color: #FF905A;
        }
        
        .btn-ver-mais {
            display: inline-block;
            margin-top: 20px;
        }

        #conteudo-abas {
            word-wrap: break-word;
            width: 700px;
            background-color: #F1B461;
            padding: 20px;
            border-radius: 20px;
            border: 2px solid #724B2C;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 5px;
            flex-shrink: 0;
        }

        .item-caixa-branca {
            background-color: #FFFFFF;
            border: 1px solid #724B2C;
            border-radius: 10px;
            width: 700px;
            min-height: 170px;
            padding: 5px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }
        
        .item-caixa-branca + hr {
            display: none;
        }

        #abas {
            display: flex;
            margin-bottom: 20px;
            gap: 10px;
            align-items: center;
        }
        #form-busca {
            display: flex;
            gap: 5px;
            margin-left: auto; 
            max-width: 400px;
        }
        #campo-busca {
            padding: 8px 10px;
            border: 2px solid #724B2C;
            border-radius: 5px;
            font-size: 14px;
            flex-grow: 1;
            width: 400px; 
        }
        #btn-buscar {
            padding: 8px 12px;
            border: 2px solid #724B2C;
            background-color: #FFA06A;
            color: #724B2C;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        #btn-buscar:hover {
            background-color: #FF905A;
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
            background-color: #FFA06A;
            color: #724B2C;
            cursor: pointer;
            border-radius: 20px;
            border: 2px solid #724B2C;
        }
        #btn-aplicar-filtros:hover {
            background-color: #FF905A;
        }

        .top-right {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .top-right a {
            text-decoration: none;
            color: #724B2C;
            background-color: #FFA06A;
            padding: 8px 12px;
            border-radius: 20px;
            border: 2px solid #724B2C;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .top-right a:hover {
            background-color: #FF905A;
        }

        .container-menu {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .container-menu button {
            background-color: #FFA06A;
            color: #724B2C;
            border: 2px solid #724B2C;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-bottom: 30px;
            font-size: 2em;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }
        .container-menu button:hover {
            background-color: #FF905A;
        }
        
        .opcoes {
            position: absolute;
            bottom: 60px;
            right: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background-color: #F1B461; 
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 10px;
            border: 2px solid #724B2C;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 999;
        }
        .opcoes a {
            display: flex;
            text-decoration: none;
            color: #724B2C;
            background-color: #FFA06A;
            padding: 5px;
            border-radius: 10px;
            border: 2px solid #724B2C;
            text-align: center;
            white-space: nowrap;
            transition: background-color 0.3s ease;
        }
        .opcoes a:hover {
            background-color: #FF905A;
        }

        .info-autor-materia {
            font-size: 0.9em;
            color: #555;
            margin-top: 5px;
        }

        .video-container {
            flex-shrink: 0;
            max-width: 500px; 
            height: auto;
            position: relative; 
            overflow: hidden; 
            background-color: #F1B461; 
            border-radius: 20px;
            border: 2px solid #724B2C;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .video-container video {
            width: 100%; 
            height: 100%; 
            display: block;
            object-fit: cover; 
            border-radius: 18px;
        }
        
    </style>
</head>
<body>
    <header>
        <strong>GerminarEtec</strong>
    </header>

    <div class="main-content">
        <div class="top-right">
            <a href="logout.php">Sair</a>
        </div>
        
        <div class="content-wrapper">
            <div id="abas">
                <button id="perguntas" class="btn-aba">Perguntas</button>
                <button id="postagens" class="btn-aba">Postagens</button>
                
                <form action="" method="get" id="form-busca">
                    <input type="hidden" name="aba" value="<?php echo htmlspecialchars($aba_ativa); ?>">
                    <input type="hidden" name="materia" value="<?php echo htmlspecialchars($id_materia); ?>">
                    <input type="hidden" name="ordenacao" value="<?php echo htmlspecialchars($ordenacao); ?>">
                    
                    <input type="text" name="busca" id="campo-busca" placeholder="Pesquisar por título ou conteúdo..." 
                           value="<?php echo htmlspecialchars(stripslashes($termo_busca)); ?>">
                    <button type="submit" id="btn-buscar">Buscar</button>
                </form>
                </div>
            
            <div id="container-filtros">
                <form action="" method="get" id="form-filtro">
                    <input type="hidden" name="aba" value="<?php echo htmlspecialchars($aba_ativa); ?>"> 
                    <input type="hidden" name="busca" value="<?php echo htmlspecialchars(stripslashes($termo_busca)); ?>">
                    
                    <label for="filtro-materia">Filtrar por Matéria:</label>
                    <select name="materia" id="filtro-materia">
                        <option value="">Todas</option>
                        <?php
                        if (mysqli_num_rows($resultado_materias) > 0) {
                            mysqli_data_seek($resultado_materias, 0); 
                            while ($linha_materia = mysqli_fetch_assoc($resultado_materias)) {
                                $selected = ($linha_materia['id_materia'] == $id_materia) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($linha_materia['id_materia']) . "' " . $selected . ">" . htmlspecialchars($linha_materia['nome_materia']) . "</option>";
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
            
            <div class="content-and-image">
                <div id="conteudo-abas">
                    
                    <div id="abaperguntas" style="display: <?php echo ($aba_ativa == 'abaperguntas' ? 'block' : 'none'); ?>;">
                        <h2>Perguntas</h2>
                        
                        <div id="lista-perguntas">
                        <?php
                        $limite_caracteres = 100;

                        $sql = "SELECT p.id_pergunta, p.titulo_pergunta, p.pergunta, u.nome_usuario, m.nome_materia
                                FROM pergunta p
                                INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                                LEFT JOIN pergunta_materias pm ON p.id_pergunta = pm.id_pergunta 
                                LEFT JOIN materias m ON pm.id_materia = m.id_materia";

                        $condicoes = [];

                        if ($id_materia) {
                            $condicoes[] = "pm.id_materia = '" . mysqli_real_escape_string($conexao_banco, $id_materia) . "'";
                        }
                        if ($termo_busca) {
                            $condicoes[] = "(p.titulo_pergunta LIKE '%" . $termo_busca . "%' OR p.pergunta LIKE '%" . $termo_busca . "%')";
                        }
                        
                        if (!empty($condicoes)) {
                            $sql .= " WHERE " . implode(' AND ', $condicoes);
                        }

                        $sql .= " GROUP BY p.id_pergunta, p.titulo_pergunta, p.pergunta, u.nome_usuario 
                                  ORDER BY p.data_pergunta " . $order_by . " LIMIT " . $limite . " OFFSET " . $offset_inicial;

                        $resultado = mysqli_query($conexao_banco, $sql);
                        $resultados_encontrados_perguntas = mysqli_num_rows($resultado);

                        if ($resultados_encontrados_perguntas > 0) {
                            while ($linha = mysqli_fetch_assoc($resultado)) {
                                $corpo_pergunta = htmlspecialchars($linha['pergunta']);
                                if (strlen($corpo_pergunta) > $limite_caracteres) {
                                    $corpo_pergunta = substr($corpo_pergunta, 0, $limite_caracteres) . '...';
                                }
                                
                                $materia_nome = !empty($linha['nome_materia']) ? htmlspecialchars($linha['nome_materia']) : 'Sem Matéria';

                                echo "<div class='item-caixa-branca pergunta-item'>";
                                echo "<h3>" . htmlspecialchars($linha['titulo_pergunta']) . "</h3>";
                                echo "<p>" . $corpo_pergunta . "</p>";
                                echo "<p class='info-autor-materia'>Por: " . htmlspecialchars($linha['nome_usuario']) . " | Matéria: <strong>" . $materia_nome . "</strong></p>";
                                echo "<p><a href='pergunta.php?id=" . htmlspecialchars($linha['id_pergunta']) . "'>Ver pergunta completa</a></p>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>Nenhuma pergunta foi encontrada";
                            if ($termo_busca) {
                                echo " para o termo '<strong>" . htmlspecialchars(stripslashes($termo_busca)) . "</strong>'";
                            } else {
                                echo " ainda. Seja o primeiro a perguntar!";
                            }
                            echo "</p>";
                        }
                        ?>
                        </div> 
                        <?php
                        if ($resultados_encontrados_perguntas == $limite) {
                            $proximo_offset_perguntas = $limite; 
                            echo "<div style='text-align: center;'>";
                            echo "<button class='btn-aba btn-ver-mais' id='btn-ver-mais-perguntas' 
                                   data-aba='perguntas' data-offset='" . $proximo_offset_perguntas . "'>Ver Mais Perguntas</button>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                    
                    <div id="abapostagens" style="display: <?php echo ($aba_ativa == 'abapostagens' ? 'block' : 'none'); ?>;">
                        <h2>Postagens</h2>
                        
                        <div id="lista-postagens">
                        <?php
                        $limite_caracteres = 200;

                        $sql = "SELECT p.id_post, p.titulo_post, p.conteudo, u.nome_usuario, m.nome_materia
                                FROM post p
                                INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                                LEFT JOIN postagem_materias pm ON p.id_post = pm.id_post
                                LEFT JOIN materias m ON pm.id_materia = m.id_materia";
                        
                        $condicoes = [];
                        
                        if ($id_materia) {
                            $condicoes[] = "pm.id_materia = '" . mysqli_real_escape_string($conexao_banco, $id_materia) . "'";
                        }
                        if ($termo_busca) {
                            $condicoes[] = "(p.titulo_post LIKE '%" . $termo_busca . "%' OR p.conteudo LIKE '%" . $termo_busca . "%')";
                        }

                        if (!empty($condicoes)) {
                            $sql .= " WHERE " . implode(' AND ', $condicoes);
                        }

                        $sql .= " GROUP BY p.id_post, p.titulo_post, p.conteudo, u.nome_usuario 
                                  ORDER BY p.data_post " . $order_by . " LIMIT " . $limite . " OFFSET " . $offset_inicial;

                        $resultado = mysqli_query($conexao_banco, $sql);
                        $resultados_encontrados_postagens = mysqli_num_rows($resultado);

                        if ($resultados_encontrados_postagens > 0) {
                            while ($linha = mysqli_fetch_assoc($resultado)) {
                                $corpo_post = htmlspecialchars($linha['conteudo']);
                                if (strlen($corpo_post) > $limite_caracteres) {
                                    $corpo_post = substr($corpo_post, 0, $limite_caracteres) . '...';
                                }
                                
                                $materia_nome = !empty($linha['nome_materia']) ? htmlspecialchars($linha['nome_materia']) : 'Sem Matéria';

                                echo "<div class='item-caixa-branca post-item'>";
                                echo "<h3>" . htmlspecialchars($linha['titulo_post']) . "</h3>";
                                echo "<p>" . $corpo_post . "</p>";
                                echo "<p class='info-autor-materia'>Por: " . htmlspecialchars($linha['nome_usuario']) . " | Matéria: <strong>" . $materia_nome . "</strong></p>";
                                echo "<p><a href='post.php?id=" . htmlspecialchars($linha['id_post']) . "'>Ver post completo</a></p>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>Nenhuma postagem foi encontrada";
                             if ($termo_busca) {
                                echo " para o termo '<strong>" . htmlspecialchars(stripslashes($termo_busca)) . "</strong>'";
                            } else {
                                echo " ainda. Seja o primeiro a postar!";
                            }
                            echo "</p>";
                        }
                        ?>
                        </div> 
                        <?php
                        if ($resultados_encontrados_postagens == $limite) {
                            $proximo_offset_postagens = $limite; 
                            echo "<div style='text-align: center;'>";
                            echo "<button class='btn-aba btn-ver-mais' id='btn-ver-mais-postagens' 
                                   data-aba='postagens' data-offset='" . $proximo_offset_postagens . "'>Ver Mais Postagens</button>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
                
                <div class="video-container">
                    <video id="video-decorativo" autoplay loop muted playsinline>
                        <source src="imagens/ipe_amarelo.webm" type="video/webm">
                        Seu navegador não suporta a tag de vídeo.
                    </video>
                </div>
                </div>
            </div>
        </div>
    
    <div class="container-menu">
        <button id="menu">+</button>
        <div class="opcoes" style="display: none;">
            <a href="fazer_pergunta.php">Fazer pergunta</a>
            <a href="fazer_postagem.php">Fazer postagem</a>
        </div>
    </div>
    
    <footer>
        &copy; <?php echo date("Y"); ?> GerminarEtec. Todos os direitos reservados.
        <br>
        germinaretec@gmail.com
        <br>
    </footer>

    <?php 
    mysqli_close($conexao_banco); 
    ?>

    <script src="java.js"></script>
    <script src="ajax_loader.js"></script>
</body>
</html>