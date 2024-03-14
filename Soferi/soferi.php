<?php

session_start();

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header('Location: /Autentificare/autentificare.php');
    exit;
}

// Conectare la baza de date
include '../db_connect.php';

// Inițializează array-ul pentru a stoca informațiile șoferilor
$soferi = [];

// Pregătește interogarea SQL
$sql = "SELECT Soferi.Nume, Soferi.Prenume, Vehicule.MarcaModel, Vehicule.NumarInmatriculare FROM Soferi LEFT JOIN Vehicule ON Soferi.SoferID = Vehicule.SoferID";

// Execută interogarea
$result = $conn->query($sql);

// Verifică dacă interogarea a returnat rezultate
if ($result && $result->num_rows > 0) {
    // Parcurge rezultatele și le adaugă în array-ul $soferi
    while($row = $result->fetch_assoc()) {
        $soferi[] = $row;
    }
} else {
    echo "Nu au fost găsiți șoferi.";
}

// Închide conexiunea la baza de date
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Șoferi</title>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="/Soferi/soferi.css" rel="stylesheet">
    <title>Șoferi</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/MeniuPrincipal/meniu_principal.php">
            <img src="/Imagini/Logo.png" class="navbar-logo" alt="Logo" style="max-height: 50px;">
            Route Rover
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto custom-navbar">
                <li class="nav-item">
                    <a class="nav-link" href="/MeniuPrincipal/meniu_principal.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Profil/profil.php"><i class="fas fa-user"></i> Profil</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/Soferi/soferi.php"><i class="fas fa-users"></i> Șoferi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-file-alt"></i> Documente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-file-contract"></i> Contracte</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-truck"></i> Vehicule</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-tasks"></i> Task nou</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link no-hover-effect" href="/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 class="text-center my-4 mt-5">Informații despre șoferi</h1>
        <div class="text-center my-4">
            <a href="/Soferi/adauga_sofer.php" class="btn btn-add">Adaugă un șofer</a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead class="text-black" style="background-color: #ADD8E6;">
                    <tr>
                        <th scope="col">Nume</th>
                        <th scope="col">Prenume</th>
                        <th scope="col">Marca și modelul</th>
                        <th scope="col">Număr de înmatriculare</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($soferi as $sofer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sofer['Nume']); ?></td>
                        <td><?php echo htmlspecialchars($sofer['Prenume']); ?></td>
                        <td><?php echo htmlspecialchars($sofer['MarcaModel']); ?></td>
                        <td><?php echo htmlspecialchars($sofer['NumarInmatriculare']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>