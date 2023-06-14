<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "schoenreus";


// Connectie maken met de PDO.
$connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


try {
    // INSERT query uitvoeren.
    $stmt = $connection->prepare("INSERT INTO contact (naam_en_achternaam, email, telefoonnummer, bericht)
    VALUES (:naam_en_achternaam, :email, :telefoonnummer, :bericht) ");
    $stmt->bindParam(':naam_en_achternaam', $naam);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefoonnummer', $telefoonnummer);
    $stmt->bindParam(':bericht', $bericht);


    
    // INSERT rij.
    if (isset($_REQUEST['naam']) ) {
        $naam = $_POST['naam'];
        $email = $_POST['email'];
        $telefoonnummer = $_POST['telefoonnummer'];
        $bericht = $_POST['bericht'];
        $stmt->execute();

        header('Location: contact.php');

    }
}


catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}


$sql_select = "SELECT * FROM contact";
$data = $connection->query($sql_select);


$connection = null;




// Delete functies


if (isset($_GET['id']) ) {
    $id = $_GET['id'];
    echo "$id";

    try {
        $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        // Set the PDO error mode to exception.
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL to delete a record.
        $sql = "DELETE FROM contact WHERE id = '$id'";

        // Use exec() because no results are returned.
        $connection->exec($sql);
        echo "Record deleted successfully";
        
        // Terugsturen naar de hoofdpagina.
        header('Location: contact_actionpage.php');
    }

    catch(PDOException $e) {
        // echo $sql . "<br>" . $e->getMessage();
    }
}

$connection = null;

?> 