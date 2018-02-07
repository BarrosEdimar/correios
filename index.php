
<?php
require 'vendor/autoload.php';

use Carbon\Carbon;
use FlyingLuscas\Correios\Client;
use FlyingLuscas\Correios\Service;
if (isset($_POST['destination'])) {
    $origin = '85910-070';

    $destination = $_POST['destination'];
    $number = $_POST['number'];

    $largura = 16;
    $altura = 16;
    $comprimento = 16;
    $peso = '2';
    $quantidade = 1;

    function endereco($zipcode, $numero) {
        $correios = new Client;
        $cep = $correios->zipcode()->find($zipcode)['zipcode'];
        $rua = $correios->zipcode()->find($zipcode)['street'];
        $bairro = $correios->zipcode()->find($zipcode)['district'];
        $cidade = $correios->zipcode()->find($zipcode)['city'];
        $estado = $correios->zipcode()->find($zipcode)['uf'];

        echo 'CEP: '    .    $cep    .    '<br>';
        echo 'Rua: '    .    $rua    .    '<br>';
        echo 'Numero: ' .    $numero .    '<br>';
        echo 'Bairro: ' .    $bairro .    '<br>';
        echo 'Cidade: ' .    $cidade .    '<br>';
        echo 'Estado: ' .    $estado .    '<br>';
    }

    function frete($origin, $destination, $largura, $altura, $comprimento, $peso, $quantidade){
        $correios = new Client;

        $return =($correios->freight()
        ->origin($origin)
        ->destination($destination)
        ->services(Service::SEDEX, Service::PAC)
        ->item($largura, $altura, $comprimento, $peso, $quantidade) // largura, altura, comprimento, peso e quantidade
        ->calculate());

        for ($i = 0; $i <= 1; $i++){
            $nome = $return[$i]['name'];
            $price = $return[$i]['price'];
            $deadline = $return[$i]['deadline'];

            if ($price == 0) {
                $erro = $return[$i]['error']['message'];
                echo $erro;
            } else {
                echo 'Tipo de envio: '  .   $nome       .   '<br>';
                echo 'Valor do envio: ' .   $price      .   '<br>';
                echo 'Tempo de envio'   .   $deadline   .  ' dias'  .   '<br>';
                echo '<br>';
            }
        }
    }
} else {
    echo 'Informe seu CEP e Numero';
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="" method="post">
    <label>CEP: </label>
    <input type="number" name="destination">

    <label>Numero: </label>
    <input type="number" name="number">

    <input type="submit" value="calcular">
</form>

<?php if (isset($_POST['destination'])) {endereco($destination, $number);} ?>
<br>
<?php if (isset($_POST['destination'])) {frete($origin, $destination, $largura, $altura, $comprimento, $peso, $quantidade);} ?>

</body>
</html>
