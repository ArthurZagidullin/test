<?php 
	/* Запускаем сессию и подключаем файл с классами */
	session_start();
	require_once('classes/server.php');
	/* Если пришел ID пользователя запустившего приложение */
	if (isset($_GET['viewer_id'])) {
		$_SESSION['uid'] = htmlspecialchars($_GET['viewer_id']);	// uid -- user id
		//var_dump($_SESSION['uid'] );
	}
	/* если в сессии есть UID пользователя */
	if(isset($_SESSION['uid'])):
		$user = new User($_SESSION['uid']);							// Создаем объект пользователя передавая его uid в конструктор
		$text = new Text($user);
		$hash = isset($text->sorry)?'#sorry':'#text';

		$questions = new Question($text->id);
		$qz = new Quiz($questions);

		/* Добавляем информацию о сессии пользователя */
		$user->tid = $text->id;
		/**********************************************/
		/* Пользователь ответил на все вопросы */
		if ((count($_POST) >= 9) && ( $bt = $_COOKIE['begin'] ) && ( $et = $_COOKIE['end'] )) {
			foreach ($_POST as $key => $value) {
				$k = Lib::cutAnsw($key);
				$v = Lib::cutAnsw($value);
				$qz->addAnsw($k, $v);
			}
			//удаляем куки
			setcookie('begin','',time()-3600);
			setcookie('end', time()-3600);

    	    if(($et-$bt)<10)										// если время меньше 10 секунд, сделать 
    	    {$rt = 1;}			
    	  	else{$rt = ($et - $bt)/60;}			
    	      				
			$cu = count($qz->ra)/10;								// правильные ответы
			$x = $text->lenght;										// количество символов
			$speed = $qz->result($x,$rt,$cu);

			/* Добавляем информацию о сессии пользователя */
			$user->setOld($rt,$cu,$speed);
			//print_r($qres);
			/**********************************************/

			setcookie('speed',$speed,time()+3600);

			//print_r("Скорость -- ".$speed." Коэффициент понимания ".$cu."<br>");
			//print_r($qz->ra);
			header('Location: /#finish');
		}
		if ($speed = $_COOKIE['speed']) {
			setcookie('speed','',time()-3600);
		}

		//var_dump($speed);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			Проверка скорости чтения
		</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/my.css">
		<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="/js/slidr.min.js"></script>
		<script src="js/my.js" type="text/javascript"></script>
        <script src="//vk.com/js/api/xd_connection.js?2"  type="text/javascript"></script>
	</head>
	<body class="container-fluid nopadding">
	<!-- Панель меню -->
		<div class="container-fluid nopadding bg-c">
			<div class="col-xs-8">
				<div class="col-xs-2">
					<a class="fc-gs" href="/">
						<span class="glyphicon glyphicon-repeat"></span>
					</a>
				</div>
				<div class="col-xs-1">	
					<span id="perm">
						время
					</span>
				</div>
			</div>
			<div class="col-xs-4">
				<span id="showMinute"><?php echo isset($min)?$min:0?></span><span> м. </span>
				<span id="showSecond"><?php echo isset($sec)?$sec:0?></span><span> с.</span>
			</div>
		</div>
	<!-- Конец панели меню -->
	<!-- Прогресс бар -->
		<div class="container-fluid nopadding">
			<div class="progress nomargin" style="height: 10px; border-radius: 0px;">
			  <div id="progress" class="progress-bar progress-bar-success progress-new" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="line-height: 10px;font-size: 7px;width: %;">
			  </div>
			</div>
		</div>
	<!-- Конец прогресс бара -->

	<!-- Контент -->
		<div class="container-fluid nopadding">
		
		<!-- Слайдер 1 -->
			<div id="slidr-a" class="width-x">
				<?php if(isset($speed) && !empty($speed)): ?>
					<div data-slidr="finish" class="width-x">
						<div class="col-md-8 panel panel-default margin-x">
						  <div class="panel-body">
						   <div class="text-center">
								<h2 class="">Ваша скорость чтения:</h2>
								<h1 id="speed" class="text-success"><?=$speed?></h1>
								<h2>символов в минуту</h2>
								<a class="fc-gs" href="/<?=$hash?>">
									<span class="glyphicon glyphicon-repeat"></span>
								</a>
							</div>
						  </div>
						</div>
					</div>
                          		<script type="text/javascript">
                          			function wpost(msg)	{	VK.api('wall.post',	{message:msg},	function(data){ if (data.response) { console.log(data) }} )	}
                          			$(document).ready(function(){	
                                        var speed = $("#speed").text();
                                        post = "Моя скорость чтения: "+speed+" символов в минуту. Измерено при помощи приложения: https://vk.com/app4295493_9664895" ; 
                                        wpost(post);
                                        console.log('Чо та');
                                      });
                          		</script>
				<?php endif; ?>
				<?php if(!$text->sorry): ?>
				<div data-slidr="welcome" class="width-x">
					<div class="panel panel-default padding-top-s noborder">
					  <div class="panel-heading noborder">Добро пожаловать!</div>
					  <div class="panel-body">
					  	<p>
					  		После нажатия на кнопку "Старт!"<br />
					  		Вам будет предложен текст для чтения на время,<br />
					  		затем задан ряд вопросов по прочитаному.<br />
					  		Жмите "Старт!" и удачи!
					  	</p>
					  	<br>
					  	<div class="text-center">
					  		<a href="#text" class="btn btn-danger" role="button">Старт!</a>
					  	</div>
					  </div>
					</div>
				</div>

				<div data-slidr="text">
					<?php require_once('view/Text.php'); ?>
				</div>

				<div data-slidr="quiz" class="width-x" id="quiz">
					<form method="POST">
					<!-- Слайдер 2 -->
						<div id='slidr-q' class="width-x">
							<?php require_once('view/Quiz.php'); ?>
							<div data-slidr="<?php echo count($questions->questions)+1; ?>" class="width-x">
				<div class="panel panel-default padding-top-s">
					  <div class="panel-heading noborder">Спасибо за ответы!</div>
					  <div class="panel-body text-center">
 						<button type="submit" class="btn btn-default btn-lg">Узнать результаты</button>
					  </div>
					</div>
							</div>
						</div>
					<!-- конец Слайдер 2 -->
					</form>
				</div>
			<?php else: ?>
				<div data-slidr="sorry" class="col-md-8 panel panel-default margin-x">
				  <div class="panel-body">
				   <div class="text-center">
						<p><?=$text->sorry?></p>
					</div>
				  </div>
				</div>
		<?php endif; ?>
			</div>
		<!-- конец Слайдер 1 -->

		</div>
	<!-- Конец котент -->

	</body>
</html>
<?php endif; ?>