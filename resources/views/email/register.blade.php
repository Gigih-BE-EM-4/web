<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CariPengalaman.com | Registration</title>
</head>
<body>
    <h1>Hai {{$name}}</h1>
    <p>Akun kamu telah berhasil dibuat, untuk melanjutkan proses registrasi, kamu dapat mengklik tautan dibawah</p>
    <a href="{{url('api/user/verify/'.$hash)}}">{{url('api/user/verify/'.$hash)}}</a>
    <br><br>
    <p>Terimakasih</p>
    <p>CariPengalaman.com</p>
</body>
</html>