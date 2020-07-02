<?php 
require_once "conn.php";
require_once ('vendor/autoload.php');
use \Dejurin\GoogleTranslateForFree;

if (isset($_POST['reg'])) {
	if ($_POST['pass'] == $_POST['confirm-pass']) {

	$login = filter_var(trim($_POST['login']),FILTER_SANITIZE_STRING);
	$pass = filter_var(trim($_POST['pass']),FILTER_SANITIZE_STRING);

	if (mb_strlen($login) < 3 || mb_strlen($login) > 29) {
		echo "Недопустимая длинна логина";
		exit();
	}
	elseif (mb_strlen($pass) < 3 || mb_strlen($login) > 29) {
		echo "Недопустимая длинна пароля";
		exit();
	}
	$pass = md5($pass);
	$conn->query("INSERT INTO users (login, pass) VALUES ('$login', '$pass')");

	}
	else{
		echo "Пароли не совпадают";
	}
	
}
if (isset($_POST['auth'])) {
	$login = filter_var(trim($_POST['login']),FILTER_SANITIZE_STRING);
	$pass = filter_var(trim($_POST['pass']),FILTER_SANITIZE_STRING);
	$pass = md5($pass);

	$result = $conn->query("SELECT * FROM users WHERE login = '$login' AND pass = '$pass' ");
	$user = $result->fetch_assoc();
	if (count($user) == 0) {
		echo "Такого пользователя не найдено";
	}
	else{
		setcookie('user', $user['login'], time() + 3600, "/");
		setcookie('user_id', $user['id'], time() + 3600, "/");
		header('Location: /');
	}
}
while (TRUE) {
	if (isset($_POST['addEnWord'])) {
		if ($_POST['word'] != "") {
			$enWord = mb_strtolower(trim($_POST['word']));
			$source = 'en';
			$target = 'ru';
			$attempts = 5;
			$text = $enWord;

			$tr = new GoogleTranslateForFree();
			$result = $tr->translate($source, $target, $text, $attempts);
			$ruWord = mb_strtolower($result);
			$user_id = $_COOKIE['user_id'];
			$result = $conn->query("SELECT * FROM words WHERE user_id = '$user_id'");
			while ($row = $result->fetch_assoc()) {
	 			if ($ruWord == $row['ruWord'] || $enWord == $row['enWord']) {
					echo "Такое слово уже есть";
					break 2;
				}
	 		}
				$conn->query("INSERT INTO words (user_id, ruWord, enWord) VALUES ('$user_id', '$ruWord', '$enWord')");
				$conn->query("UPDATE users SET xp=xp+1 WHERE id = '$user_id'");
				break;
		}
		else{
			echo "Введите слово";
			break;
		}
	}
	break;
}
while (TRUE) {
	if (isset($_POST['addRuWord'])) {
		if ($_POST['word'] != "") {
			$ruWord = mb_strtolower(trim($_POST['word']));
			$source = 'ru';
			$target = 'en';
			$attempts = 5;
			$text = $ruWord;

			$tr = new GoogleTranslateForFree();
			$result = $tr->translate($source, $target, $text, $attempts);
			$enWord = mb_strtolower($result);
			$enWord = preg_replace('/[^ a-zа-яё\d]/ui', '',$enWord);
			$user_id = $_COOKIE['user_id'];
			$result = $conn->query("SELECT * FROM words WHERE user_id = '$user_id'");
	 		while ($row = $result->fetch_assoc()) {
	 			if ($ruWord == $row['ruWord'] || $enWord == $row['enWord']) {
					echo "Такое слово уже есть";
					break 2;
				}
	 		}
			
				$conn->query("INSERT INTO words (user_id, ruWord, enWord) VALUES ('$user_id', '$ruWord', '$enWord')");
				$conn->query("UPDATE users SET xp=xp+1 WHERE id = '$user_id'");
				break;
		}
		else{
			echo "Введите слово";
			break;
		}
	}
	break;
}
if (isset($_POST["delete"])) {

	$conn->query("DELETE FROM words WHERE id = {$_POST["delete"]}");
}
if (isset($_POST["update"])) {
	$conn->query("UPDATE words SET ruTraining=0, enTraining=0, enterTraining=0, playTraining=0 WHERE id = {$_POST["update"]}");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Qwear</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<? if ($_COOKIE['user'] == ""): ?>
<div class="container mt-4">
	<h1>Регистрация</h1><br>
	<form action="" method="post">
		<input type="text" name="login" placeholder="Enter login..."><br><br>
		<input type="password" name="pass" placeholder="Enter pass..."><br><br>
		<input type="password" name="confirm-pass" placeholder="Confirm pass..."><br><br>
		<button class="btn btn-success" type="submit" name="reg">Зарегестрироваться</button>
	</form>
</div>
<div class="container mt-4">
	<h1>Авторизация</h1><br>
	<form action="" method="post">
		<input type="text" name="login" placeholder="Enter login..."><br><br>
		<input type="password" name="pass" placeholder="Enter pass..."><br><br>
		<button class="btn btn-success" type="submit" name="auth">Авторизоваться</button>
	</form>
</div>
<? else: ?>
	<a href="logout.php" class="exit">Выйти</a>
	<h4>Добрый день, <?=$_COOKIE['user']?></h3>
	<hr>
	<div class="row">
	<div class="content col-md-8">
		<div class="add">
			<button class="btn btn-primary ml-3" onclick="addWord1()">Добавить слово на английском</button>
			<form class="ml-3 aWord1" action="" method="POST">
				<br>
				<input type="text" name="word" placeholder="Enter english word here..">
				<button class="btn btn-success" type="submit" name="addEnWord">Добавить</button>
			</form><br>
			<button class="btn btn-primary ml-3" onclick="addWord2()">Добавить слово на русском</button>
			<form class="ml-3 aWord2" action="" method="POST">
				<br>
				<input type="text" name="word" placeholder="Enter russian word here..">
				<button class="btn btn-success" type="submit" name="addRuWord">Добавить</button>
			</form>
		</div>
		<div class="training">
			<form action="training.php" method="post">
			<button type="submit" class="btn btn-info ml-3 mt-5" onclick="startTraining()">Начать тренировки</button>
			</form>
		</div>
	</div>
	<div class="words col-md-4">
		<h5 class="text-center">Мои слова</h5>
<table class="table text-center">
	<tbody>
	<? 
	$result = $conn->query("SELECT * FROM words ORDER BY ruWord");
	while ($row = $result->fetch_assoc()) {
	if ($row['user_id'] == $_COOKIE['user_id']) {
	?>
    <tr<? if ($row['ruTraining'] == 1 && $row['enTraining'] == 1 && $row['enterTraining'] == 1): ?> class="table-success">
    
      <td  valign="middle"><?=$row['ruWord']?></td>
      <td><?=$row['enWord']?> <br> <button class="btn btn-success" onclick="talk('<?=$row['enWord']?>')"><img class="play" src="media-play.svg"></button></td>
      <td><form action='' method='post'><input type='hidden' name='update' value=<? echo $row["id"]?> ><input class="btn btn-info  btn-sm" type='submit' value='Изучить'></form>
      	<form action='' method='post'><input type='hidden' name='delete' value=<? echo $row["id"]?> ><input class="btn btn-danger  btn-sm" type='submit' value='Удалить'></form>
      	</td>
    </tr>
    <? else: ?> >
      <td><?=$row['ruWord']?></td>
      <td><?=$row['enWord']?> <br> <button class="btn btn-success" onclick="talk('<?=$row['enWord']?>')"><img class="play" src="media-play.svg"></button></td>
      <td><form action='' method='post'><input type='hidden' name='delete' value=<? echo $row["id"]?>><input class="btn btn-danger  btn-sm" type='submit' value='Удалить'></form>
    </tr>
    <? endif; ?>
    <? } } ?>
    </tbody>
</table>
	</div>
	</div><br><br><br>
<div class="row">
	<div class="Lcontent col-md-8">
		<div class="xp mt-5 ml-3 ">
			<h2 style="color: #2150E0;">Мои баллы: 
			<?
			$user = $_COOKIE['user'];
			$result = $conn->query("SELECT * FROM users WHERE login = '$user'");
			$user = $result->fetch_assoc();
			echo $user['xp'];
			?>
			
		</h2>
		</div>
	</div>
	<div class="Rcontent col-md-4">
		<h5 class="text-center">Таблица рекордов</h5>
		<div class="rangTable">
			<table class="table text-center">
				<tbody class="rTableBody">
				<? 
				$result = $conn->query("SELECT * FROM users ORDER BY xp DESC");
				while ($row = $result->fetch_assoc()) {
				?>
			    <tr>
			      <td class="rTableTd"><?=$row['login']?></td>
			      <td class="rTableTd"><?=$row['xp']?></td>
			    </tr>			    
			    <? } ?>
			    </tbody>
			</table>
		</div>
	</div>
</div>
<? endif; ?>
<script type="text/javascript" src="js.js"></script>
</body>
</html>