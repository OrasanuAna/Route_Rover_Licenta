<?php
session_start();
$error = ''; // Variabilă pentru a stoca mesajele de eroare

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../db_connect.php'; // Include your database connection file

    // Colectează datele de la formular și le curăță
    $nume = mysqli_real_escape_string($conn, trim($_POST['nume']));
    $prenume = mysqli_real_escape_string($conn, trim($_POST['prenume']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $telefon = mysqli_real_escape_string($conn, trim($_POST['telefon']));
    $parola = mysqli_real_escape_string($conn, trim($_POST['parola']));

    // Verifică dacă toate câmpurile sunt completate
    if (empty($nume) || empty($prenume) || empty($username) || empty($email) || empty($telefon) || empty($parola)) {
        $error = 'Toate câmpurile sunt obligatorii.';
    } else {
        // Verifică dacă numele de utilizator există deja în baza de date
        $sql = "SELECT * FROM Utilizatori WHERE NumeUtilizator = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $error = 'Nume de utilizator deja existent.';
        } else {
            // Înserează noul utilizator în baza de date
            $hashed_password = password_hash($parola, PASSWORD_DEFAULT);
            $insertSql = "INSERT INTO Utilizatori (Nume, Prenume, NumeUtilizator, Email, Telefon, Parola) VALUES ('$nume', '$prenume', '$username', '$email', '$telefon', '$hashed_password')";
            if ($conn->query($insertSql) === TRUE) {
                header("Location: /Autentificare/autentificare.php"); // Redirect după înregistrare reușită
                exit;
            } else {
                $error = 'A apărut o eroare la înregistrare.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inregistrare</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Inregistrare/inregistrare.css">
</head>
<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-lg-7 col-md-7 col-sm-6 p-0">
                <img src="/Imagini/RouteRoverCover.jpg" class="img-fluid img-full-height" alt="Route Rover Cover">
            </div>
            <div class="col-lg-5 col-md-5 col-sm-6 d-flex">
                <div class="full-height-form w-100">
                    <form method="POST">
                        <!-- Locul pentru erori -->
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <input type="text" class="form-control" name="nume" placeholder="Nume">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="prenume" placeholder="Prenume">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" autocomplete="off" placeholder="Nume de utilizator">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="telefon" placeholder="Nr de telefon">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="parola" placeholder="Parola">
                        </div>
                        <div class="d-flex flex-column">
                            <button type="submit" class="btn btn-primary mb-3">Înainte</button>
                            <span class="text-center">---SAU---</span>
                            <a href="/Autentificare/autentificare.php" class="btn btn-primary mt-3">Ai deja un cont? Conecteaza-te</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
