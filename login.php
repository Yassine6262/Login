
<?php include 'nav.php'; ?>
<?php
// Initialiseer de sessie
session_start();
 
// Controleer of de gebruiker al is ingelogd, zo ja, leid hem dan door naar de welkomstpagina
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

 
// Include config file
require_once "config.php";
 
// Defineeer variabelen en initialiseer met lege values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Process form data wanneer form is ingeleverd
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if gebruikersnaam is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Vul aub uw gebruikersnaam in";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if wachtwoord is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Vul aub uw wachtwoord in.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Valideer credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare een select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variabelen aan de prepared statement
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // zet parameters
            $param_username = $username;
            
            // Probeer de voorbereide statement uit te voeren
            if(mysqli_stmt_execute($stmt)){
                // sla results op
                mysqli_stmt_store_result($stmt);
                
                // check of naam correct is zo ja check wachtwoord
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variabelen
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // wachtwoord is correct, dus start een nieuwe session
                            session_start();
                            
                            // sla data op in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect gebruiker naar home page
                            header("location: index.php");
                        } else{
                            // wachtwoord fout laat een generic error message zien
                            $login_err = "Foute gebruikersnaam en/of wachtwoord.";
                        }
                    }
                } else{
                    // Gebruikersnaam en of wachtwoord is fout
                    $login_err = "Foute gebruikersnaam en/of wachtwoord.";
                }
            } else{
                echo "Oeps! Iets ging mis. Probeer het later opnieuw.";
            }

            // sluit statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // sluit connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="img/schoenreus logo.png" type="image/x-icon">
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <title>Login</title>
</head>
<body>

<form method="post" action="">
<div class="login">
  <div class="login-box">
    <h2>Login</h2>
    <p>Vul je gebruikersnaam en wachtwoord in om in te loggen.</p>

    <?php 
    if(!empty($login_err)){
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }        
    ?>

    <form action="" method="post">
        <div class="form-group">
            <label>Gebruikersnaam</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>   
        <div class="form-group">
            <label>Wachtwoord</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Geen account? <a href="register.php">Registreer nu</a>.</p>
    </form>
  </div>
</div>
</form>

<?php include 'contact_actionpage.php'?>

</body>
</html>