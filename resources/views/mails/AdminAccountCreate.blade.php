<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Account</title>
</head>
<body>
    <h1>Conta criada com sucesso!</h1>
    <hr>
    <h3>Anote seus dados de acesso</h3>
    <p><strong>Login:</strong> {{$loginData['admin_login']}} </p>
    <p><strong>Senha:</strong> {{$loginData['admin_password']}} </p>
    <br>
    <br>
    <strong>Após o seu primeiro acesso, altere sua senha para sua segurança.</strong>
    <br>
    <p>Em caso de dúvida, contate o administrador.</p>
</body>
</html>