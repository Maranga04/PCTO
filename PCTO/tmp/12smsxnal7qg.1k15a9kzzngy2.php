<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Studenti</title>
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
            margin-left: 2%;
            flex-grow: 1;
        }
        .logout-button{
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #007BFF;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .logout-button:hover{
            background-color: #0056b3;
            color: #FFD700;
        }
        .form-container {
            width: 80%;
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .form-container form {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        input[type="text"] {
            padding: 8px;
            border-radius: 5px;
            border: 2px solid #b0d0ff;
            margin-right: 10px;
        }
        button {
            color: white;
            background-color: #007BFF;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
            color: #FFD700;
        }
        .aziende-container {
            width: 80%;
            margin-top: 10px;
        }
        table {
            width: 100%;
            background: #fff;
            padding: 20px;
            margin: 18px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        a.modifica-button {
            color: white;
            background-color: #007bff;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        a.modifica-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Gestione Studenti</h1>
    <div>
        <a href="/PCTO/gestione_amministratore" class="logout-button">Home</a>
        <a href="/PCTO/logout" class="logout-button">Logout</a>
    </div>
    
</div>
<div class="aziende-container">
<form action="<?= ($BASE) ?>/amministratore/studenti" method="get">
    <button type="submit">Cerca</button>
    <button type="button" onclick="resetForm(this.form)">Reset</button>
    <input type="text" name="nome" placeholder="Nome" value="<?= ($nome) ?>" />
    <input type="text" name="cognome" placeholder="Cognome" value="<?= ($cognome) ?>" />
    <input type="text" name="classe" placeholder="Classe" value="<?= ($classe) ?>" />
    <input type="text" name="azienda_attuale" placeholder="Azienda Attuale" value="<?= ($azienda_attuale) ?>" />
</form>

<script>
function resetForm(form) {
    // Resets each input field in the form
    form.nome.value = '';
    form.cognome.value = '';
    form.classe.value = '';
    form.azienda_attuale.value = '';
    form.submit();  // Optionally, submit the form after resetting
}
</script>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Email</th>
                <th>Classe</th>
                <th>Azienda Attuale</th>
                <th>Aziende Preferite</th>
                <th>Operazioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (($studenti?:[]) as $studente): ?>
                <tr>
                    <td><?= ($studente['nome']) ?></td>
                    <td><?= ($studente['cognome']) ?></td>
                    <td><?= ($studente['email']) ?></td>
                    <td><?= ($studente['classe']) ?></td>
                    <td><?= ($studente['azienda_attuale']) ?></td>
                    <td><?= ($studente['aziende_preferite']) ?></td>
                    <td><a href="/PCTO/amministratore/studenti/edit/<?= ($studente['id']) ?>" class="modifica-button">Modifica</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
