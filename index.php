<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="index.css">

</head>
<body  class="login-page">
    <form method="POST" action="" class="form">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit" id="registerBtn">Registrati</button>
    <button type="button" id="loginBtn">Login</button>
    <p>Compila i campi per registrarti e accedere al tuo account. Dopo la registrazione riceverai un’email di conferma con il link per attivare il tuo profilo e accedere al tuo menu personale.</p>
    <p>Se sei un utente già registrato vai a Login</p>
</form>
<br><br>



<script src="https://code.jquery.com/jquery-4.0.0.min.js"></script>
<script>
    $("form").submit(function(e){
    e.preventDefault();

        let password = $("input[name='password']").val();

    if (
    password.length < 8 ||
    !/[!@#$%^&*(),.?":{}|<>]/.test(password)
) {
    alert("Password non valida: minimo 8 caratteri e almeno un simbolo speciale");
    return;
}
console.log($("input[name='email']").val());

    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: {
            username: $("input[name='username']").val(),
            password: password,
             email: $("input[name='email']").val(),
        },
        success: function(res){
            console.log(res);
            alert(res);
        }
    });
});



$("#loginBtn").click(function(e){
    e.preventDefault();
    
            var username= $("input[name='username']").val();
            var password= $("input[name='password']").val();
console.log(username, password);
    $.ajax({
        url: "login.php",
        type: "POST",
        data: {
            username: username,
            password: password
        },
        success: function(res){
            console.log(res);

            if(res.trim() == "OK"){
                window.location.href = "dashboard.php";
            } else {
                alert("Login fallito");
            }

        }
    });

});
</script>

    
</body>
</html>