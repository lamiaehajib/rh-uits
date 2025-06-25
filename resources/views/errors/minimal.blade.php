<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        h1 {
            font-size: 80px;
            color: #e74c3c;
        }
        p {
            font-size: 24px;
        }
        
    </style>
</head>
<body>
    <h1>@yield('code')</h1>
    <p>@yield('message')</p>
    <a href="{{ url('/dashboard') }}" style="text-decoration: none; color: #ff0000;">Retourner à l'accueil</a>
</body>
</html>
