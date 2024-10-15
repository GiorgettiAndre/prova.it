<?php
// Connessione al database
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "prova.it";

// Crea la connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Controlla la connessione
if ($conn->connect_error) {
    die("Connessione FALLITA: " . $conn->connect_error);
}

// Chiudi la connessione
$conn->close();

header("Location: login.php");
?>

<!-- omgggg -->