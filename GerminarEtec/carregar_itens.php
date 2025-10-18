<?php

require 'conexao_banco.php'; 

$limite = 5;

$aba = isset($_GET['aba']) ? $_GET['aba'] : '';
$offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? (int)$_GET['offset'] : 0;
$id_materia = isset($_GET['materia']) && is_numeric($_GET['materia']) ? $_GET['materia'] : null;
$ordenacao = isset($_GET['ordenacao']) ? $_GET['ordenacao'] : 'recente';
$order_by = ($ordenacao == 'recente') ? 'DESC' : 'ASC';

$html_retorno = '';
$resultados_encontrados = 0;

if ($aba === 'perguntas') {
    $limite_caracteres = 100;
    
    $sql = "SELECT p.id_pergunta, p.titulo_pergunta, p.pergunta, u.nome_usuario, m.nome_materia
            FROM pergunta p
            INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
            LEFT JOIN pergunta_materias pm ON p.id_pergunta = pm.id_pergunta 
            LEFT JOIN materias m ON pm.id_materia = m.id_materia";

    $where = [];
    if ($id_materia) {
        $where[] = "pm.id_materia = '" . mysqli_real_escape_string($conexao_banco, $id_materia) . "'";
    }

    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }
    
    $sql .= " GROUP BY p.id_pergunta, p.titulo_pergunta, p.pergunta, u.nome_usuario, m.nome_materia";

    $sql .= " ORDER BY p.data_pergunta " . $order_by . " LIMIT " . $limite . " OFFSET " . $offset;

    $resultado = mysqli_query($conexao_banco, $sql);
    $resultados_encontrados = mysqli_num_rows($resultado);

    if ($resultados_encontrados > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $corpo_pergunta = htmlspecialchars($linha['pergunta']);
            if (strlen($corpo_pergunta) > $limite_caracteres) {
                $corpo_pergunta = substr($corpo_pergunta, 0, $limite_caracteres) . '...';
            }
            
            $materia_nome = !empty($linha['nome_materia']) ? htmlspecialchars($linha['nome_materia']) : 'Sem Matéria';

            $html_retorno .= "<div class='item-caixa-branca pergunta-item'>";
            $html_retorno .= "<h3>" . htmlspecialchars($linha['titulo_pergunta']) . "</h3>";
            $html_retorno .= "<p>" . $corpo_pergunta . "</p>";
            $html_retorno .= "<p class='info-autor-materia'>Por: " . htmlspecialchars($linha['nome_usuario']) . " | Matéria: <strong>" . $materia_nome . "</strong></p>";
            $html_retorno .= "<p><a href='pergunta.php?id=" . htmlspecialchars($linha['id_pergunta']) . "'>Ver pergunta completa</a></p>";
            $html_retorno .= "</div>";
        }
    }

} elseif ($aba === 'postagens') {
    $limite_caracteres = 200;

    $sql = "SELECT p.id_post, p.titulo_post, p.conteudo, u.nome_usuario, m.nome_materia
            FROM post p
            INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
            LEFT JOIN postagem_materias pm ON p.id_post = pm.id_post
            LEFT JOIN materias m ON pm.id_materia = m.id_materia";
    
    $where = [];
    if ($id_materia) {
        $where[] = "pm.id_materia = '" . mysqli_real_escape_string($conexao_banco, $id_materia) . "'";
    }

    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }
    
    $sql .= " GROUP BY p.id_post, p.titulo_post, p.conteudo, u.nome_usuario, m.nome_materia";

    $sql .= " ORDER BY p.data_post " . $order_by . " LIMIT " . $limite . " OFFSET " . $offset;

    $resultado = mysqli_query($conexao_banco, $sql);
    $resultados_encontrados = mysqli_num_rows($resultado);

    if ($resultados_encontrados > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $corpo_post = htmlspecialchars($linha['conteudo']);
            if (strlen($corpo_post) > $limite_caracteres) {
                $corpo_post = substr($corpo_post, 0, $limite_caracteres) . '...';
            }
            
            $materia_nome = !empty($linha['nome_materia']) ? htmlspecialchars($linha['nome_materia']) : 'Sem Matéria';

            $html_retorno .= "<div class='item-caixa-branca post-item'>";
            $html_retorno .= "<h3>" . htmlspecialchars($linha['titulo_post']) . "</h3>";
            $html_retorno .= "<p>" . $corpo_post . "</p>";
            $html_retorno .= "<p class='info-autor-materia'>Por: " . htmlspecialchars($linha['nome_usuario']) . " | Matéria: <strong>" . $materia_nome . "</strong></p>";
            $html_retorno .= "<p><a href='post.php?id=" . htmlspecialchars($linha['id_post']) . "'>Ver post completo</a></p>";
            $html_retorno .= "</div>";
        }
    }
}

mysqli_close($conexao_banco);

echo $html_retorno . '|||' . $resultados_encontrados;
?>