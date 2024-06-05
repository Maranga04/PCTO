<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Diario di Bordo</title>
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
    h1 {
        color: #0056b3;
        margin-top: 20px; 
        font-size: 28px;
    }

    .button-container {
        margin-bottom: 20px;
    }
    form {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 350px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    label {
        font-size: 16px;
        color: #333333;
    }
    input[type="date"],
    input[type="time"],
    textarea,
    select {
        padding: 8px;
        border: 2px solid #b0d0ff;
        border-radius: 5px;
        outline: none;
    }
    input[type="date"]:focus,
    input[type="time"]:focus,
    textarea:focus,
    select:focus {
        border-color: #0056b3;
    }
    textarea {
        resize: none;
        height: 100px;
    }
    button, .time-button {
        background-color: #007bff;
        color: white;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    button:hover,
    .time-button:hover {
        background-color: #0056b3;
    }
    .error-message {
        color: red;
        font-size: 14px;  
        margin: 2px 0;  
        padding: 2px;   
        display: block;  
    }
    .message, #message {
        color: green;
        font-size: 16px;
        margin-top: 10px;
        margin-bottom: 10px;
        display: none; /* Ensures it's hidden by default and can be shown via PHP or JS */
    }
</style>

</head>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var form = document.querySelector("form");
    form.onsubmit = function (event) {
        if (!validateTimes()) {
            event.preventDefault(); // Previeni l'invio del form se la validazione fallisce
        }
    };

    function validateTimes() {
        var isValid = true;
        var messageElement = document.getElementById('message');

        // Ottieni i valori dei campi del form
        var entrataMattina = document.getElementById('entrataMattina').value;
        var uscitaMattina = document.getElementById('uscitaMattina').value;
        var entrataPome = document.getElementById('entrataPome').value;
        var uscitaPome = document.getElementById('uscitaPome').value;

        // Reset dei messaggi di errore
        document.getElementById('errorEntrataMattina').textContent = '';
        document.getElementById('errorUscitaMattina').textContent = '';
        document.getElementById('errorEntrataPome').textContent = '';
        document.getElementById('errorUscitaPome').textContent = '';
        messageElement.textContent = '';
        messageElement.style.display = 'none';

        // Controllo degli orari
        if (uscitaMattina <= entrataMattina) {
            document.getElementById('errorUscitaMattina').textContent = "L'uscita del mattino deve essere dopo l'entrata.";
            isValid = false;
        }
        if (uscitaPome <= entrataPome) {
            document.getElementById('errorUscitaPome').textContent = "L'uscita del pomeriggio deve essere dopo l'entrata.";
            isValid = false;
        }

        return isValid; // Solo se isValid è true, il form verrà inviato
    }

    // Gestione del parametro di successo dall'URL
    var urlParams = new URLSearchParams(window.location.search);
    var success = urlParams.get('success');
    if (success === '1') {
        var messageElement = document.getElementById('message');
        messageElement.textContent = 'Il diario di bordo è stato inviato con successo!';
        messageElement.style.color = 'green';
        messageElement.style.display = 'block';
    }
});
</script>

<body>
    <h1>Diario di Bordo</h1>
    <p id="message" style="color: green; display: none;">Tutti i campi sono corretti!</p>
    <div class="button-container">
        <a href="/PCTO/aziende" class="time-button">Home</a>
        <a href="/PCTO/diario_di_bordo/visualizza" class="time-button">Visualizza Diari di Bordo</a>
    </div>

    <form action="{{ @BASE }}/diario_di_bordo-invio" method="post" onsubmit="return validateTimes()">
        <label for="giorno">Giorno:</label>
        <input type="date" id="giorno" name="giorno" required>
        
        <label for="entrataMattina">Orario di entrata del mattino:</label>
        <select id="entrataMattina" name="entrataMattina" required>
                <option value="08:00">08:00</option>
                <option value="08:30">08:30</option>
                <option value="09:00">09:00</option>
                <option value="09:30">09:30</option>
                <option value="10:00">10:00</option>
                <option value="10:30">10:30</option>
                <option value="11:00">11:00</option>
                <option value="11:30">11:30</option>
                <option value="12:00">12:00</option>
                <option value="12:30">12:30</option>
            </select>
        <span id="errorEntrataMattina" class="error-message"></span>
        
        <label for="uscitaMattina">Orario di uscita del mattino:</label>
        <select type="time" id="uscitaMattina" name="uscitaMattina" required>
                <option value="08:30">08:30</option>
                <option value="09:00">09:00</option>
                <option value="09:30">09:30</option>
                <option value="10:00">10:00</option>
                <option value="10:30">10:30</option>
                <option value="11:00">11:00</option>
                <option value="11:30">11:30</option>
                <option value="12:00">12:00</option>
                <option value="12:30">12:30</option>
                <option value="13:00">13:00</option>
            </select>
        <span id="errorUscitaMattina" class="error-message"></span>

        <label for="entrataPome">Orario di entrata del pomeriggio:</label>
        <select type="time" id="entrataPome" name="entrataPome" required>
                <option value="14:00">14:00</option>
                <option value="14:30">14:30</option>
                <option value="15:00">15:00</option>
                <option value="15:30">15:30</option>
                <option value="16:00">16:00</option>
                <option value="16:30">16:30</option>
                <option value="17:00">17:00</option>
                <option value="17:30">17:30</option>
            </select>
        <span id="errorEntrataPome" class="error-message"></span>

        <label for="uscitaPome">Orario di uscita del pomeriggio:</label>
        <select type="time" id="uscitaPome" name="uscitaPome" required>
                <option value="14:30">14:30</option>
                <option value="15:00">15:00</option>
                <option value="15:30">15:30</option>
                <option value="16:00">16:00</option>
                <option value="16:30">16:30</option>
                <option value="17:00">17:00</option>
                <option value="17:30">17:30</option>
                <option value="18:00">18:00</option>
            </select>
        <span id="errorUscitaPome" class="error-message"></span>

        <label for="descrizione">Descrizione delle attività:</label>
        <textarea id="descrizione" name="descrizione" required></textarea>
        
        <button type="submit">Invia</button>
    </form>
</body>
</html>

