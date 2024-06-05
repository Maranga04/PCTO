<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Diari di Bordo</title>
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
            margin-top: 0px;
        }
        .diari-container {
            width: 80%;
            margin-top: 10px;
        }
        .diario {
            background: #fff;
            padding: 20px;
            margin: 18px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .diario h2 {
            color: #0056b3;
        }
        button, .time-button, .modifica-button {
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
        .time-button:hover,
        .modifica-button:hover {
            background-color: #0056b3;
        }
        .modifica-button {
            margin-top: 10px; /* Ensures buttons are in line */
        }
    </style>
</head>
<body>
    <h1>Diari di Bordo</h1>
    <div class="button-container">
        <a href="/PCTO/aziende" class="time-button">Home</a>
        <a href="/PCTO/diario_di_bordo" class="time-button">Compila Diario</a>
    </div>
    <div class="diari-container">
        <check if="{{ @diari }}">
            <true>
                <repeat group="{{ @diari }}" value="{{ @diario }}">
                    <div class="diario">
                        <h2>Data: {{ @diario.giorno }}</h2>
                        <p>Entrata Mattina: {{ @diario.entrataMattina }}</p>
                        <p>Uscita Mattina: {{ @diario.uscitaMattina }}</p>
                        <p>Entrata Pomeriggio: {{ @diario.entrataPome }}</p>
                        <p>Uscita Pomeriggio: {{ @diario.uscitaPome }}</p>
                        <p>Descrizione: {{ @diario.descrizione | esc }}</p>
                        <a href="{{ @BASE }}/diario_di_bordo/modifica/{{ @diario.id }}" class="modifica-button">Modifica</a>
                    </div>
                </repeat>
            </true>
            <false>
                <p>Nessun diario di bordo registrato.</p>
            </false>
        </check>
    </div>
</body>
</html>
