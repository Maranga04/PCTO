<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>I tuoi preferiti</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Stili preesistenti */
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
        .azienda h2 {
            color: #0056b3;
        }
        .azienda .star {
            font-size: 24px;
            color: #FFD700; 
            cursor: pointer; 
            display: block; 
            margin-top: 10px; 
        }
        #no-favorites-message {
            width: 100%;
            text-align: center;
            margin-top: 20px;
            padding: 10px 0;
            background-color: #f8f9fa;  /* Light grey background for better visibility */
            color: #343a40;  /* Dark grey text for contrast */
            border: 1px solid #dee2e6;  /* Subtle border */
            border-radius: 5px;  /* Rounded corners */
            font-size: 16px;  /* Reasonable text size */
            display: none;  /* Keep it hidden by default */
        }
        
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    updateNoFavoritesMessage(); // Call this on page load to set the correct initial state.
});

function updateNoFavoritesMessage() {
    // Check how many favorite items are currently visible.
    const favorites = document.querySelectorAll('.azienda:not([style*="display: none"])');
    const noFavoritesMsg = document.getElementById('no-favorites-message');
    if (favorites.length === 0) {
        noFavoritesMsg.style.display = 'block'; // Show the message if no favorites are visible.
    } else {
        noFavoritesMsg.style.display = 'none'; // Hide the message if there are visible favorites.
    }
}

function toggleFavorite(aziendaId, element) {
    fetch(`/PCTO/toggle_favorite?aziendaId=${aziendaId}`, {
        method: 'POST'
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.removed) {
                // If the company is removed from favorites, hide its container.
                element.closest('.azienda').style.display = 'none';
                updateNoFavoritesMessage(); // Update the no favorites message visibility.
            } else {
                // If the company is added to favorites, update the icon to filled star.
                element.classList.add('fas');
                element.classList.remove('far');
            }
        } else {
            // Show an error message if there's a problem.
            alert(data.error || 'Errore nel modificare i preferiti.');
        }
    }).catch(error => {
        console.error('Errore nella richiesta:', error);
        alert('Errore nella connessione al server.');
    });
}

    </script>
</head>
<body>
    <div class="header">
        <h1>I tuoi preferiti</h1>
        <div class="header-nav">
            <a href="/PCTO/aziende" class="button-style" title="Home">Home</a>
        </div>
    </div>
    <div class="aziende-container">
    <?php foreach (($preferiti?:[]) as $azienda): ?>
        <div class="azienda">
            <h2><?= ($azienda['nome']) ?></h2>
            <p><?= ($azienda['descrizione']) ?></p>
            <p>Voto Medio: <?= (sprintf('%.1f', $azienda['voto_medio'])) ?></p>
            <i class="fas fa-star star" onclick="toggleFavorite(<?= ($azienda['id']) ?>, this)"></i>
        </div>
    <?php endforeach; ?>
    <div id="no-favorites-message" style="width: 100%; text-align: center; margin-top: 20px; display: none;">
        Non hai ancora aggiunto preferiti.
    </div>
</div>
</body>
</html>
