<?
if ($_COOKIE['user'] == ""){
header('Location: index.php');
}

require_once "conn.php";
require_once ('vendor/autoload.php');
use \Dejurin\GoogleTranslateForFree;

if (isset($_POST['checkRuWord'])) {
	if ($_POST['ruWord'] != "") {
	$id = $_POST['id'];
	$ruWord = trim($_POST['ruWord']);
	$enWord = $_POST['enWord'];
	$result = $conn->query("SELECT ruWord FROM words WHERE id = '$id'");
	$user = $result->fetch_assoc();

	if (mb_strtolower($ruWord) == $user['ruWord']){	
		$conn->query("UPDATE words SET ruTraining=1 WHERE id = '$id'");
		$conn->query("UPDATE users SET xp=xp+1 WHERE id = '$user_id'");
		$res = 1;	
	}
	else{
		$res = 2;
	}
}
else{
	echo "Введите слово";
}
}

if (isset($_POST['checkEnWord'])) {
	if ($_POST['enWord'] != "") {
	$id = $_POST['id'];
	$ruWord = $_POST['ruWord'];
	$enWord = trim($_POST['enWord']);
	$result = $conn->query("SELECT enWord FROM words WHERE id = '$id'");
	$user = $result->fetch_assoc();

	if (mb_strtolower($enWord) == $user['enWord']){
		$conn->query("UPDATE words SET enTraining=1 WHERE id = '$id'");	
		$conn->query("UPDATE users SET xp=xp+1 WHERE id = '$user_id'");
		$res2 = 1;	
	}
	else{
		$res2 = 2;
	}
}
else{
	echo "Введите слово";
}
}

if (isset($_POST['checkEnterWord'])) {
	if ($_POST['enWord'] != "") {
	$id = $_POST['id'];
	$ruWord = $_POST['ruWord'];
	$enWord = $_POST['enWord'];
	$result = $conn->query("SELECT enWord FROM words WHERE id = '$id'");
	$user = $result->fetch_assoc();

	if (mb_strtolower($enWord) == $user['enWord']){	
		$conn->query("UPDATE words SET enterTraining=1 WHERE id = '$id'");
		$conn->query("UPDATE users SET xp=xp+1 WHERE id = '$user_id'");
		$res3 = 1;	
	}
	else{
		$res3 = 2;
	}
}
else{
	echo "Введите слово";
}
}
if (isset($_POST['checkPlayWord'])) {
	if ($_POST['ruWord'] != "") {
	$id = $_POST['id'];
	$ruWord = trim($_POST['ruWord']);
	$enWord = $_POST['enWord'];
	$result = $conn->query("SELECT ruWord FROM words WHERE id = '$id'");
	$user = $result->fetch_assoc();

	if (mb_strtolower($ruWord) == $user['ruWord']){	
		$conn->query("UPDATE words SET playTraining=1 WHERE id = '$id'");
		$conn->query("UPDATE users SET xp=xp+1 WHERE id = '$user_id'");
		$res4 = 1;	
	}
	else{
		$res4 = 2;
	}
}
else{
	echo "Введите слово";
}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Training</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<a href="index.php" class="exit">Назад</a>
<h4>Добрый день, <?=$_COOKIE['user']?></h3>
<hr>
		<?
			$user_id = $_COOKIE['user_id'];
			$result = $conn->query("SELECT * FROM words");
			while ($row = $result->fetch_assoc()) {
				if ($row['user_id'] == $user_id && $row['enterTraining'] != 1) {
					$enterArr[] = $row['ruWord'];
				}
			}
			$result = $conn->query("SELECT * FROM words");
			while ($row = $result->fetch_assoc()) {
				if ($row['user_id'] == $user_id && $row['enTraining'] != 1) {
					$ruArr[] = $row['ruWord'];
				}
			}
			$result = $conn->query("SELECT * FROM words");
			while ($row = $result->fetch_assoc()) {
				if ($row['user_id'] == $user_id && $row['ruTraining'] != 1) {
					$enArr[] = $row['enWord'];
				}
			}
			$result = $conn->query("SELECT * FROM words");
			while ($row = $result->fetch_assoc()) {
				if ($row['user_id'] == $user_id && $row['playTraining'] != 1) {
					$playArr[] = $row['enWord'];
				}
			}
			$playWord = $playArr[rand(0,count($playArr)-1)];
			$enterWord = $enterArr[rand(0,count($enterArr)-1)];
			$enWord = $enArr[rand(0,count($enArr)-1)];
			$ruWord = $ruArr[rand(0,count($ruArr)-1)];
			$result = $conn->query("SELECT id FROM words WHERE enWord = '$playWord' AND user_id = '$user_id'");
			$row = $result->fetch_assoc();
			$playId = $row['id'];
			$result = $conn->query("SELECT id FROM words WHERE ruWord = '$enterWord' AND user_id = '$user_id'");
			$row = $result->fetch_assoc();
			$enterId = $row['id'];
			$result = $conn->query("SELECT id FROM words WHERE ruWord = '$ruWord' AND user_id = '$user_id'");
			$row = $result->fetch_assoc();
			$ruId = $row['id'];
			$result = $conn->query("SELECT id FROM words WHERE enWord = '$enWord' AND user_id = '$user_id'");
			$row = $result->fetch_assoc();
			$enId = $row['id'];
		?>
		<div class="training">
			<button class="btn btn-success" onclick="startTraining0()">Ввод слова</button><br>
			<? if ($res3 == 1) { ?>
			<p style="color: green" class="mt-2">Верно!</p>
			<? } 
			elseif($res3 == 2){ ?>
				<p style="color: red" class="mt-2">Не верно :(</p>
			<?}
				if ($enterWord) {
			?>
			<form class="ml-3 tWord0" action="" method="POST">
				<br>
				<input type="text" name="ruWord" value="<?= $enterWord ?>" readonly>
				<input type="text" name="enWord" id="inputLet" readonly><br>
				<?
					$result = $conn->query("SELECT enWord FROM words WHERE id = '$enterId'");
					$user = $result->fetch_assoc();
					$enterWord = $user['enWord'];
					$enterWord = str_split($enterWord);
					shuffle($enterWord);
					for ($i=0; $i < count($enterWord); $i++) {
						
						?>
							<input type="button" class="btn btn-light" id="<?= $i ?>" onclick="getLetter('<?= $enterWord[$i] ?>','<?= $i ?>')" value="<? if ($enterWord[$i] == " ") {echo "_";} else{echo $enterWord[$i];} ?>">
						<?
					}
				?>
				<input type='hidden' name='id' value=<?= $enterId ?> ><br>
				<input type="button" class="btn btn-danger" onclick="clean()" value="Сбросить">
				<button class="btn btn-success" type="submit" name="checkEnterWord">Ответить</button>
			</form>
			<?} 
				else{
					?> <form class="ml-3 tWord0" action="" method="POST">
					<p style="color: green" class="mt-2">Вы изучили все слова в этом разделе</p>
					</form><?
				}
			?><br>
			<button class="btn btn-success" onclick="startTraining3()">Прослушать</button>
			<? if ($res4 == 1) { ?>
			<p style="color: green" class="mt-2">Верно!</p>
			<? } 
			elseif($res4 == 2){ ?>
				<p style="color: red" class="mt-2">Не верно :(</p>
			<?}
				if ($playWord) {
			?>
			<form class="ml-3 tWord3" action="" method="POST">
				<br>
				<input type="button" name="enWord" value="Прослушать" onclick="talk('<?=$playWord?>')">
				<input type="text" name="ruWord" placeholder="Enter russian word here..">
				<input type='hidden' name='id' value=<?= $playId ?> >
				<button class="btn btn-success" type="submit" name="checkPlayWord">Ответить</button>
			</form>
			<?} 
				else{
					?> <form class="ml-3 tWord3" action="" method="POST">
					<p style="color: green" class="mt-2">Вы изучили все слова в этом разделе</p>
					</form><?
				}
			?>
			<br>
			<button class="btn btn-success" onclick="startTraining1()">Перевод - слово</button>
			<? if ($res == 1) { ?>
			<p style="color: green" class="mt-2">Верно!</p>
			<? } 
			elseif($res == 2){ ?>
				<p style="color: red" class="mt-2">Не верно :(</p>
			<?}
				if ($enWord) {
			?>
			<form class="ml-3 tWord1" action="" method="POST">
				<br>
				<input type="text" name="enWord" value="<?= $enWord ?>" readonly>
				<input type="text" name="ruWord" placeholder="Enter russian word here..">
				<input type='hidden' name='id' value=<?= $enId ?> >
				<button class="btn btn-success" type="submit" name="checkRuWord">Ответить</button>
			</form>
			<?} 
				else{
					?> <form class="ml-3 tWord1" action="" method="POST">
					<p style="color: green" class="mt-2">Вы изучили все слова в этом разделе</p>
					</form><?
				}
			?>
			<br>
			<button class="btn btn-success" onclick="startTraining2()">Слово - перевод</button>
			<? if ($res2 == 1) { ?>
			<p style="color: green" class="mt-2">Верно!</p>
			<? } 
			elseif($res2 == 2){ ?>
				<p style="color: red" class="mt-2">Не верно :(</p>
			<?}
				if ($ruWord) {
			?>
			<form class="ml-3 tWord2" action="" method="POST">
				<br>
				<input type="text" name="ruWord" value="<?= $ruWord ?>" readonly>
				<input type="text" name="enWord" placeholder="Enter english word here..">
				<input type='hidden' name='id' value=<?= $ruId ?> >
				<button class="btn btn-success" type="submit" name="checkEnWord">Ответить</button>
			</form>
			<?}
			 else{
			 	?>
			 	<form class="ml-3 tWord2" action="" method="POST">
					<p style="color: green" class="mt-2">Вы изучили все слова в этом разделе</p>
					</form>
			 	<?
			 }
			?>
		</div>
<script type="text/javascript" src="js.js"></script>
</body>
</html>