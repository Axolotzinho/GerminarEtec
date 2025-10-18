<?php
$host = "localhost";
$user = "root";
$pass = "";
$banco = "germinaretec";
$conexao_banco = mysqli_connect($host, $user, $pass) or die("Erro ao conectar");
mysqli_select_db($conexao_banco, $banco) or die(mysqli_error($conexao_banco));
mysqli_set_charset($conexao_banco, "utf8");
?>