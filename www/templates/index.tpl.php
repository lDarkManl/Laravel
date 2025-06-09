<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin>
</head>
<body>
    <h3 class="mt-1 ms-1">Форма отправки сообщений</h3>
    <form method="POST" class="ms-1">
        <?php foreach ($this->formData as $input): ?>
            <div class="input-group mb-3 mt-3 flex-column">
                <label class="form-label"><?$input['placeholder']?></label>
                <<?php echo $input['field'] ?> name="<?php echo $input['name']?>" type="<?php echo $input['type']?>" placeholder="<?php echo $input['placeholder']?>" class="form-control w-25" autocomplete="off" <?php echo $input['requiredAttr']?>></<?php echo $input['field'] ?>>
            </div>
        <?php endforeach;?>
            <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</body>
</html>

