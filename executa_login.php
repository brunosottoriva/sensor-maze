<?php 
// Métodos de acesso ao banco de dados 
require "fachada.php"; 
 
// Inicia sessão 
session_start();
$isPedido = $_POST["isPedido"];
$urlAction = "Location: home/inicio.php";

if(isset($_POST["isPedido"]) && (int)$_POST["isPedido"] == 1)
    $urlAction = "Location: marketplace/criarPedido.php";

if(isset($_POST["isLogin"]) && (int)$_POST["isLogin"] == 1)
    $urlAction = "Location: marketplace/index.php";

// Recupera o login
$login = isset($_POST["login"]) ? addslashes(trim($_POST["login"])) : FALSE; 
// Recupera a senha, a criptografando em MD5 
$senha = isset($_POST["senha"]) ? md5(trim($_POST["senha"])) : FALSE; 
//$senha = isset($_POST["senha"]) ? trim($_POST["senha"]) : FALSE; 
 
// Usuário não forneceu a senha ou o login 
if(!$login || !$senha) 
{ 
    echo "Você deve digitar sua senha e login!<br>";
    echo "<a href='home/login.php'>Efetuar Login</a>";
    exit; 
}  

$dao = $factory->getUsuarioDao();
$usuario = $dao->buscaPorLogin($login);

$problemas = FALSE;

if($usuario) {
    
    // Agora verifica a senha 
    if(strcmp($senha, $usuario->getSenha())) 
    { 
        // TUDO OK! Agora, passa os dados para a sessão e redireciona o usuário 
        $_SESSION["id_usuario"]= $usuario->getId(); 
        $_SESSION["nome_usuario"] = stripslashes($usuario->getNome()); 
        $_SESSION["usuario_logado"] = $usuario;
        //$_SESSION["permissao"]= $dados["postar"]; 

        header($urlAction);
        exit; 
    } else {
        $problemas = TRUE; 
    }
} else {
    $problemas = TRUE; 
}

if($problemas==TRUE) {
    header($urlAction); 
    exit; 
}

?>