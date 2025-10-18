<?php
session_start();
require 'conexao_banco.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_pergunta = mysqli_real_escape_string($conexao_banco, $_GET['id']);
    
    $sql_pergunta = "SELECT p.titulo_pergunta, p.pergunta, u.nome_usuario, m.nome_materia
                     FROM pergunta p
                     INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                     LEFT JOIN pergunta_materias pm ON p.id_pergunta = pm.id_pergunta
                     LEFT JOIN materias m ON pm.id_materia = m.id_materia
                     WHERE p.id_pergunta = '$id_pergunta'
                     GROUP BY p.id_pergunta";
            
    $resultado_pergunta = mysqli_query($conexao_banco, $sql_pergunta);
    
    if (mysqli_num_rows($resultado_pergunta) > 0) {
        $linha_pergunta = mysqli_fetch_assoc($resultado_pergunta);
    } else {
        header("Location: pagina_principal.php");
        exit;
    }

} else {
    header("Location: pagina_principal.php");
    exit;
}

$sql_respostas = "SELECT r.resposta, r.data_resposta, u.nome_usuario 
                   FROM resposta r
                   INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                   WHERE r.id_pergunta = '$id_pergunta'
                   ORDER BY r.data_resposta ASC";
                   
$resultado_respostas = mysqli_query($conexao_banco, $sql_respostas);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pergunta</title>
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
            min-height: calc(100vh - 120px); 
        }

        .container {
            display: flex;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            border: 2px solid #724B2C;
            border-radius: 20px;
            background-color: #F1B461;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        #pergunta-secao {
            flex: 2; 
            padding: 25px;
            background-color: #FFF; 
            border-right: 2px solid #724B2C;
            word-wrap: break-word; 
            overflow-wrap: break-word;
            box-sizing: border-box; 
            min-width: 0; 
            overflow-y: auto;
            border-top-left-radius: 18px; 
            border-bottom-left-radius: 18px; 
            border-top-right-radius: 0; 
            border-bottom-right-radius: 0; 
        }
        
        #respostas-secao {
            flex: 1; 
            padding: 25px;
            background-color: transparent; 
            box-sizing: border-box; 
            min-width: 0; 
            border-top-right-radius: 18px; 
            border-bottom-right-radius: 18px; 
            border-top-left-radius: 0; 
            border-bottom-left-radius: 0; 
            
            max-height: 80vh;
            overflow-y: auto;
        }


        #pergunta {
            word-wrap: break-word; 
            overflow-wrap: break-word;
        }
        #pergunta h1 {
            color: #724B2C;
            border-bottom: 2px solid #F1B461;
            padding-bottom: 10px;
            word-wrap: break-word; 
            overflow-wrap: break-word;
        }
        #pergunta p {
            color: #444;
            line-height: 1.6;
        }
        .info-pergunta-meta {
            font-size: 0.9em;
            text-align: right;
            margin: 5px 0;
            color: #724B2C; 
        }

        #respostas-secao h2 {
            color: #724B2C;
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #724B2C;
            padding-bottom: 10px;
            box-sizing: border-box; 
            width: 100%; 
            white-space: normal; 
            word-wrap: break-word; 
            overflow-wrap: break-word;
        }
        .resposta {
            background-color: #FFFFFF; 
            border: 1px solid #724B2C;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            word-wrap: break-word;
            overflow-wrap: break-word;
            box-sizing: border-box; 
            max-width: 100%; 
            min-width: 0; 
        }
        .resposta p {
            margin: 5px 0;
            color: #333;
        }
        .resposta p:last-child {
            font-size: 0.85em !important;
            color: #724B2C !important;
            text-align: right;
            margin-top: 10px;
        }
        
        #responder {
            margin-top: 30px;
        }
        
        #btn-abrir-resposta,
        #btn-resposta,
        #btn-enviar-resposta {
            padding: 10px 20px;
            border: 2px solid #724B2C;
            border-radius: 20px;
            background-color: #FFA06A;
            color: #724B2C;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        #btn-abrir-resposta:hover,
        #btn-resposta:hover,
        #btn-enviar-resposta:hover {
            background-color: #FF905A;
        }
        
        #form-resposta {
            display: none;
            margin-top: 15px;
            padding: 20px;
            border: 2px solid #724B2C;
            border-radius: 10px;
            background-color: #F1B461; 
        }
        #form-resposta textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #724B2C;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #FFFFFF;
            resize: vertical;
            word-wrap: break-word; 
            overflow-wrap: break-word;
        }
        
        .texto-completo {
            display: none;
        }
        .ver-mais {
            cursor: pointer;
            color: #724B2C; 
            text-decoration: underline;
            border: none;
            background: none;
            padding: 0;
            font-size: 0.9em;
            margin-top: 5px;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        
        .ver-mais:hover {
            color: #FF905A;
        }

        .main-content a {
            display: block;
            text-decoration: none;
            color: #724B2C;
            font-weight: bold;
            padding: 10px;
            border: 2px solid #724B2C;
            border-radius: 20px;
            background-color: #FFA06A;
            width: 150px;
            text-align: center;
            margin-top: 20px;
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
            <div id="pergunta-secao">
                <div id="pergunta">
                    <?php
                    $materia_nome = !empty($linha_pergunta['nome_materia']) ? htmlspecialchars($linha_pergunta['nome_materia']) : 'Sem Matéria';

                    echo "<h1>" . htmlspecialchars($linha_pergunta['titulo_pergunta']) . "</h1>";
                    echo "<p>" . nl2br(htmlspecialchars($linha_pergunta['pergunta'])) . "</p>";
                    
                    echo "<p class='info-pergunta-meta'>Feito por: " . htmlspecialchars($linha_pergunta['nome_usuario']) . "</p>";
                    echo "<p class='info-pergunta-meta'>Matéria: <strong>" . $materia_nome . "</strong></p>";
                    
                    echo "<hr>";
                    ?>
                </div>
                
                <div id="responder">
                    <button id="btn-abrir-resposta">Adicionar resposta</button>
                    <div id="form-resposta">
                        <form action="processar_resposta.php" method="post">
                            <input type="hidden" name="id_pergunta" value="<?php echo htmlspecialchars($id_pergunta); ?>">
                            <textarea name="corpo_resposta" rows="5" placeholder="Escreva sua resposta..." required></textarea>
                            <br>
                            <button type="submit" id="btn-enviar-resposta">Enviar resposta</button> 
                        </form>
                    </div>
                </div>
                <a href="pagina_principal.php">Voltar</a>
            </div>
        
            <div id="respostas-secao">
                <h2>Respostas</h2>
                <?php
                
                if (mysqli_num_rows($resultado_respostas) > 0) {
                    $limite_caracteres = 200;
                    $contador_resposta = 0;

                    while ($linha_resposta = mysqli_fetch_assoc($resultado_respostas)) {
                        $contador_resposta++;
                        $id_unico = "resposta-" . $contador_resposta;
                        $resposta_completa = htmlspecialchars($linha_resposta['resposta']);

                        echo "<div class='resposta'>";
                        
                        if (strlen($resposta_completa) > $limite_caracteres) {
                            $resposta_truncada = substr($resposta_completa, 0, $limite_caracteres) . '...';
                            
                            echo "<p class='texto-truncado'>" . nl2br($resposta_truncada) . "</p>";
                            echo "<span class='texto-completo' id='" . $id_unico . "'>" . nl2br($resposta_completa) . "</span>";
                            echo "<button class='ver-mais' data-alvo-id='" . $id_unico . "'>Ler mais</button>";
                        } else {
                            echo "<p>" . nl2br($resposta_completa) . "</p>";
                        }

                        echo "<p>Por: " . htmlspecialchars($linha_resposta['nome_usuario']) . " em " . date('d/m/Y H:i', strtotime($linha_resposta['data_resposta'])) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Nenhuma resposta ainda. Seja o primeiro a responder!</p>";
                }
                
                mysqli_close($conexao_banco);
                ?>
            </div>
        </div>
    </div>
    
    <footer>
         &copy; <?php echo date("Y"); ?> GerminarEtec. Todos os direitos reservados.
        <br>
        germinaretec@gmail.com
        <br>
    </footer>
    <script src="java.js"></script>
</body>
</html>