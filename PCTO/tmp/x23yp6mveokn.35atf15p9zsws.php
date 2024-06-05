<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Piattaforma PCTO</title>
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
            margin-left: 15px; 
        }
        .button-style {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #007BFF;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
            display: inline-block;
        }
        .button-style:hover {
            background-color: #0056b3;
            color: #FFD700;
        }
        .header a.button-style {
            margin-right: 15px; 
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
            left: 0;
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
            margin-top: 20px;
            align-self: center;
        }
        select {
            display: none;
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
    </style>
    <script>
        // Gestisce l'apertura e la chiusura del menu a discesa
        document.addEventListener('DOMContentLoaded', function() {
            var dropdown = document.querySelector('.dropdown');
            var dropdownContent = document.querySelector('.dropdown-content');
            dropdown.addEventListener('click', function(event) {
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
                event.stopPropagation();
            });

            document.addEventListener('click', function(event) {
                if (!event.target.matches('.dropdown, .dropdown *')) {
                    dropdownContent.style.display = 'none';
                }
            });
        });
    </script>
</head>
<body>
<div class="header">
    <h1>Piattaforma PCTO</h1>
    <a href="login.php" class="button-style">Login</a>
</div>
<div class="form-container">
    <div class="dropdown">
        <button class="button-style">Ordina per voto</button>
        <div class="dropdown-content">
            <a href="?order=ALPHA">Alfabetico</a>
            <a href="?order=DESC">Più alto</a>
            <a href="?order=ASC">Più basso</a>
        </div>
    </div>
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
