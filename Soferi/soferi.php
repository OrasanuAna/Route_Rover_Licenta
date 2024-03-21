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

// Obține ID-ul utilizatorului curent din sesiune
$currentUserId = $_SESSION['user_id'];

// Pregătește interogarea SQL pentru a selecta doar șoferii adăugați de utilizatorul curent
$sql = "SELECT Soferi.SoferID, Soferi.Nume, Soferi.Prenume, Vehicule.MarcaModel, Vehicule.NumarInmatriculare FROM Soferi LEFT JOIN Vehicule ON Soferi.SoferID = Vehicule.SoferID WHERE Soferi.UtilizatorID = $currentUserId";

// Execută interogarea
$result = $conn->query($sql);

// Verifică dacă interogarea a returnat rezultate
if ($result && $result->num_rows > 0) {
    // Parcurge rezultatele și le adaugă în array-ul $soferi
    while($row = $result->fetch_assoc()) {
        $soferi[] = $row;
    }
}

// Închide conexiunea la baza de date
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <a class="nav-link" href="/Vehicule/vehicule.php"><i class="fas fa-truck"></i> Vehicule</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Documente/documente.php"><i class="fas fa-file-alt"></i> Documente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Contracte/contracte.php"><i class="fas fa-file-contract"></i> Contracte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Rapoarte/genereaza_raport.php"><i class="fas fa-chart-bar"></i> Rapoarte</a>
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
                <a href="/Soferi/adauga_sofer.php" class="btn btn-add"><i class="fas fa-plus-circle"></i> Adaugă un șofer</a>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead class="text-black" style="background-color: #ADD8E6;">
                        <tr>
                            <th scope="col" class="text-center">Nr. crt.</th>
                            <th scope="col">Nume</th>
                            <th scope="col">Prenume</th>
                            <th scope="col">Marca și modelul</th>
                            <th scope="col">Număr de înmatriculare</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($soferi as $sofer): ?>
                        <tr>
                            <td class="text-center"><?php echo $counter++; ?></td>
                            <td><?php echo htmlspecialchars($sofer['Nume']); ?></td>
                            <td><?php echo htmlspecialchars($sofer['Prenume']); ?></td>
                            <td><?php echo htmlspecialchars($sofer['MarcaModel']); ?></td>
                            <td><?php echo htmlspecialchars($sofer['NumarInmatriculare']); ?></td>
                            <td>
                                <a href="informatii_sofer.php?id=<?php echo $sofer['SoferID']; ?>" class="edit-icon"><i class="fas fa-pencil-alt"></i></a>
                                <a href="#" class="delete-icon" data-soferid="<?php echo $sofer['SoferID']; ?>"><i class="fas fa-times"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <script>
            document.querySelectorAll('.delete-icon').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const soferId = this.getAttribute('data-soferid');
                    Swal.fire({
                        title: 'Sunteți sigur?',
                        text: "Nu veți putea reveni asupra acestei acțiuni!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Da',
                        cancelButtonText: 'Anulare',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "sterge_sofer.php?id=" + soferId;
                        }
                    });
                });
            });
        </script>

    </body>
</html>