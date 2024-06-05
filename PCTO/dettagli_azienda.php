<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dettagli Azienda - {{ @nome }}</title>
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
            padding: 45px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .content {
            width: 80%;
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1, h3, p {
            color: #0056b3;
            margin: 10px 0;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 703px; 
        }
        button:hover {
            background-color: #0056b3;
        }
        button, .time-button {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer; 
            transition: background-color 0.3s; 
        }
    </style>
</head>
<body>
    <div class="header">
    <button type="button" onclick="history.back();">Torna Indietro</button>
    </div>
    <div class="content">
        <h1>{{ @nome }}</h1>
        <p>Indirizzo: {{ @indirizzo }}</p>
        <p>Descrizione: {{ @descrizione }}</p>
        <p>Tutor: {{ @tutor_nome && @tutor_cognome ? @tutor_nome . ' ' . @tutor_cognome . ' (' . @tutor_email . ')' : 'Informazioni sul tutor non disponibili' }}</p>
        <h3>Recensioni:</h3>
        <check if="{{ @recensioni }}">
            <true>
                <repeat group="{{ @recensioni }}" value="{{ @recensione }}">
                    <p>{{ @recensione.studente_nome }} {{ @recensione.studente_cognome }}: {{ @recensione.commento }} - Voto: {{ @recensione.voto }}</p>
                </repeat>
            </true>
            <false>
                <p>Nessuna recensione disponibile.</p>
            </false>
        </check>
    </div>
</body>
</html>
