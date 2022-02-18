<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de multiplicar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
    <link rel="stylesheet" href="./css/table.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <div class="contenedor">
        <h4>Calculadora de préstamos</h4>
        <form method="POST" action="./index.php">
            <?php
                if(isset($_GET["error"])){
                    echo '<div class="alert">';
                    echo '<strong>Error!</strong> El número de ser mayor a 0 y menor o igual a 10';
                    echo '</div>';
                }
                    
            ?>
            <div class="form_campos">
                <div class="d-flex">

                <section>
                    <select class="selectpicker show-menu-arrow" 
                            data-style="form-control" 
                            title="Seleccionar Método"
                            name="method"
                            >
                    <option data-tokens="fr" value="fr">Francés</option>
                    <option data-tokens="al" value="al">Alemán</option>
                    <option data-tokens="am" value="am">Americano</option>
                    <option data-tokens="si" value="si">Simple</option>
                    <option data-tokens="co" value="co">Compuesto</option>
                    </select>
                    <label for="numero" class="control_label label_correo">Sistema de amortización:</label>
                </section>
                <section>
                    <div id="datepicker" class="input-group date" data-date-format="dd/mm/yyyy">
                        <input class="form-control" name="date" type="text" readonly />
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                    <label for="numero" class="control_label label_correo">Fecha del desembolso:</label>
                </section>
                </div>
                <div class="d-flex mt-1">
                <section>
                    <select class="selectpicker show-menu-arrow" 
                            data-style="form-control" 
                            title="Seleccionar periodo"
                            name="period"
                            >
                    <option data-tokens="di" value="di">Diario</option>
                    <option data-tokens="se" value="se">Semanal</option>
                    <option data-tokens="qu" value="qu">Quincenal</option>
                    <option data-tokens="me" value="me">Mensual</option>
                    <option data-tokens="an" value="an">Anual</option>
                    </select>
                    <label for="numero" class="control_label label_correo">Periodo:</label>
                </section>
                <section>
                    <input type="number" name="importe" class="control" step="0.01" placeholder="Ej: $2000"  />
                    <label for="numero" class="control_label label_correo">Importe del préstamo:</label>
                </section>
                </div>
                <div class="d-flex mt-1">
                <section>
                    <input type="number" name="interest" class="control" step="any" placeholder="Ej: 12.00%"  />
                    <label for="numero" class="control_label label_correo">Interés (Periodo seleccionado):</label>
                </section>
                <section>
                    <input type="number" name="plazo" class="control" step="0.01" placeholder="Ej: 5"  />
                    <label for="numero" class="control_label label_correo">Plazo (Periodo seleccionado):</label>
                </section>
                </div>
                <button name="ingresar" type="submit">
                    Calcular
                </button>
            </div>
        </form>
    </div>
    <main>
 <div id="wrapper">
  <?php 
    if(isset($_POST['plazo'])) {

     $method = "";
     switch ($_POST['method']) {
         case 'si':
             $method='Interés simple';
             break;
         case 'fr':
             $method='Frances';
             break;
         case 'al':
             $method='Alemán';
             break;
         case 'am':
             $method='Americano';
             break;
         case 'co':
             $method='Interés compuesto';
             break;
     }
     $date = DateTime::createFromFormat('d/m/Y', $_POST['date']);
     $cap = $_POST['importe'];
     $i = $_POST['interest'];
     $period = $_POST['period'];
     $n = $_POST['plazo'];
     $sumInterval = "";
     $rows = "";

    switch ($period) {
        case 'di':
            $sumInterval='P1D';
            break;
        case 'se':
            $sumInterval='P7D';
            break;
        case 'qu':
            $sumInterval='P15D';
            break;
        case 'me':
            $sumInterval='P1M';
            break;
        case 'an':
            $sumInterval='P1Y';
            break;
    }
    $deuda=$cap;
        $d=$date->format('d/m/Y');
        $rows .= <<<EOF
            <tr>
                <td class="lalign">$d</td>
                <td>$cap</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
        EOF;
    function round2($arg) {
        return abs(round($arg, 2));
    }
    function getStringFormated(...$args) {
        return array_map('round2', $args);
    }
    for ($j=0; $j < $n; $j++) { 
        $date->add(new DateInterval($sumInterval));
        
        $d=$date->format('d/m/Y');
        switch ($_POST['method']) {
            case 'si':
                $ip = $i*$cap;
                $amortizacion = $cap/$n;
                $cuota=$ip+$amortizacion;
                $deuda-=$amortizacion;
                break;
            case 'co':
                $ip = $i*$deuda;
                $amortizacion = $cap/$n;
                $cuota=$ip+$amortizacion;
                $deuda-=$amortizacion;
                break;
            case 'al':
                $ip = $i*$deuda;
                $amortizacion=$cap/$n;
                $cuota = $amortizacion + $ip;
                $deuda -= $amortizacion;
                break;
            case 'fr':
                $cuota = $cap*((pow(1+$i, $n)*$i)/(pow(1+$i, $n)-1));
                $ip = $i*$deuda;
                $amortizacion = $cuota-$ip;
                $deuda -= $amortizacion;
                break;
            case 'am':
                $ip = $i*$cap;
                $cuota = ($j == $n - 1) ? $cap + $ip : $ip;
                $amortizacion = $cuota-$ip;
                $deuda -= $amortizacion;
                break;
        }
        $string = getStringFormated($deuda, $amortizacion, $ip, $cuota);
        $rows .= <<<EOF
            <tr>
                <td class="lalign">$d</td>
                <td>$$string[0]</td>
                <td>$$string[1]</td>
                <td>$$string[2]</td>
                <td>$$string[3]</td>
            </tr>
        EOF;
    }
    $table = <<<EOF
        <h1>Préstamo utilizando: $method</h1>
        <table id="keywords" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th><span>Fecha</span></th>
            <th><span>Saldo Restante</span></th>
            <th><span>Amortización</span></th>
            <th><span>Interés</span></th>
            <th><span>Cuota</span></th>
          </tr>
        </thead>
        <tbody>
        $rows
        </tbody>
        </table>
    EOF;
    echo $table;
    }
  ?>
 </div> 
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.28.14/js/jquery.tablesorter.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script src="https://kit.fontawesome.com/0c7c28094d.js" crossorigin="anonymous"></script>
    <script>
          /* Multiple Item Picker */
  $('.selectpicker').selectpicker({
    style: 'btn-default'
  });
  $(function () {
  $("#datepicker").datepicker({ 
      autoclose: true, 
      todayHighlight: true,
      clearBtn: true
  }).datepicker('update', new Date());
});

 $("#datepicker").keyup(function(e){
   console.log("heool");
   if(e.keyCode ==8 || e.keyCode == 46) {
     $("#datepicker").datepicker('update', "");
   }
 });
    </script>
</body>

</html>