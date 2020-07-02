<?php  
setcookie('user', $user['login'], time() - 3600, "/");
setcookie('user_id', $user['id'], time() - 3600, "/");
header('Location: /')
?>