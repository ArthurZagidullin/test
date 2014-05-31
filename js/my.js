function nextSlide(s,a){s.slide(a);};
function clearAdr(){history.replaceState(null, null, '/');}
function progress(x){$("#progress").attr({"aria-valuenow":x*10, "aria-valuemin":x, "style":"width:"+x*10+"%;"}).text(x*10+"%");	}		// Создаем слайдер
function sliderCreate(sId,foo){var res = slidr.create(sId,{controls: 'none',overflow: true,theme: '#343',transition:foo}).start();return res; }
var hash, ns = 2, text = 0, quiz = 0, fin = 0, interval = 0, time = 0,ajURL = "classes/ajax.php";
jQuery.fn.exists = function() {   return $(this).length;}

/* Смена слайдов тут */
$(document).ready(function(){								// грузится дом-дерево
	var sa = sliderCreate("slidr-a","cube"); 				// главный слайд
	$(window).bind("hashchange", function (a) { 			// Если изменился #hash
		hash = window.location.hash.substring(1);	
		if(hash){  	console.log(hash);						// постим hash в консольку
            if(hash == "finish"){ }							// если #finish
			else if (hash == "text" && text == 0 ) { 		// если #text и переменна finish == 0, то есть этот блок отрабатывает только один раз
			/* объявляем всякие переменные */
				var s = 0,								// тут секунды
				m = 0,									// тут минуты
				t = 0,									// тут общее время
				ms = $("#showMinute"),					// объект для показа минут пользователю
				ss = $("#showSecond"),					// в этом показываем секунды
				tm = $("#time");						// а тут видимо время

				nextSlide(sa,hash);							// перелистываем слайд на текст
				clearAdr(); 								// очищаем строку с адресом

			/* при помощи AJAX уведомляем о генерации текста сервер */
       			$.post(ajURL,								// адрес в переменной ajURL, объявлена где-то наверху
       					{action:"begin"},					// передаем параметр 'begin', будет доступен на сервере в $_POST['ation']
       					function(data){							// обрабатываем ответ
       						console.log(data);					// выводим ответ в консольку
       						interval = setInterval(function()	// запускаем таймер записываем его идентификатор в глобальную переменную interval
								{
									if (s == 60)			// если натикало 60 секунд
									{
										s=0;
										m++;				// увеличиваем минуту на 1
										ms.html(m);			// обновляем поле с минутами в браузере пользователя
									};
									ss.html(s);				// показываем секунды
									tm.attr("value", t);	// поле время, будет отправлено на сервер, хз зачем это надо
									t++;
									s++;
									++time;					// чеза выебон?
								},
								1000		// таймер проводит иттерацию каждые 1000 милисекунд, то есть 1 секунда
								);
       					})
			}
			else if ( hash == "quiz" && quiz == 0 ) {				// сюда пользователь переходит из слайда #text передав запрос с #quiz
				nextSlide(sa,hash);									// перелистываем главный слайд
				clearAdr();
				text++; quiz++;										// блокируем повторный переход на слайды #text и #quiz
			/* AJAX-запрос пользователь закончил читать, сервер должен это знать */
       			$.post(ajURL,										// переменная с адресом где-то наверху
       					{action:"end"},								// на сервере $_POST['begin'] == 'end'
       					function(data){								// обрабатываем ответ
       						console.log(data);						// ответ от сервера в консольку
							clearInterval(interval);				// убиваем таймер, что отматывает время
       					})
			/* опрос или QUIZ, погнали */
				var sq = sliderCreate("slidr-q","cube"),	// слайдер с вопросами @sq
				foo = $("input");							// принимаем все инпуты на странице, !!я боюсь, что функция избыточна, т.к. радиобутоны опроса не единственные input на странице
			    foo.change(function(){							// функция реагирует на изменение input, конкретно в моем случае на выбор radiobutton
			    	progress(ns);								// если пользователь выбрал вариант увеличиваем шкалу progress !!увеличение начинается сразу с 20%
			    	if(ns == 10){ns++}							// @ns -- указатель на вопрос, т.е если ns = 1, то показывается первый вопрос. Костыль
			    	nextSlide(sq,ns);							// перелистываем на следующий вопрос
			    	ns++;										// увеличиваем @ns
			    })
			}
			else if ( hash == "final" && fin == 0 ) {nextSlide(sa,hash);clearAdr(); fin++}	// #final, хз, сюда вообще приходят?
		}
	})	
/* Инициализируем API VK */
 	VK.init(function() {}, function() {}, '5.21'); 
/* получаем данные о пользователе */
	function getUser(id)
	{
		VK.api('users.get',{},
		function(data) { 
			if (data.response) { 
				console.log(data);
			}
			console.log(data.error);
		})
	}
/* постим сообщение */
	function wpost(msg)	{	VK.api('wall.post',	{message:msg},	function(data){ if (data.response) { console.log(data) }} )	}
})



	
	

