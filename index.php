<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="index.css">
</head>
<body class="login-page">

    <form id="regForm" method="POST" class="form">
        <h2>Registrazione Account</h2>
        
        <label>Nome:</label><br>
        <input type="text" name="nome" id="nome">

        <label>Cognome:</label><br>
        <input type="text" name="cognome" id="cognome">

        <label>Telefono:</label><br>
        <input type="text" name="telefono" id="telefono">

        <label>Email:</label><br>
        <input type="email" name="email" id="email">

        <label>Password:</label><br>
        <input type="password" name="password" id="password">

        <label>Conferma Password:</label><br>
        <input type="password" name="conferma_password" id="conferma_password">

        <label>
            <input type="checkbox" name="privacy" id="privacy"> Accetto l'informativa sulla privacy
        </label>

        <button type="submit" id="registerBtn">Registrati</button>
        
        <p>Se sei un utente già registrato vai a <a href="login_page.php">Login</a></p>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $("#regForm").submit(function(e) {
        e.preventDefault();

        let nome = $("#nome").val().trim();
        let cognome = $("#cognome").val().trim();
        let telefono = $("#telefono").val().trim();
        let email = $("#email").val().trim();
        let password = $("#password").val();
        let confermaPassword = $("#conferma_password").val();
        let privacyChecked = $("#privacy").is(":checked");

        if (nome === "" || cognome === "" || email === "" || password === "" || confermaPassword === "") {
            alert("Attenzione: Compila tutti i campi obbligatori!");
            return;
        }

        if (password !== confermaPassword) {
            alert("Errore: Le password non coincidono!");
            return;
        }

        // Controllo password semplificato per Localhost (almeno 6 caratteri liberi)
        if (password.length < 6) {
            alert("Password troppo corta: inserisci almeno 6 caratteri.");
            return;
        }

        if (!privacyChecked) {
            alert("Devi accettare l'informativa sulla privacy per proseguire.");
            return;
        }

        $.ajax({
            url: "ajax.php",
            type: "POST",
            data: {
                nome: nome,
                cognome: cognome,
                telefono: telefono,
                email: email,
                password: password
            },
            success: function(res) {
                if (res.trim() === "OK") {
                    alert("Registrazione avvenuta con successo! Controlla la tua email per attivare l'account.");
                    window.location.href = "login_page.php"; 
                } else {
                    alert("Il Server ha risposto con un errore: " + res);
                }
            },
            error: function(xhr, status, error) {
                // Intercetta crash di PHP (es. se inviomail.php si blocca)
                alert("Errore critico di Localhost (Codice " + xhr.status + "): Impossibile completare la richiesta. Controlla che il DB sia connesso.");
            }
        });
    });
    </script>
</body>
</html>