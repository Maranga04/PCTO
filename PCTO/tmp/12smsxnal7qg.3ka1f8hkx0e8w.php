<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>PCTO Aziende</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
        }
        .header {
            width: 100%;
            background-color: #0056b3;
            color: white;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin-left: 2%;
            flex-grow: 1;
        }
        .welcome-message {
            flex-grow: 1.4;
            text-align: left;
            font-size: 20px;
            font-weight: bold;
        }
        .logout-button, .order-button, .student-button {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #007BFF;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .logout-button:hover, .order-button:hover, .student-button:hover {
            background-color: #0056b3;
            color: #FFD700;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            background-color: white;
        }
        .dropdown-content a:hover {
            background-color: #007BFF;
            color: white;
        }
        .form-container {
            position: relative;
            margin-top: 20px;
        }
        .aziende-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 80%;
            margin-top: 20px;
        }
        .azienda {
            width: calc(28% - 20px);
            background: #fff;
            padding: 20px;
            margin: 18px;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .azienda a {
            text-decoration: none; 
            color: #0056b3; 
        }

        .azienda a:hover {
            text-decoration: underline;
        }

        .azienda p {
            color: black;
        }
        .azienda h2 {
            color: #0056b3;
        }
        .form-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            width: 100%;
        }

        .order-button {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #007BFF;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .search-form { 
            display: flex;
            align-items: center;
            margin-left: 20px;
        }

        .search-input {
            padding: 8px;
            border-radius: 5px;
            border: 2px solid #b0d0ff;
            margin-right: 10px;
        }

        .button-style {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #007BFF;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .button-style:hover {
            background-color: #0056b3;
            color: #FFD700;
        }

    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var orderBtn = document.querySelector('.order-button');
            var dropdownContent = document.querySelector('.dropdown-content');
            orderBtn.addEventListener('click', function(event) {
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
                event.stopPropagation();
            });

            document.addEventListener('click', function(event) {
                if (!event.target.matches('.order-button, .dropdown *')) {
                    dropdownContent.style.display = 'none';
                }
            });
        });
    </script>
</head>
<body>
<div class="header">
    <h1>AMMINISTRATORE</h1>
    <div class="welcome-message"><?= ($welcomeMessage) ?></div>
    <div class="button-container">
        <a href="/PCTO/amministratore/studenti" class="student-button">Studenti</a>
        <a href="/PCTO/logout" class="logout-button">Logout</a>
    </div>
</div>
<div class="form-container">
    <div class="dropdown">
        <button class="order-button">Ordina per voto</button>
        <div class="dropdown-content">
            <a href="?order=ALPHA">Alfabetico</a>
            <a href="?order=DESC">Più alto</a>
            <a href="?order=ASC">Più basso</a>
        </div>
    </div>
    <form action="<?= ($BASE) ?>/gestione_amministratore" method="get" class="search-form">
        <input type="text" name="search" placeholder="Cerca azienda" value="<?= ($search) ?>" class="search-input" />
        <button type="submit" class="button-style">Cerca</button>
    </form>
</div>
<div class="aziende-container">
    <?php foreach (($aziende?:[]) as $azienda): ?>
        <div class="azienda">
        <a href="<?= ($BASE) ?>/azienda/<?= ($azienda['id']) ?>">
                <h2><?= ($azienda['nome']) ?></h2>
                <p><?= ($azienda['descrizione']) ?></p>
                <p>Voto Medio: <?= (sprintf('%.1f', $azienda['voto_medio'])) ?></p>
            </a>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
