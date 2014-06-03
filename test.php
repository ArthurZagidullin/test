<!DOCTYPE html>
<html>
    <head>
        <title>
            Проверка скорости чтения
        </title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/my.css">
        <script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script src="js/my.js" type="text/javascript"></script>
    </head>
    <body class="container-fluid nopadding" style="width: 700px;">
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

           <div class="width-x height-m result bg-bh">
               <div class="text-center fnt-r fc-w result-border padding-x">
                 <h1 class="result-text nomargin">Ваша скорость чтения:</h1>
                 <h1 class=" fnt-bi result-speed nomargin">1000</h1>
                 <h1 class="result-text nomargin">символов в минуту</h1>
               </div>      
           </div>

    </body>
</html>