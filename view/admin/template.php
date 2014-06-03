<!DOCTYPE html>
<html>
	<head>
		<title>
			Проверка скорости чтения
		</title>
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/my.css">
		<script src="/js/jquery-1.11.1.min.js"></script>
		<script src="/js/bootstrap.min.js" type="text/javascript"></script>
	</head>
	<body class="container-fluid nopadding">
	<!--Модальные окна-->
		<div class="modal fade bs-example-modal-sm" id="modal-delete"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-sm">
		    <div class="modal-content">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		      <div class="text-center">
		      	<h3>$text_name?</h3>
		      </div>
		      <div class="modal-footer ">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Оставить</button>
		        <form>
		        	<input></input>
		        	<button type="button" class="btn btn-danger">Удалить</button>
		        </form>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->


	<!--Список текстов-->
		<table class="table table-striped table-hover">
			<tr>
				<th>
					№
				</th>
				<th>
					Название
				</th>
				<th>
					Символов
				</th>
				<th>
					Вопросов
				</th>
				<th>
					Статус
				</th>
				<th>
					Управление
				</th>
			</tr>
			<tr>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					<a href="" class="btn btn-primary btn-xs">Редактировать </a>
					<a href="" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete"> Удалить</a>
				</td>
			</tr>
			<tr>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					<a href="" class="btn btn-primary btn-xs">Редактировать </a>
					<a href="" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete"> Удалить</a>
				</td>
			</tr>
		</table>	
	</body>
</html>