<?php
function ValidateEmail($mail){
    $symbolfounded = false; // <-- means "@"
    $pointfounded = false;
    for($i = 0; $i < strlen($mail); $i++){
        if($mail[$i] == '@')
            $symbolfounded = true;
        if($mail[$i] == '.')
            $pointfounded = true;
        
        if($i == 0 && ($symbolfounded || $pointfounded))
            return false;
        elseif($pointfounded && !$symbolfounded)
            return false;
    }
    return $symbolfounded && $pointfounded;
}
?>

<?php
abstract class Registrst{
    const Userntkn = -1;
    const Emailtkn = 0;
    const Wrongemail = 1/3;
    const ForgottenPWD = 1/2;
    const Success = 1;
}

require "index.php";

$alreadyreg = true;
$registr = Registrst::Success;

if(!(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])))
    $alreadyreg = false;
else
{
    // Recupero dei dati dal modulo di registrazione
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    $result = $conn->query("SELECT * FROM utente WHERE username='$username'");
    if ($result->num_rows > 0)
        $registr = Registrst::Userntkn;

    $result = $conn->query("SELECT * FROM utente WHERE email='$email'");
    if ($result->num_rows > 0)
    {
        if ($registr == Registrst::Userntkn)
            $registr = Registrst::ForgottenPWD;
        else
            $registr = Registrst::Emailtkn;
    }
    if (!ValidateEmail($email))
        $registr = Registrst::Wrongemail;

    $codedpwd = hash('sha256', $password);
    if ($registr == Registrst::Success)
    {
        $conn->query("INSERT INTO utente VALUES ('$username' , '$email' , '$codedpwd')");
        $conn->query("INSERT INTO accesso (username_utente, email_utente) VALUES('$username', '$email'); ");
    }
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
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
        if($alreadyreg){
            switch($registr){
                case Registrst::Success:
                    echo "<h2>Registrazione avvenuta con successo, <a href=\"login.php\">entra!</a></h2>";
                    break;
                case Registrst::Emailtkn:
                    echo "<h2>Questa email appartiene già ad un altro account, riprova:</h2>";
                    break;
                case Registrst::Wrongemail:
                    echo "<h2>Questa email non è valida, riprova:</h2>";
                    break;
                case Registrst::ForgottenPWD:
                    echo "<h2>L'username e l'email sono già occupati... non è che la password è stata <a href=\"login.php\">dimenticata</a>?</h2>";
                    break;
                default:
                    echo "<h2>Questo username è già occupato, riprova:</h2>";
                    break;
            }
        }
        else{
            echo "<h2>Prima volta? fai la registrazione:</h2>";
        }

        if(!$alreadyreg || ($alreadyreg && $registr != Registrst::Success))
        {
        ?>
            <div class = "form_container">
                <form action="registrer.php" method="post">
                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username"><br>
                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password"><br>
                    <label for="email">Email:</label><br>
                    <input type="text" id="email" name="email"><br><br>
                    <input type="submit" value="Registrati">
                </form>
            </div>
            <br><br>
            <p>Hai già un account? <a href="login.php">Accedi!</a></p>
        <?php
        }
    ?>
</body>
</html>