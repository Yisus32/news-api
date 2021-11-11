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
    <table class="default" id="table_1">
        <?php 
            $i = 0; 
        ?>

        <?php foreach ($data as $d) { ?> 
            <?php if (!$d->isEmpty()) { ?>

                 <?php foreach (json_decode($d[$i]->guests) as $guest){
                    $players[] = $guest;
                } 

                    foreach (json_decode($d[$i]->partners) as $partner) {
                        $players[] = $partner;
                    }
                ?>

                <thead>
                    <tr>
                        <td>FECHA</td>
                        <td colspan="3">SALIDA <?= strtoupper($d[$i]->hole_name)?></td>
                    </tr>
                </thead>
             <tr>
                <td><?= $d[$i]->start_hour?></td>
                <td>REF</td>
                <td>PLAYERS</td>
                <td>RESERVADO POR</td>
            </tr>
            <?php foreach ($d as $info) {?>
            <tr>
                <td>
                   TEE 028 
                </td>
                <td>
                    <?= $info['id'] ?> 
                </td>
                <td>
                    <table>
                        <?php foreach ($players as $player) {?>
                           <tr>
                                <td><?= $player ?> </td>
                           </tr>
                        <?php } ?> 
                    </table>
                </td>
                <td>
                    <?= $info['owner'] ?> 
                </td>
            </tr>
        <?php }
            $i++; 
            }
        }?>
    </table>
</div>
</body>
</html>