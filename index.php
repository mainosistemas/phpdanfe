<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php  $xml = file_get_contents('./xml.xml') ?>
<?php  $xml2 = file_get_contents('/home/alisson/Área de Trabalho/NF-es/NFe_001-000011672.xml') ?>
<body>
    <form action="/printDanfe.php" method="post">
        <input type="text" name="xml" value='<?=$xml2?>'>
        <button type="submit">Enivar</button>
    </form>
</body>
</html>