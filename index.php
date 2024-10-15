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
    die("Connessione FALLITA: " . $conn->connect_error);
else
    header("Location: login.php");
?>

<!-- omgggg -->