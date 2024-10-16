<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        // Connessione al database
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "prova.it";

        // Crea la connessione
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Controlla la connessione
        if ($conn->connect_error)
            echo "<h1>Errore di connessione: '$conn->connect_error'</h1>";
        else
            header("Location: login.php");
    ?>
</body>
</html>