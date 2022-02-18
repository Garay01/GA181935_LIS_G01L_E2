<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de Potencia</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <div class="contenedor">
        <div class="item">
        <?php
            $num = $_POST["numero"];
            if ($num < 0 || $num > 10) {
                $host  = $_SERVER['HTTP_HOST'];
                $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'index.php';
                header("Location: http://$host$uri/$extra?error=true");
                exit;
            }
            echo "<h4>Tabla del número $num </h4>";
            $i = 1;
            $res= function($num) {
                return $num;
            };
            while ($i <= 10) {
                echo "<div>";
                echo "<bold>$num x {$i}</bold>=<strong>{$res($num*$i++)}</strong>";
                echo "</div>";
            }
            
        ?>
        </div>

    </div>
</body>

</html>