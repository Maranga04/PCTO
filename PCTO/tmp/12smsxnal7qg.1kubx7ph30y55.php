<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Le Mie Recensioni</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .table-container {
            width: 80%;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:hover {
            background-color: #f4f4f4;
        }
        .no-aziende-message {
            color: #FF0000;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Le Mie Recensioni</h1>
        <div>
            <a href="/PCTO/aziende" class="button-style">Home</a>
            <a href="/PCTO/logout" class="button-style">Logout</a>
        </div>
    </div>

    <?php if ($no_aziende): ?>
        <div class="no-aziende-message">
            Studente senza azienda assegnata.
        </div>
    <?php endif; ?>
    <?php if (! $no_aziende): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Azienda</th>
                        <th>Voto</th>
                        <th>Commento</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($recensioni?:[]) as $recensione): ?>
                        <tr>
                            <td><?= ($recensione['azienda_nome']) ?></td>
                            <td><?= ($recensione['voto']) ?></td>
                            <td><?= ($recensione['commento']) ?></td>
                            <td>
                                <a href="<?= ($BASE) ?>/recensione/modifica/<?= ($recensione['idAzienda']) ?>" class="button-style">Modifica</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php foreach (($aziende_non_recensite?:[]) as $azienda): ?>
                        <tr>
                            <td><?= ($azienda['nome']) ?></td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <a href="<?= ($BASE) ?>/recensione/crea/<?= ($azienda['id']) ?>" class="button-style">Crea Recensione</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</body>
</html>
