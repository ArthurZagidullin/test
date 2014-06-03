<?php
$colors = array('bg-g','bg-r','bg-y','bg-t','bg-o');
for ($i=0; $i < count($questions->questions); $i++) {
	if($questions->getQ($i)):
		$q = $questions->questions[$i];
		$options = new Option($q['id']);
		$bg = $colors[array_rand($colors)];
?>
		<div data-slidr="<?=$i?>" class="">
			<div class="width-x height-m questions <?=$bg?>">
				<div class="center-block fnt-r fc-w ">
				  <h3 class="fnt-b margin-bottom-x"><?=$questions->text_question?></h3>
						<?php foreach ($options->opt as $item): ?>
							<p class="fnt-s-m">
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