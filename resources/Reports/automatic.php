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
    <table class="default" id="table_1" border="1">

        <?php
        foreach ($data as $d) { ?> 
            <?php if ($d) { ?>

                <thead>
                    <tr>
                        <td>FECHA</td>
                        <td colspan="3">SALIDA <?= strtoupper($d->hole_name)?></td>
                    </tr>
                </thead>
             <tr>
                <td><?= $d->start_hour?></td>
                <td>REF</td>
                <td>PLAYERS</td>
                <td>RESERVADO POR</td>
            </tr>
            <tr>
                <td>
                   TEE 028 
                </td>
                <td>
                    <table>
                         <?php 
                            $regex = "/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i";
                            $partners = explode(',', $d->partners_name);
                            $guests = explode(',', $d->guests_name);
                            $_partner = [];
                            $_guest = [];

                            foreach ($partners as $partner) {
                                $partners = explode(' ', $partner);
                            }
                            
                            foreach ($guests as $guest) {
                                $guests = explode(' ', $guest);
                            }

                           
                            foreach ($partners as $partner) {
                                if (!preg_match($regex, $partner)) {
                                    $_partner[] = $partner;
                                }
                            }

                            foreach ($guests as $guest) {
                                if (!preg_match($regex, $guest)) {
                                    $_guest[] = $guest;
                                }
                            }

                            foreach ($_partner as $p) {
                              $player[] = $p;
                            }

                            foreach ($_guest as $g) {
                                $player[] = $g;
                            }
                            

                            foreach ($player as $p) { ?> 
                                   <tr>
                                       <td> <?php 
                                       if (preg_match('!\d+!', $p)) {
                                           echo $p;
                                       }?></td>
                                   </tr> 
                           <?php } ?>                             
                    </table>
                </td>
                <td>
                    <table>
                        <?php 

                        foreach (explode(',',$d->partners_name.','.$d->guests_name) as $player) {?>
                           <tr>
                                <td><?php echo $player ?> </td>
                           </tr>
                        <?php } ?> 
                    </table>
                </td>
                <td>
                    <?= $d->owner ?> 
                </td>
            </tr>
        <?php  
            }

        }?>
    </table>
</div>
</body>
</html>