<?php			
for ($i=0; $i < count($questions->questions); $i++) {
	$questions->getQ($i);
	$options = new Option($questions->id);
?>
<div data-slidr="<?=$i?>" class="col-md-6 padding-top-s">
	<div class="panel panel-default">
	  <div class="panel-heading"><?=$questions->text_question?></div>
	  <div class="panel-body">
			<?php foreach ($options->opt as $item): ?>
				<p class="">
					<input name="q<?=$questions->id?>" type="radio" value="<?=$item[0]?>">
					<?=$item[2]?>
					</input>
				</p>	
			<?php endforeach; ?>
	  </div>
	</div>
</div>
<?php } ?>