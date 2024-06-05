<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - PCTO</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-form {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #0056b3;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            color: #333;
            display: block;
            margin-top: 10px;
        }

        input,
        select,
        button {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            background-color: #0056b3;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        button:hover {
            background-color: #007bff;
        }

        .error {
            color: #ff3860;
            text-align: center;
            margin-top: -10px;
            margin-bottom: 10px;
            height: 20px;
        }
    </style>
</head>
    <body>
    <div class="login-form">
        <h2>Login</h2>
        <button type="button" onclick="history.back();">Torna Indietro</button>
        <form action="/PCTO/login" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="role">Ruolo:</label>
            <select id="role" name="role">
                <option value="studente">Studente</option>
                <option value="admin">Amministratore</option>
            </select>
            <button type="submit">Accedi</button>

                <p class="error">Credenziali non valide.</p>

        </form>
    </div>
</body>
</html>
