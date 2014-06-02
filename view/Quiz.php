<?php
for ($i=0; $i < count($questions->questions); $i++) {
	if($questions->getQ($i)):
		$q = $questions->questions[$i];
		$options = new Option($q['id']);
?>
		<div data-slidr="<?=$i?>" class="col-md-6 padding-top-s">
			<div class="panel panel-default">
			  <div class="panel-heading"><?=$questions->text_question?></div>
			  <div class="panel-body">
					<?php foreach ($options->opt as $item): ?>
						<p class="">
							<input name="q<?=$q['id']?>" type="radio" value="<?=$item['id']?>">
							<?=$item['text_option']?>
							</input>
						</p>	
					<?php endforeach; ?>
			  </div>
			</div>
		</div>
<?php
 	unset($options);
 	endif;
 }
  ?>