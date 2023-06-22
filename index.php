<?php
require_once 'functies.php';

// Database-inloggegevens
$dbHost = 'localhost';
$dbName = 'loginopdracht';
$dbUser = 'root';
$dbPass = '';

// Maak een PDO-verbinding
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Fout bij het verbinden met de database: " . $e->getMessage());
}

// Controleer of er een inlogpoging is gedaan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $wachtwoord = $_POST['wachtwoord'];

    // Zoek naar de gebruiker in de database
    $query = "SELECT * FROM users WHERE email = :email";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // Controleer het wachtwoord
    if ($user && $wachtwoord === $user['wachtwoord']) {
        // Inloggen is gelukt, stel de sessie in
        $_SESSION['ingelogd'] = true;
        $_SESSION['gebruiker'] = $user;
        header("Location:index.php");
        exit();
    } else {
        // Ongeldige inloggegevens, toon een foutmelding
        $foutmelding = "Ongeldige inloggegevens.";
    }
}

// Uitloggen
if (isset($_GET['uitloggen'])) {
    session_destroy();
    header("Location:index.php");
    exit();
}

// Controleer of de gebruiker is ingelogd
if (isIngelogd()) {
    // Haal de gebruikersgegevens op uit de sessie
    $gebruiker = getGebruiker();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Inlogpagina</title>
</head>
<body>
    <h1>Welkom, <?php echo $gebruiker['naam']; ?>!</h1>
    <p>E-mail: <?php echo $gebruiker['email']; ?></p>
    <p>Wachtwoord: <?php echo $gebruiker['wachtwoord']; ?></p>
    <a href="index.php?uitloggen=1">Uitloggen</a>
</body>
</html>

<?php
    exit(); // Beëindig de code hier als de gebruiker is ingelogd
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Inlogpagina</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Inloggen</h1>
    <?php if (isset($foutmelding)) { ?>
        <p><?php echo $foutmelding; ?></p>
    <?php } ?>
    <form method="POST" action="">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="wachtwoord">Wachtwoord:</label>
        <input type="password" id="wachtwoord" name="wachtwoord" required><br><br>

        <input type="submit" value="Inloggen">
    </form>
    <br>
    <a href="registreren.php">Registreren</a>
</body>
</html>