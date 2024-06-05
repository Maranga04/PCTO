<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Modifica Recensione</title>
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
        .form-container {
            margin-top: 20px;
            width: 80%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }
        .form-container form {
            padding: 0 20px;
        }
        .form-container label {
            display: block;
            margin: 10px 0 5px;
        }
        .form-container input[type="number"],
        .form-container textarea {
            width: calc(100% - 40px);
            padding: 10px;
            margin-bottom: 10px;
            border: 2px solid #b0d0ff;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }
        .form-container button:hover {
            background-color: #0056b3;
            color: #FFD700;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Modifica Recensione per <?= ($azienda['nome']) ?></h1>
        <div>
            <a href="/PCTO/aziende" class="button-style">Home</a>
            <a href="/PCTO/logout" class="button-style">Logout</a>
        </div>
    </div>

    <div class="form-container">
        <form action="/PCTO/recensione/invio" method="post">
            <input type="hidden" name="idAzienda" value="<?= ($azienda['id']) ?>">
            
            <label for="voto">Voto:</label>
            <input type="number" id="voto" name="voto" min="0" max="5" value="<?= ($recensione['voto'] ?: '') ?>" required>
            
            <label for="commento">Commento:</label>
            <textarea id="commento" name="commento" rows="4" cols="50" required><?= ($recensione['commento'] ?: '') ?></textarea>
            
            <button type="submit">Modifica Recensione</button>
        </form>
    </div>
</body>
</html>
