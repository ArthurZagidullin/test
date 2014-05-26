<div class="">
	<div class="panel panel-default padding-top-s">
	  <div class="panel-heading">Внимательно прочитайте текст:</div>
	  <div class="panel-body">
	  	<h4> <?=$text->name?></h4>
	  	<p>
		   <?=$text->text?>
	  	</p>
	  	<br>
	  	<div class="text-right">
	  	 	<a href="#quiz" class="btn btn-success" role="button">Готово!</a>
		</div>
	  </div>
	</div>
</div>


	<!-- Таймер @динамическая величина -->
		<script>
			/*var s = 0;
			var m = 0;
			var t = 0;
			setInterval(function()
			{
				if (s/60 == 1) 
				{
					s=0;
					m++;
					$("#showMinute").html(m);
				};
				$("#showSecond").html(s);
				$("#time").attr("value", t);
				t++;
				s++;
			},
			1000
			);*/
		</script>
