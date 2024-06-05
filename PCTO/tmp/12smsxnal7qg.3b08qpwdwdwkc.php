<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Crea Recensione</title>
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
        input[type="text"], input[type="number"], textarea {
            width: 100%;
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
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Crea Recensione per <?= ($azienda['nome']) ?></h1>
        <div>
            <a href="/PCTO/aziende" class="button-style">Home</a>
            <a href="/PCTO/recensione" class="button-style">Le Mie Recensioni</a>
            <a href="/PCTO/logout" class="button-style">Logout</a>
        </div>
    </div>

    <form action="/PCTO/recensione/invio" method="post">
        <input type="hidden" name="idAzienda" value="<?= ($azienda['id']) ?>">
        
        <label for="voto">Voto:</label>
        <input type="number" id="voto" name="voto" min="1" max="5" required>

        <label for="commento">Commento:</label>
        <textarea id="commento" name="commento" rows="4" required></textarea>

        <button type="submit">Invia Recensione</button>
    </form>
</body>
</html>
