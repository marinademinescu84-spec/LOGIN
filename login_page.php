<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="index.css">
</head>
<body class="login-page">

    <form id="loginForm" action="javascript:void(0);" method="POST" class="form">
        <h2>Accedi al tuo Account</h2>
        
        <label>Email:</label><br>
        <input type="email" name="email" id="loginEmail" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" id="loginPassword" required><br><br>

        <button type="submit" id="loginBtn">Login</button>
        <br><br>
        <p>Non hai un account? <a href="index.php">Registrati qui</a></p>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#loginForm").on("submit", function(e){
            e.preventDefault(); // Impedisce il caricamento della pagina
            e.stopPropagation();

            let email = $("#loginEmail").val().trim();
            let password = $("#loginPassword").val();

            if(email === "" || password === "") {
                alert("Inserisci sia l'email che la password.");
                return false;
            }

            // Cambiamo il testo del pulsante per capire che sta lavorando
            $("#loginBtn").prop("disabled", true).text("Verifica in corso...");

            $.ajax({
                // 🌟 CORREZIONE URL: Usiamo l'indirizzo assoluto completo per non sbagliare cartella
                url: "http://localhost/login/login.php",
                type: "POST",
                data: {
                    email: email,
                    password: password
                },
                success: function(res){
                    res = res.trim();
                    $("#loginBtn").prop("disabled", false).text("Login");
                    
                    if(res === "OK"){
                        // 🌟 CORREZIONE REDIRECT: Percorso assoluto alla dashboard
                        window.location.href = "http://localhost/login/dashboard.php";
                    } else if(res === "NON_ATTIVO") {
                        alert("Il tuo account non è attivo. Controlla la posta per attivarlo!");
                    } else {
                        alert("Errore di credenziali: Email o password errate. Il server risponde: " + res);
                    }
                },
                error: function(xhr, status, error) {
                    $("#loginBtn").prop("disabled", false).text("Login");
                    // Se Apache restituisce 404, questo alert ti fermerà qui dentro senza farti uscire dalla pagina!
                    alert("Errore critico AJAX (" + xhr.status + "): Il file 'login.php' non è stato trovato dentro c:/wamp64/www/login/ o Wamp ha un problema di porte.");
                }
            });

            return false;
        });
    });
    </script>
</body>
</html>