<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Amministratore</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .user-info {
            width: 90%;
            max-width: 600px;
            padding: 20px 40px;
            background-color: #fff;
            color: #0056b3;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .welcome-message {
            font-size: 28px;
            font-weight: bold;
            color: #0056b3;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            height: 100%;
            justify-content: center;
        }
        .menu {
            width: 90%;
            max-width: 600px;
            background-color: #0056b3;
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            padding: 20px;
            text-align: center;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .menu a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin: 10px;
            font-size: 18px;
            border: 2px solid white;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .menu a:hover {
            background-color: #007bff;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
<div class="user-info">
    <div class="welcome-message"><?= ($welcomeMessage) ?></div>
</div>
<div class="menu">
    <a href="/PCTO/logout">Logout</a>
    <a href="/PCTO/gestione_amministratore">Dettagli</a>
</div>
</body>
</html>
