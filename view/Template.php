<?php 
	require_once('classes/server.php');
	$text = new Text;
	$questions = new Question($text->id);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			Проверка скорости чтения
		</title>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="/js/slidr.min.js"></script>
		<script src="js/my.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<STYLE TYPE="text/css">
			.bg-gr{
				background-color: #f5f5f5;
			}
			.bg-w{
				background-color: #FFFFFF;
			}
			.bg-g{
				background-color: #2ecc71;
			}
			.bg-r{
				background-color: #e74c3c;
			}
			.bg-y{
				background-color: #f1c40f;
			}
			.bg-t{
				background-color: #1abc9c;
			}
			.bg-o{
				background-color: #f39c12;
			}
			.bg-c{
				background-color: #ecf0f1;
			}
			.fc-w{
				color: #ecf0f1;
			}
			.fc-gs{
				color: #16a085;
			}
			.margin-x
			{
				margin: 40px;
			}
			.margin-top-x{
				margin-top: 30px;
			}
			.margin-top-m{
				margin-top: 20px;
			}
			.nomargin{
				margin: 0;
			}
			.nopadding{
				padding: 0;
			}
			.padding-xx{
				padding: 40px;
			}
			.padding-x{
				padding: 20px;
			}
			.padding-m{
				padding: 15px;
			}
			.padding-top-x{
				padding-top:20px;
			}
			.padding-top-s{
				padding-top:1px;
			}			
			.padding-title{
				padding: 20px 0px 10px 30px;
			}
			.fnt-s-m
			{
				font-size: 14pt;
			}
			.fnt-s-x
			{
				font-size: 22pt;
			}
		</STYLE>
	</head>
	<body class="container-fluid nopadding">
	<!-- Прогресс бар -->
		<div class="container-fluid nopadding">
			<div class="progress nomargin">
			  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: %;">
			    <?php // тут прогресс ?>%
			  </div>
			</div>
		</div>
	<!-- Конец прогресс бара -->
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
	<!-- Контент -->
		<div class="container-fluid nopadding">
			<div id="slidr-a" class="width-x">
				<p data-slidr="text" id="text">
					<?php require_once('view/Text.php'); ?>
				</p>
				<div data-slidr="quiz" class="width-x" id="quiz">
					<form method="POST">
						<div id='slidr-q'>
							<?php require_once('view/Quiz.php'); ?>
							<div data-slidr="<?php echo count($questions->questions)+1; ?>">
								<button type="submit">Finish!</button>
							</div>
						</div>
					</form>
				</div>
				<div data-slidr="other">
					<p>
						Ни туда ни сюда
					</p>
				</div>
			</div>
		</div>
	<!-- Конец котент -->

	</body>
</html>
<?php 
	$ur = new User;
	$qz = new Quiz($questions);
?>

<?php
	if ($_POST) {
		/**/
		foreach ($_POST as $key => $value) {
			$k = Lib::cutAnsw($key);
			$v = Lib::cutAnsw($value);
			$qz->addAnsw($k, $v);
		}
	}
	echo "Правильных ответов: <strong>".count($qz->ra)."</strong>";
?>