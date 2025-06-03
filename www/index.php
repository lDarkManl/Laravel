<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
    <form action="send.php" method="POST">
	<input placeholder="Фамилия" name="last_name" class="form-control mt-1">

	<input placeholder="Имя" name="name" class="form-control mt-1">

	<input placeholder="Контактный телефон" required name="phone" class="form-control mt-1">

	<input placeholder="Текст" name="text" class="form-control mt-1">

	<button type="submit" class="btn btn-primary mt-1">Отправить</button>
    </form>
    
</body>
</html>
