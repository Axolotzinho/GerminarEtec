<?php
session_start();
require 'conexao_banco.php';

if (!isset($_SESSION['id_usuario'])) {
    die("Você precisa estar logado para responder.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_pergunta = mysqli_real_escape_string($conexao_banco, $_POST['id_pergunta']);
    $corpo_resposta = mysqli_real_escape_string($conexao_banco, $_POST['corpo_resposta']);
    $id_usuario = $_SESSION['id_usuario'];
    $data_resposta = date('Y-m-d H:i:s');

    $sql = "INSERT INTO resposta (id_pergunta, id_usuario, resposta, data_resposta) 
            VALUES ('$id_pergunta', '$id_usuario', '$corpo_resposta', '$data_resposta')";
            
    if (mysqli_query($conexao_banco, $sql)) {
        header("Location: pergunta.php?id=" . $id_pergunta . "&success=1");
        exit();
    } else {
        echo "Erro ao enviar a resposta: " . mysqli_error($conexao_banco);
    }

    mysqli_close($conexao_banco);

} else {
    header("Location: pagina_principal.php");
    exit();
}
?>