<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Elenco Aziende</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
            display: flex;
            justify-content: center;
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
        .welcome-message {
            flex-grow: 1.4;
            text-align: left;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }
        .error-message {
            color: #FFA500; 
            font-size: 14px;
            margin-top: 10px;
            padding: 5px;
            border-radius: 5px;
            text-align: center;
            display: none; /* Nascondi il messaggio di errore di default */
        }
        .azienda .star {
            font-size: 24px;
            color: #FFD700; 
            cursor: pointer; 
            display: block; 
            margin-top: 10px; 
        }

    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(function(dropdown) {
                var dropdownContent = dropdown.querySelector('.dropdown-content');
                dropdown.addEventListener('click', function(event) {
                    dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
                    event.stopPropagation();
                });
            });

            document.addEventListener('click', function(event) {
                dropdowns.forEach(function(dropdown) {
                    var dropdownContent = dropdown.querySelector('.dropdown-content');
                    if (!dropdown.contains(event.target)) {
                        dropdownContent.style.display = 'none';
                    }
                });
            });
        });
        
        function toggleFavorite(aziendaId, element) {
            fetch(`/PCTO/toggle_favorite?aziendaId=${aziendaId}`, {
                method: 'POST'
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Toggle le classi per cambiare l'icona della stella
                    element.classList.toggle('fas');
                    element.classList.toggle('far');
                } else {
                    // Mostra il messaggio di errore se presente
                    const errorMessageDiv = document.getElementById(`error-${aziendaId}`);
                    errorMessageDiv.style.display = 'block';
                    errorMessageDiv.textContent = data.error || 'Errore nel modificare i preferiti.';
                    setTimeout(() => {
                        errorMessageDiv.style.display = 'none';
                    }, 3000);
                }
            }).catch(error => {
                console.error('Errore nella richiesta:', error);
                const errorMessageDiv = document.getElementById(`error-${aziendaId}`);
                errorMessageDiv.style.display = 'block';
                errorMessageDiv.textContent = 'Errore nella connessione al server.';
                setTimeout(() => {
                    errorMessageDiv.style.display = 'none';
                }, 3000);
            });
        }

    </script>
</head>
<body>
<div class="header">
    <h1>STUDENTE</h1>
    <div class="welcome-message"><?= ($message) ?></div>
    <div class="header-nav">
        <a href="/PCTO/preferiti" class="button-style" title="Preferiti">Preferiti</a>
        <div class="dropdown">
            <button class="button-style" title="Diario di Bordo">Diario di Bordo &#9662;</button>
            <div class="dropdown-content">
                <a href="/PCTO/diario_di_bordo">Compila</a>
                <a href="/PCTO/diario_di_bordo/visualizza">Visualizza</a>
            </div>
        </div>
        <a href="/PCTO/recensione" class="button-style" title="Recensione">Recensione</a>
        <a href="/PCTO/logout" class="button-style" title="Logout">Logout</a>
    </div>
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
    <form action="<?= ($BASE) ?>/aziende" method="get" style="display: flex; align-items: center; margin-left: 20px;">
        <input type="text" name="search" placeholder="Cerca azienda" style="padding: 8px; border-radius: 5px; border: 2px solid #b0d0ff; margin-right: 10px;" />
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
        <i class="fa<?= ($azienda['isFavorite'] ? 's' : 'r') ?> fa-star star" onclick="toggleFavorite(<?= ($azienda['id']) ?>, this)"></i>
        <div class="error-message" id="error-<?= ($azienda['id']) ?>"></div>
    </div>
<?php endforeach; ?>

</div>
</body>
</html>
