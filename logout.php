<html>
    <head>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
        
<?php
// sessie starten/stoppen voor het inloggen/uitloggen
session_start();
session_destroy();
header("location: index.php");
exit;
?>
</html>
</body>
