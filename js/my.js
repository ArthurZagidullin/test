function nextSlide(s,a){s.slide(a);};
function clearAdr(){history.replaceState(null, null, '/');}
function progress(x){$("#progress").attr({"aria-valuenow":x*10, "aria-valuemin":x, "style":"width:"+x*10+"%;"}).text(x*10+"%");	}		// Создаем слайдер
function sliderCreate(sId,foo){var res = slidr.create(sId,{controls: 'none',overflow: true,theme: '#343',transition:foo}).start();return res; }
var hash, ns = 2, text = 0, quiz = 0, fin = 0, interval = 0, time = 0,ajURL = "classes/ajax.php";
jQuery.fn.exists = function() {   return $(this).length;}
function wpost(msg)
{
	VK.api('wall.post',{message:msg},function(data) { 
		if (data.response) { 
			console.log(data);
		}})
          	console.log(data.error);
}

$(document).ready(function(){
	var sa = sliderCreate("slidr-a","cube");  
	// Если пришел #hash
	$(window).bind("hashchange", function (a) {
		
		hash = window.location.hash.substring(1);
		if(hash){
                  	console.log(hash);
                  	if(hash == "finish"){ }
			else if (hash == "text" && text == 0 ) { //#text
				var s = 0, m = 0, t = 0, ms = $("#showMinute"), ss = $("#showSecond"), tm = $("#time");
				//$.post()
				nextSlide(sa,hash); clearAdr(); 
       			$.post(ajURL,
       					{action:"begin"},
       					function(data){
       						console.log(data);
       						interval = setInterval(function()
								{
									if (s/60 == 1) 
									{
										s=0;
										m++;
										ms.html(m);
									};
									ss.html(s);
									tm.attr("value", t);
									t++;
									s++;
									++time;
								},
								1000
								);
       					})
			}
			else if ( hash == "quiz" && quiz == 0 ) {			 //#quiz
				nextSlide(sa,hash); clearAdr(); text++; quiz++;
       			$.post(ajURL,	// Замутить глобальную переменную URL
       					{action:"end"},
       					function(data){
       						console.log(data);
							clearInterval(interval);
       					})
				
				var sq = sliderCreate("slidr-q","cube"), foo = $("input");
			    foo.change(function(){
			    	progress(ns);
			    	if(ns == 10){ns++}	// Костыль
			    	nextSlide(sq,ns);
			    	ns++;
			    })
			}
			else if ( hash == "final" && fin == 0 ) {nextSlide(sa,hash);clearAdr(); fin++}
		}
	})
  function isAppUser(id)
  {VK.api('users.isAppUser',{user_id: id},function(data) { 
		if (data.response) { 
			console.log(data);
		}});
  }
  function createAlbum(title, desc)
  {
    VK.api('photos.createAlbum',{title:title,description:desc, privacy: 0},function(data) 
	{
		if (data.response) { 
		// data.response is object
			
		}
         	console.log(data.error);
          
	});
  }
  VK.init(function() { 
     // API initialization succeeded 
     // Your code here 
	
    //var bar = isAppUser('9664895');
    //var test = wpost("test");		// постит посты
    //createAlbum('Моя скорость чтения','Результаты тестов приложения https://vk.com/app4295493_9664895');
   // console.log(test);
    
  }, function() { 
     // API initialization failed 
     // Can reload page here 
  }, '5.21'); 
})