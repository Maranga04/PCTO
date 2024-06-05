<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Modifica Diario di Bordo</title>
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
            height: 150px;
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
        .error-message, .warning-message {
            color: red;
            font-size: 14px;  
            margin: 2px 0;  
            padding: 2px;   
            display: block;  
        }
        .warning-message {
            color: #ffcc00; /* Yellow */
        }

    </style>
</head>
<script>
let initialData = {};

document.addEventListener("DOMContentLoaded", function() {
    // Capture initial data for comparison
    initialData = {
        giorno: document.getElementById('giorno').value,
        entrataMattina: document.getElementById('entrataMattina').value,
        uscitaMattina: document.getElementById('uscitaMattina').value,
        entrataPome: document.getElementById('entrataPome').value,
        uscitaPome: document.getElementById('uscitaPome').value,
        descrizione: document.getElementById('descrizione').value
    };
});

function validateTimes() {
    var isValid = true;
    var hasChanged = false;
    var entrataMattina = document.getElementById('entrataMattina').value;
    var uscitaMattina = document.getElementById('uscitaMattina').value;
    var entrataPome = document.getElementById('entrataPome').value;
    var uscitaPome = document.getElementById('uscitaPome').value;
    var descrizione = document.getElementById('descrizione').value;

    // Reset error messages
    document.getElementById('errorEntrataMattina').textContent = '';
    document.getElementById('errorUscitaMattina').textContent = '';
    document.getElementById('errorEntrataPome').textContent = '';
    document.getElementById('errorUscitaPome').textContent = '';
    document.getElementById('warningMessage').textContent = '';
    
    if (uscitaMattina <= entrataMattina) {
        document.getElementById('errorUscitaMattina').textContent = "L'uscita del mattino deve essere dopo l'entrata.";
        isValid = false;
    }
    if (uscitaPome <= entrataPome) {
        document.getElementById('errorUscitaPome').textContent = "L'uscita del pomeriggio deve essere dopo l'entrata.";
        isValid = false;
    }

    // Check if any data has changed
    if (initialData.giorno === document.getElementById('giorno').value &&
        initialData.entrataMattina === entrataMattina &&
        initialData.uscitaMattina === uscitaMattina &&
        initialData.entrataPome === entrataPome &&
        initialData.uscitaPome === uscitaPome &&
        initialData.descrizione === descrizione) {
        document.getElementById('warningMessage').textContent = "Nessuna modifica eseguita.";
        document.getElementById('warningMessage').style.display = 'block';
        hasChanged = false;
    } else {
        hasChanged = true;
    }

    return isValid && hasChanged; // Return false to prevent form submission if validation fails or nothing has changed
}
</script>

<body>
    <h1>Modifica Diario di Bordo</h1>
    <div class="button-container">
        <a href="/PCTO/aziende" class="time-button">Home</a>
        <a href="/PCTO/diario_di_bordo/visualizza" class="time-button">Visualizza Diari di Bordo</a>
        <a href="/PCTO/diario_di_bordo" class="time-button">Compila Diario di Bordo</a>
    </div>
    <form action="{{ @BASE }}/diario_di_bordo/modifica/{{ @diario.id }}" method="post" onsubmit="return validateTimes();">
    <p id="warningMessage" class="warning-message" style="display: none;"></p>
    <label for="giorno">Giorno:</label>
    <input type="date" id="giorno" name="giorno" required value="{{ @diario.giorno }}">

    <label for="entrataMattina">Orario di entrata del mattino:</label>
    <select id="entrataMattina" name="entrataMattina" required>
        <option value="{{ @diario.entrataMattina }}">{{ @diario.entrataMattina }}</option>
        <option value="08:00" {{ @diario.entrataMattina == '08:00' ? 'selected' : '' }}>08:00</option>
        <option value="08:30" {{ @diario.entrataMattina == '08:30' ? 'selected' : '' }}>08:30</option>
        <option value="09:00" {{ @diario.entrataMattina == '09:00' ? 'selected' : '' }}>09:00</option>
        <option value="09:30" {{ @diario.entrataMattina == '09:30' ? 'selected' : '' }}>09:30</option>
        <option value="10:00" {{ @diario.entrataMattina == '10:00' ? 'selected' : '' }}>10:00</option>
        <option value="10:30" {{ @diario.entrataMattina == '10:30' ? 'selected' : '' }}>10:30</option>
        <option value="11:00" {{ @diario.entrataMattina == '11:00' ? 'selected' : '' }}>11:00</option>
        <option value="11:30" {{ @diario.entrataMattina == '11:30' ? 'selected' : '' }}>11:30</option>
        <option value="12:00" {{ @diario.entrataMattina == '12:00' ? 'selected' : '' }}>12:00</option>
        <option value="12:30" {{ @diario.entrataMattina == '12:30' ? 'selected' : '' }}>12:30</option>
    </select>
    <p id="errorEntrataMattina" class="error-message"></p>

    <label for="uscitaMattina">Orario di uscita del mattino:</label>
    <select id="uscitaMattina" name="uscitaMattina" required>
        <option value="{{ @diario.uscitaMattina }}">{{ @diario.uscitaMattina }}</option>
        <option value="08:30" {{ @diario.uscitaMattina == '08:30' ? 'selected' : '' }}>08:30</option>
        <option value="09:00" {{ @diario.uscitaMattina == '09:00' ? 'selected' : '' }}>09:00</option>
        <option value="09:30" {{ @diario.uscitaMattina == '09:30' ? 'selected' : '' }}>09:30</option>
        <option value="10:00" {{ @diario.uscitaMattina == '10:00' ? 'selected' : '' }}>10:00</option>
        <option value="10:30" {{ @diario.uscitaMattina == '10:30' ? 'selected' : '' }}>10:30</option>
        <option value="11:00" {{ @diario.uscitaMattina == '11:00' ? 'selected' : '' }}>11:00</option>
        <option value="11:30" {{ @diario.uscitaMattina == '11:30' ? 'selected' : '' }}>11:30</option>
        <option value="12:00" {{ @diario.uscitaMattina == '12:00' ? 'selected' : '' }}>12:00</option>
        <option value="12:30" {{ @diario.uscitaMattina == '12:30' ? 'selected' : '' }}>12:30</option>
        <option value="13:00" {{ @diario.uscitaMattina == '13:00' ? 'selected' : '' }}>13:00</option>
    </select>
    <p id="errorUscitaMattina" class="error-message"></p>

    <label for="entrataPome">Orario di entrata del pomeriggio:</label>
    <select id="entrataPome" name="entrataPome" required>
        <option value="{{ @diario.entrataPome }}">{{ @diario.entrataPome }}</option>
        <option value="14:00" {{ @diario.entrataPome == '14:00' ? 'selected' : '' }}>14:00</option>
        <option value="14:30" {{ @diario.entrataPome == '14:30' ? 'selected' : '' }}>14:30</option>
        <option value="15:00" {{ @diario.entrataPome == '15:00' ? 'selected' : '' }}>15:00</option>
        <option value="15:30" {{ @diario.entrataPome == '15:30' ? 'selected' : '' }}>15:30</option>
        <option value="16:00" {{ @diario.entrataPome == '16:00' ? 'selected' : '' }}>16:00</option>
        <option value="16:30" {{ @diario.entrataPome == '16:30' ? 'selected' : '' }}>16:30</option>
        <option value="17:00" {{ @diario.entrataPome == '17:00' ? 'selected' : '' }}>17:00</option>
        <option value="17:30" {{ @diario.entrataPome == '17:30' ? 'selected' : '' }}>17:30</option>
    </select>
    <p id="errorEntrataPome" class="error-message"></p>

    <label for="uscitaPome">Orario di uscita del pomeriggio:</label>
    <select id="uscitaPome" name="uscitaPome" required>
        <option value="{{ @diario.uscitaPome }}">{{ @diario.uscitaPome }}</option>
        <option value="14:30" {{ @diario.uscitaPome == '14:30' ? 'selected' : '' }}>14:30</option>
        <option value="15:00" {{ @diario.uscitaPome == '15:00' ? 'selected' : '' }}>15:00</option>
        <option value="15:30" {{ @diario.uscitaPome == '15:30' ? 'selected' : '' }}>15:30</option>
        <option value="16:00" {{ @diario.uscitaPome == '16:00' ? 'selected' : '' }}>16:00</option>
        <option value="16:30" {{ @diario.uscitaPome == '16:30' ? 'selected' : '' }}>16:30</option>
        <option value="17:00" {{ @diario.uscitaPome == '17:00' ? 'selected' : '' }}>17:00</option>
        <option value="17:30" {{ @diario.uscitaPome == '17:30' ? 'selected' : '' }}>17:30</option>
        <option value="18:00" {{ @diario.uscitaPome == '18:00' ? 'selected' : '' }}>18:00</option>
    </select>
    <p id="errorUscitaPome" class="error-message"></p>

    <label for="descrizione">Descrizione delle attività:</label>
    <textarea id="descrizione" name="descrizione" required>{{ @diario.descrizione }}</textarea>

    <button type="submit">Salva Modifiche</button>
</form>

</body>
</html>
