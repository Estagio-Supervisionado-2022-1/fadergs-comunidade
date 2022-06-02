<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fadergs Comunidade</title>
</head>
<?php
    $data = date('d/m/Y', strtotime($appointment['datetime']));
    $horario = date ('H:i', strtotime($appointment['datetime']));
    $endereco = $fullAddress['addresses']['streetName'];
    $cep = $fullAddress['addresses']['zipcode'];
    $bairro = $fullAddress['addresses']['district'];
    $cidade = $fullAddress['addresses']['city'];
    $estado = $fullAddress['addresses']['stateAbbr'];
    $sala = $fullAddress['room'];
    $andar = $fullAddress['floor'];
    $numero = $fullAddress['building_number'];
    $servico = $service['name'];
    $departamento = $service['departaments']['name'];
    $usuario = $user['name'];
?>
<body>
    <h1>Seu agendamento foi atualizado</h1>
    <hr>
    <h3>Fique por dentro das novas informações</h3>
    <p><strong>Nome:</strong> {{$usuario}} </p>
    <hr>
    <p><strong>Serviço:</strong> {{$servico}} </p>
    <p><strong>Departamento:</strong> {{$departamento}} </p>
    <hr>
    <p><strong>Endereço:</strong> {{$endereco}} </p>
    <p><strong>Número:</strong> {{$numero}}</p>
    <p><strong>Cep:</strong> {{$cep}} </p>
    <p><strong>Bairro:</strong> {{$bairro}} </p>
    <p><strong>Cidade:</strong> {{$cidade}} </p>
    <p><strong>Estado:</strong> {{$estado}} </p>
    <hr>
    <p><strong>Data:</strong> {{$data}} </p>
    <p><strong>Horário:</strong> {{$horario}} </p>
    <p><strong>Sala:</strong> {{$sala}} </p>
    <p><strong>Andar:</strong> {{$andar}}º andar </p>
    
    <br>
    <br>
    <p>Em caso de dúvida, entre em contate o administrador.</p>
</body>
</html>