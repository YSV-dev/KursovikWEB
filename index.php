<!DOCTYPE HTML>
<HTML>
	<HEAD>
		<META charset="UTF-8">
		<link rel="icon" sizes="64x64" href="Icon/LOGO64.png">
		<link rel="stylesheet" type="text/css" href="Style/Main.css">
		<title>SimpleFurniture</title>
	</HEAD>
	<body>
		<div class="centralBlock">
			<header id="headerHead">
			<p align='right'>
				<a href="index.php?page=registration" class=enterLinks>Регистрация</a>
				<a href="index.php?page=login" class=enterLinks>Вход</a>
			</p>
			<p id="MainCaption">Website Development Technology<p>
			</header>
			<nav>
				<div class=navButtonDiv>
					<button class=navButton>
					<b>Каталог</b>
					</button>
					<button onclick="document.location='index.php?page=main'" class=navButton>
					<b>Главная</b>
					</button>
					<button class=navButton>
					<b>Отзывы</b>
					</button>
					<button class=navButton>
					<b>Обратная связь</b>
					</button>
				</div>
			</nav>
			<div class="mainList">
				<content>
					<?PHP
						$db = mysqli_connect("localhost","root","");
						mysqli_select_db($db, "wdt")or die("Нет соединения с БД " . mysqli_connect_error());
						
						$page = isset($_SESSION["page"])? $_SESSION["page"]: "main";
						
						if ($_GET["page"]=="main"||$_GET["page"]==null){
							
							$sql = mysqli_query($db, "SELECT * FROM users WHERE status='Преподаватель' LIMIT 3;");
							
							echo ("<style>
								   p {
									text-indent: 20px;
								   }
								  </style>
								  
									
									
							<h2 align=\"center\">Добро пожаловать на сайт Website Development Technology</h2> 
									<p>Здесь вы найдете всё самое необходимое для разработки вашего собственного сайта! 
									Если вам нужна будет помощь, то вы всегда можете обратиться к одному из наших преподавателей 
									и, в ближайшее время, они постараются вам ответить.
							</p>
							");
							echo("
							<div style=\"
							overflow: hidden;
							\">
								<div style=\"
								width: 1000%;
								margin-left: 15%; /* Отступ слева */
								padding: 10px; /* Поля вокруг текста */
								\">
							"
							);
							while ($rows = mysqli_fetch_row($sql))
							{
							  echo("<div style='
							  float: left; 
							  width: 150px; 
							  height: 200px;
							  background-image: linear-gradient(to bottom, rgb(153, 230, 255), rgb(160, 223, 250), rgb(167, 216, 245), rgb(175, 208, 240), rgb(182, 201, 235), rgb(189, 194, 230), rgb(196, 187, 225), rgb(203, 180, 220), rgb(210, 173, 215), rgb(218, 165, 210), rgb(225, 158, 205), rgb(232, 151, 200));
							  border: solid 2px;
							  border-radius: 8px;
							  border-color: #b8b8b8;
							  margin: 5px;
							  '>
							  <img src='res/avatar/$rows[5]' width='150' height='150' alt='Нет аватара'
							  style= 
							  'border-radius: 5px;'>
							  <div align='center'>$rows[1]</div>
							  </div>");
							}
							echo("</div>
							</div>
							<p></p>");
							
							
							echo("<style>
								   li {
									list-style-type: none; /* Убираем маркеры */
								   }
								  </style>");//
								  
							$sql = mysqli_query($db, 
							"
							SELECT GRPN.groupName, AR.articleName, AR.ArticleLink FROM articlegroups AGS
							JOIN GROUPNAMES GRPN ON AGS.groupID = GRPN.groupID
							JOIN (SELECT * FROM ARTICLES WHERE Actual=1) AR ON AR.articleID = AGS.articleID
							");
							$lastGroup = null;
							while ($rows = mysqli_fetch_row($sql)){
								if ($lastGroup!=$rows[0]){
									if ($lastGroup!=null){
										echo("</ul>");
									}
									echo("
									<p><b>$rows[0]</b></p>
									<ul>");
								}
								echo("<li><a href=\"index.php?page=$rows[2]\">$rows[1]</a></li>");
								$lastGroup = $rows[0];
							}
						} elseif ($_GET["page"]=="registration"){
//регистрация////////////////////////////////////////////////
							
							if(isset($_POST['submit']))
							{
								$err = [];

								// проверям логин
								if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
								{
									$err[] = "Логин может состоять только из букв английского алфавита и цифр";
								}

								if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
								{
									$err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
								}

								// проверяем, не сущестует ли пользователя с таким именем
								$query = mysqli_query($db, "SELECT userid FROM users WHERE login='".mysqli_real_escape_string($db, $_POST['login'])."'");
								if(mysqli_num_rows($query) > 0)
								{
									$err[] = "Пользователь с таким логином уже существует в базе данных";
								}
								
								$query = mysqli_query($db, "SELECT userid FROM users WHERE email='".mysqli_real_escape_string($db, $_POST['email'])."'");
								if(mysqli_num_rows($query) > 0)
								{
									$err[] = "Пользователь с таким e-mail уже существует в базе данных";
								}

								// Если нет ошибок, то добавляем в БД нового пользователя
								if(count($err) == 0)
								{
									$login = $_POST['login'];
									$mail = $_POST['mail'];
									$name = $_POST['name'];
									// Убераем лишние пробелы и делаем двойное хеширование
									$password = md5(md5(trim($_POST['password'])));
									
									$command = "INSERT INTO users SET 
									login='".$login."', 
									password='".$password."',
									email='".$mail."',
									username='".$name."'";
									mysqli_query($db,$command);
									echo("<script>location.href='index.php?page=login'</script>");
								}
								else
								{
									print "<b>При регистрации произошли следующие ошибки:</b><br>";
									foreach($err AS $error)
									{
										print $error."<br>";
									}
								}
							}
							echo('
							<form method="POST">
								Логин <input name="login" type="text" required><br>
								Ник <input name="name" type="text" required><br>
								E-mail <input name="mail" type="email" required><br>
								Пароль <input name="password" type="password" required><br>
							<input name="submit" type="submit" value="Зарегистрироваться">
							</form>'
							);
							
/////////////////////////////////////////////////////////////
						} elseif ($_GET["page"]=="login"){
//вход///////////////////////
							
							// Функция для генерации случайной строки
							function generateCode($length=6) {
								$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
								$code = "";
								$clen = strlen($chars) - 1;
								while (strlen($code) < $length) {
										$code .= $chars[mt_rand(0,$clen)];
								}
								return $code;
							}

							// Соединямся с БД

							if(isset($_POST['submit']))
							{
								// Вытаскиваем из БД запись, у которой логин равняеться введенному
								$query = mysqli_query($db,"SELECT userid, password FROM users WHERE login='".mysqli_real_escape_string($db, $_POST['login'])."' LIMIT 1");
								$data = mysqli_fetch_assoc($query);

								// Сравниваем пароли
								if($data['password'] === md5(md5($_POST['password'])))
								{
									// Генерируем случайное число и шифруем его
									$hash = md5(generateCode(10));

									if(!empty($_POST['not_attach_ip']))
									{
										// Если пользователя выбрал привязку к IP
										// Переводим IP в строку
										$insip = ", ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')";
									}

									// Записываем в БД новый хеш авторизации и IP
									mysqli_query($db, "UPDATE users SET hash='".$hash."' ".$insip." WHERE userid='".$data['userid']."'");

									// Ставим куки
									setcookie("userid", $data['userid'], time()+60*60*24*30, "/");
									setcookie("hash", $hash, time()+60*60*24*30, "/", null, null, true); // httponly !!!

									
									// Переадресовываем браузер на страницу проверки нашего скрипта
									echo("<script>location.href='check.php'</script>");exit();
								}
								else
								{
									print "Вы ввели неправильный логин/пароль";
								}
							}
							
							
							
							echo('
							<form method="post">
								<div align="center" style="
								border: solid 1px;
								margin-left: 25%;
								margin-right: 25%;
								border-radius: 15px;
								padding: 10px;
								border-color: silver;
								background-image: linear-gradient(to top left, rgb(23, 23, 23), rgb(63, 32, 50), rgb(103, 40, 78), rgb(142, 49, 105), rgb(182, 57, 133), rgb(222, 66, 160), rgb(190, 57, 144), rgb(159, 47, 129), rgb(127, 38, 113), rgb(95, 28, 97), rgb(64, 19, 82), rgb(32, 9, 66));
								">
									<div><label>Логин</label></div>  
									<div><input id="login" name="login" type="text" required=""></div>

									<div><label>Пароль</label></div>  
									<div><input id="password" name="password" type="password" required=""></div>
									
									<div><label class=smallLabel>Не прикреплять к IP(не безопасно)</label> <input type="checkbox" name="not_attach_ip"><br></div>
									<input name="submit" type="submit" value="Войти" class="ApplyButton">
								</div>
							</form>
							');
							echo($_COOKIE['id']);
/////////////////////////////
						} else {
							//Статьи
							$fp = fopen("res/article/".$_GET["page"], "r"); // Открываем файл в режиме чтения
							if ($fp)
							{
							while (!feof($fp))
								{
									$mytext = fgets($fp, 999);
									echo $mytext."<br />";
								}
							}
							else echo "Такой статьи не существует. ОШИБКА ССЫЛКИ!!!";
							fclose($fp);
						}
						echo($_GET["page"]);
						mysqli_close($db);
					?>
				</content>
			</div>
			
			<footer>
				<div class="footerContent">
					Футер
				</div>
			</footer>
		</div>
		
		
	</body>
</HTML>