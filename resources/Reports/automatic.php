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
    
    <div style="text-align: center;">
        <h2>Reporte <?= $title ?></h2>
    </div>

</div>
<div>
    <table class="default" border="1">
    
    <?php foreach ($data as $d) {?>
    <!-------------------------------------------->
         <thead>
            <tr>
                <td colspan="2">FECHA</td>
                <td colspan="2">SALIDA <?php echo strtoupper(\App\Models\Hole::where('id',$d["hole_id"])->value('name')); ?></td>
            </tr>      
         </thead>
         <!-------------------------------------------->
        
        <?php foreach ($d["groupeddata"] as $groupeddata) {?>
          <tr>
            <td><?= $groupeddata["start_hour"] ?></td>
            <td>REF</td>
            <td>JUGADOR</td>
            <td>RESERVADO POR</td>
          </tr>
        
           <?php foreach ($groupeddata["players"] as $player) {?> 
                <tr>
                    <td> <?= $groupeddata["reservation_id"] ?> </td>
                    <td><?= str_replace('"',"",$player[0]) ?></td>
                    <td><?= strtoupper($player[2].' '.$player[1]) ?></td>
                    <td>Celda 7</td>
                </tr>
            <?php }//foreach($d["groupeddata"] as $groupeddata)?> 
        <?php }//foreach($d["groupeddata"] as $groupeddata)?>
    <?php }//foreach($data as $d)?>

</table>
</div>
</body>
</html>