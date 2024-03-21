<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /Autentificare/autentificare.php');
    exit;
}

include '../db_connect.php';

$taskInfo = [];
$error = '';
$success = '';
$userID = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $taskID = mysqli_real_escape_string($conn, $_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $numeTask = mysqli_real_escape_string($conn, trim($_POST['numeTask']));
        $descriereTask = mysqli_real_escape_string($conn, trim($_POST['descriereTask']));
        $deadlineTask = mysqli_real_escape_string($conn, $_POST['deadlineTask']);

        if (empty($numeTask) || empty($descriereTask) || empty($deadlineTask)) {
            $error = 'Toate câmpurile sunt obligatorii.';
        } else {
            $sql = "SELECT * FROM Sarcini WHERE SarcinaID = '$taskID' AND UtilizatorID = '$userID'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $existingInfo = $result->fetch_assoc();
                if ($numeTask != $existingInfo['NumeSarcina'] || $descriereTask != $existingInfo['DescriereSarcina'] || $deadlineTask != $existingInfo['TermenLimitaSarcina']) {
                    $updateSql = "UPDATE Sarcini SET NumeSarcina='$numeTask', DescriereSarcina='$descriereTask', TermenLimitaSarcina='$deadlineTask' WHERE SarcinaID='$taskID' AND UtilizatorID='$userID'"; 
                    if ($conn->query($updateSql) === TRUE) {
                        $success = 'Informațiile au fost actualizate cu succes.';
                    } else {
                        $error = 'Eroare la actualizarea datelor: ' . $conn->error;
                    }
                } else {
                    $error = 'Vă rugăm să modificați informațiile înainte de actualizare.';
                }
            } else {
                $error = 'Task-ul specificat nu există sau nu aveți permisiunea de a-l edita.';
            }
        }
    }

    $sql = "SELECT * FROM Sarcini WHERE SarcinaID = '$taskID' AND UtilizatorID='$userID'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $taskInfo = $result->fetch_assoc();
    } else {
        $error = "Nu au fost găsite informații pentru task-ul specificat sau nu aveți permisiunea de a vizualiza aceste informații.";
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link href="/Sarcini/informatii_task.css" rel="stylesheet">
        <title>Informații despre task</title>
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
                    <li class="nav-item">
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
                        <a class="nav-link" href="/Sarcini/adauga_task.php"><i class="fas fa-tasks"></i> Task nou</a>
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
            <h1 class="text-center my-4 mt-5">Informații despre task-ul <u><?php echo htmlspecialchars($taskInfo['NumeSarcina']); ?></u></h1>
            <!-- Zona pentru mesajul de eroare sau succes -->
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="alert-container">
                        <?php if ($error): ?>
                            <div class="alert alert-danger text-center" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php elseif ($success): ?>
                            <div class="alert alert-success text-center" role="alert">
                                <?php echo $success; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Formularul de editare a task-ului -->
            <form method="POST">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="numeTask">Nume Task:</label>
                            <input type="text" class="form-control" id="numeTask" name="numeTask" value="<?php echo htmlspecialchars($taskInfo['NumeSarcina']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="descriereTask">Descriere Task:</label>
                            <textarea class="form-control" id="descriereTask" name="descriereTask" rows="3"><?php echo htmlspecialchars($taskInfo['DescriereSarcina']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="deadlineTask">Deadline Task:</label>
                            <input type="date" class="form-control" id="deadlineTask" name="deadlineTask" value="<?php echo htmlspecialchars($taskInfo['TermenLimitaSarcina']); ?>">
                        </div>
                        <div class="float-right">
                                <button type="submit" class="btn custom-update-btn"><i class="fas fa-redo"></i> Actualizează informațiile</button>
                            </div>
                    </div>
                </div>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <!-- Script pentru a ascunde alertele după 3 secunde -->
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 3000);
            });
        </script>

    </body>
</html>