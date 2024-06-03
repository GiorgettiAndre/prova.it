<?php
// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "esempiodb";

// Crea la connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Controlla la connessione
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$nome_tabella = "esempioTB";

// Esegui la query per verificare l'esistenza della tabella
$sql = "SHOW TABLES LIKE '$nome_tabella'";
$result = $conn->query($sql);

if ($result->num_rows <= 0) {
    // Query di creazione tabella e inserimeto di due righe
	$conn->query("CREATE TABLE esempioTB( usern VARCHAR(30) NOT NULL, pwd VARCHAR(100) NOT NULL , email VARCHAR(40) NOT NULL, PRIMARY KEY (usern, email));");
}

// Chiudi la connessione
$conn->close();

header("Location: login.php");
?>