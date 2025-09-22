<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pergunta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }

        #pergunta-secao {
            flex: 3;
            margin-right: 20px;
            padding-right: 20px;
            border-right: 1px solid #ccc;
        }
        
        #respostas-secao {
            flex: 1;
        }
        
        #responder {
            margin-top: 20px;
        }

        #form-resposta {
            display: none;
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        #form-resposta textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        #btn-resposta {
            padding: 10px 15px;
            border: none;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            cursor: pointer;
            border-radius: 4px;
        }
        .resposta {
            word-wrap: break-word;
            max-width: 400px;
        }
        .texto-completo {
            display: none;
        }
        .ver-mais {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
            border: none;
            background: none;
            padding: 0;
            font-size: 1em;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div id="pergunta-secao">
            <div id="pergunta">
                <?php
                session_start();
                require 'conexao_banco.php';

                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $id_pergunta = mysqli_real_escape_string($conexao_banco, $_GET['id']);
                    
                    $sql_pergunta = "SELECT p.titulo_pergunta, p.pergunta, u.nome_usuario
                                     FROM pergunta p
                                     INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                                     WHERE p.id_pergunta = '$id_pergunta'";
                            
                    $resultado_pergunta = mysqli_query($conexao_banco, $sql_pergunta);
                    
                    if (mysqli_num_rows($resultado_pergunta) > 0) {
                        $linha_pergunta = mysqli_fetch_assoc($resultado_pergunta);
                        
                        echo "<h1>" . htmlspecialchars($linha_pergunta['titulo_pergunta']) . "</h1>";
                        echo "<p>" . nl2br(htmlspecialchars($linha_pergunta['pergunta'])) . "</p>";
                        echo "<p>Feito por: " . htmlspecialchars($linha_pergunta['nome_usuario']) . "</p>";
                        
                        echo "<hr>";
                    } else {
                        echo "<h1>Pergunta não encontrada.</h1>";
                    }

                } else {
                    header("Location: pagina_principal.php");
                    exit;
                }
                ?>
            </div>
            
            <div id="responder">
                <button id="btn-abrir-resposta">Adicionar resposta</button>
                <div id="form-resposta">
                    <form action="processar_resposta.php" method="post">
                        <input type="hidden" name="id_pergunta" value="<?php echo htmlspecialchars($id_pergunta); ?>">
                        <textarea name="corpo_resposta" rows="5" placeholder="Escreva sua resposta..." required></textarea>
                        <br>
                        <button type="submit" id="btn-resposta">Enviar resposta</button>
                    </form>
                </div>
            </div>
            <a href="pagina_principal.php">Voltar</a>
        </div>
    
        <div id="respostas-secao">
            <h2>Respostas</h2>
            <?php
            $sql_respostas = "SELECT r.resposta, r.data_resposta, u.nome_usuario 
                               FROM resposta r
                               INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                               WHERE r.id_pergunta = '$id_pergunta'
                               ORDER BY r.data_resposta ASC";
                               
            $resultado_respostas = mysqli_query($conexao_banco, $sql_respostas);
            
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

                    echo "<p style='font-size: 0.9em; color: #555; text-align: right;'>Por: " . htmlspecialchars($linha_resposta['nome_usuario']) . " em " . date('d/m/Y H:i', strtotime($linha_resposta['data_resposta'])) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Nenhuma resposta ainda. Seja o primeiro a responder!</p>";
            }
            
            mysqli_close($conexao_banco);
            ?>
        </div>
    </div>
    </table>
    <script src="java.js"></script>
</body>
</html>