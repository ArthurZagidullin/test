<?php 
	session_start();
	require_once('classes/server.php');
	$text = new Text;
	$questions = new Question($text->id);
	$qz = new Quiz($questions);
	if (count($_POST) >= 9) {
		foreach ($_POST as $key => $value) {
			$k = Lib::cutAnsw($key);
			$v = Lib::cutAnsw($value);
			$qz->addAnsw($k, $v);
		}
		$bt = $_COOKIE['begin'];
		$et = $_COOKIE['end'];
		//удаляем куки
		setcookie('begin','',time()-3600);
		setcookie('end', time()-3600);
          if(($et-$bt)<10){$t = 1;}else{$t = ($et - $bt)/60;}
          	//$t = ($et - $bt)/60;	// время
          	
		$c = count($qz->ra)/10;	// правильные ответы
		$x = $text->lenght;		// количество символов
		$speed = $qz->result($x,$t,$c);
		setcookie('speed',$speed,time()+3600);
		header('Location: /#finish');
	}
	if ($speed = $_COOKIE['speed']) {
		setcookie('speed','',time()-3600);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			Проверка скорости чтения
		</title>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/my.css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="/js/slidr.min.js"></script>
		<script src="js/my.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
          <script src="//vk.com/js/api/xd_connection.js?2"  type="text/javascript"></script>
	</head>
	<body class="container-fluid nopadding">
	<!-- Панель меню -->
		<div class="container-fluid nopadding bg-c">
			<div class="col-xs-8">
				<div class="col-xs-2">
					<a class="fc-gs" href="/?tryagain=1">
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
				<?php if(isset($speed)): ?>
					<div data-slidr="finish" class="width-x">
						<div class="col-md-8 panel panel-default margin-x">
						  <div class="panel-body">
						   <div class="text-center">
								<h2 class="">Ваша скорость чтения:</h2>
								<h1 id="speed" class="text-success"><?=$speed?></h1>
								<h2>символов в минуту</h2>
								<a class="fc-gs" href="#text">
									<span class="glyphicon glyphicon-repeat"></span>
								</a>
							</div>
						  </div>
						</div>
					</div>
                          		<script>
                                          var speed = $("#speed").text();
                                          post = "Моя скорость чтения: "+speed+" символов в минуту. Измерено при помощи приложения: https://vk.com/app4295493_9664895" ; 
                                             wpost(post);
                                          console.log('Чо та');
                          		</script>
				<?php endif; ?>
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
			</div>
		<!-- конец Слайдер 1 -->
		</div>
	<!-- Конец котент -->

	</body>
</html>
