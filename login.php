<?php
abstract class Loginst {
    const Failed = 0;
    const Success = 1;
    const WrongPWD = 2;
}

require "index.php";

$justdone = true;
$login = Loginst::Failed;

if (!(isset($_POST['username']) && isset($_POST['password'])))
    $justdone = false;
else {
    // Prendi username e password dal form di login
    $username = trim($_POST['username']);
    $pw = trim($_POST['password']);

    $codedpw = hash('sha256', $pw);
    // Query per verificare se le credenziali sono corrette
    $result = $conn->query("SELECT * FROM utente WHERE username='$username' AND pw='$codedpw'");
    if ($result->num_rows > 0)
        $login = Loginst::Success; // Login riuscito
    else {
        $result = $conn->query("SELECT * FROM utente WHERE username='$username'");
        if ($result->num_rows > 0)
            $login = Loginst::WrongPWD;
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Reset CSS */
        body, h2, form, label, input, p, a {
            margin: 0;
            padding: 0;
            font-size: 100%;
            font-weight: normal;
        }

        /* Stili specifici per il corpo */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px; /* distanza dal bordo della finestra */
        }

        /* Stili per il titolo */
        h2 {
            font-size: 1.5em; /* il font sarà 1.5 più grande del default */
            margin-bottom: 20px; /* spazio sotto il testo */
        }

        /* stili per il sotto-titolo */
        h3 {
            font-size: 1.25em;
            margin-bottom: 17px;
        }

        /* Stili per il form */
        form {
            width: 30%;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px; /* arrotonda gli angoli */
            box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.1); /* aggiunge l'effetto ombra attorno */
        }/* postamento sx-0-dx ; spostamento sù-0-giù ; raggio di sfocatura ; valori RGB e opacità*/

        label {
            display: block;
            font-size: 1em;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"] {
            font-size: 1em;
            padding: 10px;
            margin-bottom: 10px;
            width: 100%; /* quanto occupa di spazio in larghezza della finestra */
            box-sizing: border-box; /* nella sua grandezza include anche i bordi e i padding, di default non è così */
        }

        input[type="submit"] {
            font-size: 1em;
            margin-top: 30px;
            padding: 10px 20px; /* padding su diversi lati, max 4 valori: top-dx-down-sx */
            cursor: pointer; /* cambia il cursone nella manina che sta per cliccare */
            background-color: #007BFF;
            color: white; /* colore del carattere */
            border: none; /* elimina i bordi */
            border-radius: 5px;
            display: block; /* da un elemento in line, come un testo, diventa un blocco in cui occupa tutta la sua riga */
            margin: 0 auto; /* per il centramento */
        }

        p, a {
            font-size: 1em;
        }

        a { /* per i collegamenti */
            color: #007BFF;
            text-decoration: none; /* non mette la sottolineatura che HTML mette di default ai collegamenti */
        }

        a:hover { /* i collegamenti ma versione col mouse sopra */
            text-decoration: underline; /* ammette la sottolineatura */
        }

        /* una classe CSS(definita col punto iniziale) per il centramento degli elementi */
        .form_container {
            text-align: center;
        }
    </style>
</head>
<body>
    <?php
        if ($justdone) {
            switch($login) {
                case Loginst::Success:
                    $email = $conn->query("SELECT email FROM utente WHERE username='$username' AND pw='$codedpw'")->fetch_assoc()["email"];
                    $conn->query("INSERT INTO accesso (username_utente, email_utente) VALUES('$username', '$email'); ");
                    echo "<h2>Sei entrato con successo!</h2>";
                    break;

                case Loginst::Failed:
                    echo "<h2>L'account non è stato registrato o l'username è sbagliato, <a href=\"registrer_form.php\">fai l'account</a> o riprova con un altro username:</h2>";
                    break;

                default:
                    if (strtolower($password) == "errata")
                        echo "<h3>La password è \"errata\"? Sei serio?</h3><br><h2>La password è sbagliata, riprova:</h2>";
                    elseif (strtolower($password) == "sbagliata")
                        echo "<h3>perdo le speranze con te</h3><br><h2>riprova:</h2>";
                    else
                        echo "<h2>La password è errata, riprova:</h2>";
                    break;
            }
        } else {
            echo "<h2>Benvenuto! Effettua il login:</h2>";
        }

        if ($login != Loginst::Success) {
        ?>
            <div class = "form_container">
                <form action="login.php" method="post">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">
                    <br>
                    <input type="submit" value="Login">
                </form>
            </div>
            <br><br>
            <p>Non hai un account? <a href="registrer.php">Crealo!</a></p>
        <?php
        }
    ?>
</body>
</html>
