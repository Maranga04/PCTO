<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Modifica Studente</title>
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
    .logout-button {
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        background-color: #007BFF;
        border-radius: 5px;
        transition: background-color 0.3s, color 0.3s;
    }
    .logout-button:hover {
        background-color: #0056b3;
        color: #FFD700;
    }
    form {
        width: 80%;
        max-width: 600px;
        background: #fff;
        padding: 20px;
        margin-top: 20px;
        border-radius: 5px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
    }
    label {
        display: block;
        margin-bottom: 8px;
        color: #333;
        font-weight: bold;
    }
    input[type="text"], input[type="email"], select {
        width: 95%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    button {
        color: white;
        background-color: #007bff;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        align-self: center;
    }
    button:hover {
        background-color: #0056b3;
    }
    .back-button {
        align-self: center;
        margin-bottom: 20px;
    }
    .fa-star {
        color: gold;
    }
    .fa-star-giallo {
        color: #FFD700;
    }
    .azienda-verde {
        color: #006400;
        background-color: #90EE90;
    }
    .azienda-rosso {
        color: white;
        background-color: red;
    }
    .azienda-giallo {
        color: black;
        background-color: yellow;
    }
</style>

</head>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var select = document.getElementById('azienda');
    var options = Array.from(select.options).slice(1); // Escludi l'opzione segnaposto iniziale

    // Prima rimuovi tutte le opzioni tranne il placeholder per evitare duplicati
    for (let i = select.options.length - 1; i >= 1; i--) {
        select.remove(i);
    }
    
    options.sort(function(a, b) {
        var score = function(option) {
            if (option.className.includes('azienda-verde')) return 1; 
            if (option.className.includes('azienda-giallo')) return 2;
            if (option.className.includes('fa-star-giallo')) return 3;
            if (option.className === '') return 4;
            if (option.className.includes('azienda-rosso')) return 5; 
            return 5;
        };
        return score(a) - score(b);
    });

    options.forEach(option => select.appendChild(option));
});
</script>


<body>
<div class="header">
    <h1>Gestione Studenti</h1>
    <div>
        <a href="/PCTO/gestione_amministratore" class="logout-button">Home</a>
        <a href="/PCTO/amministratore/studenti" class="logout-button">Studenti</a>
        <a href="/PCTO/logout" class="logout-button">Logout</a>
    </div>
</div>

<!-- Display success or error messages -->
<?php if ($success): ?>
    <div style="color: green; font-weight: bold;">
        <?= ($success)."
" ?>
    </div>
<?php endif; ?>
<?php if ($error): ?>
    <div style="color: red; font-weight: bold;">
        <?= ($error)."
" ?>
    </div>
<?php endif; ?>

<form action="/PCTO/amministratore/studenti/update/<?= ($studente['id']) ?>" method="post">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?= ($studente['nome']) ?>" required>

    <label for="cognome">Cognome:</label>
    <input type="text" id="cognome" name="cognome" value="<?= ($studente['cognome']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= ($studente['email']) ?>" required>

    <label for="classe">Classe:</label>
    <input type="text" id="classe" name="classe" value="<?= ($studente['classe']) ?>" required>

    <label for="azienda">Azienda Attuale:</label>
    <select id="azienda" name="azienda">
        <option value=""><?= ($studente['azienda_attuale'] ?: 'Seleziona Azienda') ?></option>
        <?php foreach (($aziende?:[]) as $azienda): ?>
                <option value="<?= ($azienda['id']) ?>" 
                        <?= ($azienda['id'] == $studente['azienda_attuale'] ? 'selected' : '')."
" ?>
                        class="<?= ($azienda['class']) ?>">
                    <?= ($azienda['nome']) ?><span class="<?= ($azienda['star_class']) ?>"><?= ($azienda['preferita'] ? ' &#9733;' : '') ?></span>
                </option>
            <?php endforeach; ?>
    </select>

    <button type="submit">Salva Modifiche</button>
</form>
</body>
</body>
