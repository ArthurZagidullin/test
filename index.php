<?php 
	/* Запускаем сессию и подключаем файл с классами */
	session_start();
	require_once('classes/MysqliDb.php');
	require_once('classes/server.php');
	/* Если пришел ID пользователя запустившего приложение */
	if (isset($_GET['viewer_id'])) {
		$_SESSION['uid'] = htmlspecialchars($_GET['viewer_id']);	// uid -- user id
		//var_dump($_SESSION['uid'] );
	}
	/* если в сессии есть UID пользователя */
	if(isset($_SESSION['uid'])):
		$user = new User($_SESSION['uid']);							// Создаем объект пользователя передавая его uid в конструктор

		//Lib::Debug($user->old);

		$text = new Text($user);
		$hash = isset($text->sorry)?'#sorry':'#text';

		$questions = new Question($text->id);
		$qz = new Quiz($questions);

		/* Добавляем информацию о сессии пользователя */
		$user->setTid($text->id);

		/* Пользователь ответил на все вопросы */
		if ((count($_POST) > 9) && ( $bt = $_COOKIE['begin'] ) && ( $et = $_COOKIE['end'] )) {

			$qz->checkAnsw($_POST);

    	    if(($et-$bt)<10)										// если время меньше 10 секунд, сделать 
    	    	$rt = 1;		
    	  	else
    	  		$rt = ($et - $bt)/60;			
    	   	//Lib::Debug($qz->ra);
    	   	//Lib::Debug($questions->questions);

    	   	$x = $text->lenght;										// количество символов
    	   	if(($cu = count($qz->ra)) !== 0)
			{	
				$cu = $cu/10;										// правильные ответы
				$speed = $qz->result($x,$rt,$cu);
			}
			else
			{
				$speed = 10; 	# Вы прочитайте "Войну и Мир", а я к тому времени уже помру.
			}
			

			/* Добавляем информацию о сессии пользователя */
			if(!$user->setOld($rt,$cu,$speed))
				var_dump($cu,$x,$speed);

			setcookie('speed',$speed,time()+3600);
			setcookie('begin','',time()-3600);
			setcookie('end', time()-3600);

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
	</head>
	<body class="container-fluid nopadding width-x">
		<div id="page-preloader" class="width-x container-fluid"><span class="spinner"></span></div>
		<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>

	<!-- Панель меню -->
		<div class="container-fluid nopadding bg-y">
			<div class="col-xs-10">

					<a class="fc-w" href="/">
						<span class="glyphicon glyphicon-repeat"></span>
					</a>

			</div>
			<div class="col-xs-2 fc-w text-right fnt-12 fnt-b">
				<span id="showMinute"><?php echo isset($min)?$min:0?></span><span> м. </span>
				<span id="showSecond"><?php echo isset($sec)?$sec:0?></span><span> с.</span>
			</div>
		</div>
	<!-- Конец панели меню -->
	<!-- Прогресс бар -->
		<div class="container-fluid nopadding">
			<div class="progress nomargin" style="height: 3px; border-radius: 0px;">
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
           				<div class="width-x height-m result bg-bh">
           				    <div class="text-center fnt-r fc-w result-border padding-x">
           				      <h1 class="result-text nomargin">Ваша скорость чтения:</h1>
           				      <h1 id="speed" class=" fnt-bi result-speed nomargin"><?=$speed?></h1>
           				      <h1 class="result-text nomargin">символов в минуту</h1>
           				    </div>      
           				</div>
					</div>
                   <script type="text/javascript">
                   	function wpost(msg)	{	VK.api('wall.post',	{message:msg},	function(data){ if (data.response) { console.log(data) }} )	}
                   	function howDays(speed,book,hid){ var countDay = speed*(hid*60);  return round(book/countDay); }
                   	var declOfNum = function(number, titles)
					{  
					    var  cases = [2, 0, 1, 1, 1, 2];  
					    return titles[ 
					            (number % 100 > 4 && number % 100 < 20) 
					            ? 
					            2 
					            : 
					            cases[(number % 10 < 5) ? number % 10 : 5] 
					    ];  
					} 
                   	$(document).ready(function(){
                   			//hour in day
                            var speed = <?=$speed?>, WaP = 2500000, hid = 2;
                            var days = howDays(speed,WaP,hid);
                            post = "Я бы прочитал \"Войну и Мир\" за " + days + declOfNum(days, ['день', 'дня', 'дней']) + ". Моя сорость чтения: " + speed + " символов в минуту. А как быстро читаешь ты? Проверь тут — https://vk.com/app4295493";
                            //post = "Моя скорость чтения: "+speed+" символов в минуту. Измерено при помощи приложения: https://vk.com/app4295493_9664895" ; 
                              /* Инициализируем API VK */
 								VK.init(function() {

 								 wpost(post);  console.log('Пост');


 								}, function() {}, '5.21'); 
                            });
                   </script>
				<?php endif; ?>
				<?php if(!$text->sorry): ?>
				<div data-slidr="welcome" class="width-x">
					<div class="width-x height-m welcome bg-bh">
						<div class="text-center fnt-r fc-w ">
						  	<h1 class="fnt-b welcome-header">Добро пожаловать!</h1>
					  		<p class="fnt-l center-block welcome-text text-center">
					  			После нажатия на кнопку "Старт!"<br />
					  			Вам будет предложен текст для чтения на время,<br />
					  			затем задан ряд вопросов по прочитаному.<br />
					  			Жмите старт и удачи!
					  		</p>						  
					  		<a href="#text" class="btn btn-danger btn-lg fnt-s-x inline-block margin-top-s" role="button">СТАРТ</a>
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
								<div class="width-x height-m questions bg-bh">
									<div class="text-center fnt-r fc-w ">
									  <h1 class="fnt-b final-thanks">Спасибо за ответы!</h1>
									  <button type="submit" class="btn btn-danger btn-lg fnt-s-x">Узнать результаты</button>
									</div>		
								</div>
							</div>
						</div>
					<!-- конец Слайдер 2 -->
					</form>
				</div>
			<?php else: ?>
				<div data-slidr="sorry" class="width-x">
           			<div class="width-x height-m result bg-bh">
           			    <div class="text-center fnt-r fc-w sorry padding-x">
           			      <h1 class=" fnt-bi  margin-bottom-x"><?=$text->sorry?></h1>
           			      <span class="glyphicon glyphicon-pencil sorry-pencil"></span>
           			    </div>      
           			</div>
				</div>
		<?php endif; ?>
			</div>
		<!-- конец Слайдер 1 -->

		</div>
	<!-- Конец котент -->
		<script type="text/javascript" src="/js/slidr.min.js"></script>
        <script src="//vk.com/js/api/xd_connection.js?2"  type="text/javascript"></script>
        <script src="js/my.js" type="text/javascript"></script>
        <script>
        	VK.init(function(){});
				function autosize(width) {
				    //Проверяем элемент body на наличие.
				    if (!document.getElementById('body')) {
				        alert('error');
				        return;
				    }
				    // Успешно ли подключен ВК скрипт
				    if (typeof VK.callMethod != 'undefined') {
				    /*
				    Вызываем функцию vk js api для управления окном.
				    VK.callMethod('функция', параметры)
				    В данном случае у нас - VK.callMethod('изменение_размеров_окна', ширина, высота);
				    Так же добавляем еще 60 пикселей что бы было небольшое расстояние.
				    */
				        VK.callMethod('resizeWindow', 840, document.getElementById('body').clientHeight + 60);
				    } else {
				    alert('error #2');
				    }
			}
			$(document).ready( function(){
			    //Вызываем функцию регулировки высоты каждые пол секунды.
			    setInterval('autosize(607)', 500);
			});
          </script>
	</body>
</html>
<?php endif; ?>