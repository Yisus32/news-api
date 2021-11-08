<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Operaciones</title>
</head>
<style>
    table {
        border-collapse: collapse;
        max-width: 100%;
        min-width: 100%;
        margin-top: 10px;
    }

    th, td {
        text-align: center;
        padding: 3px;
        font-size:12px ;
    }

    tr:nth-child(even){background-color: <?=$colors['secondary']?>;}

    th {
        background-color: <?=$colors['primary']?>;
        color: white;
    }
    h1{
        font-family: apple-system;
        font-size: 2em;
    }
</style>
<body>
<div>
    <img  style="width: 100px;float: right;" src="<?php echo $logo ?>" alt="Logo">
    <div style="text-align: center;">
        <h2>Reporte <?= $title ?></h2>
    </div>

</div>
<div>
    <h5>Fecha de emision:?></h5>
    <h5>Usuario:</h5>
</div>
</body>
</html>
