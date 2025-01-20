<?php
    session_start();
    include_once 'header.php';
    include_once 'db.php';

    if(!isset($_SESSION['username']) && $_SESSION['username'] !== 'admin'){
        header('Location: index.php');//FIXME: unhautorized?
        exit();
    }

    $db = new DatabaseClient();
    $db->connect();
    $context['vecchio_titolo'] = $_GET['vecchio_titolo'];
    $debugMessage = '';

    function sanitizeInput($input){
        $sanitizedInput = trim($input);
        $sanitizedInput = htmlspecialchars($input);
        return $sanitizedInput;
    }

    function modifyShow($db){
        $old_title = sanitizeInput($_GET['vecchio_titolo']);
        $title = sanitizeInput($_POST['titolo']);
        $description = sanitizeInput($_POST['descrizione']);
        $imageDescription = sanitizeInput($_POST['descrizione_immagine']);
        if (!array_key_exists("immagine", $_FILES) || $_FILES["immagine"]["error"] == UPLOAD_ERR_NO_FILE){
            return "È obbligatorio caricare un'immagine";
        }
        $image_file = $_FILES["immagine"];
        if ($image_file["error"] !== UPLOAD_ERR_OK){
            return "Caricamento dell'immagine fallito";
        }
        if (!str_starts_with($image_file["type"], 'image/png') && !str_starts_with($image_file["type"], 'image/jpeg')) {
            return "Non è stata caricata un'immagine valida";
        }
        $image_asset_path = 'admin_uploads/' . basename($image_file["full_path"]);
        $image_path = realpath(dirname(__FILE__) .'/assets') . '/' . $image_asset_path;
        if (!move_uploaded_file($image_file["tmp_name"], $image_path)) {
            return "Salvataggio dell'immagine fallito";
        }
        $db->modifyShow($old_title,$title,$description,$image_asset_path,$imageDescription);
        return "Modifica dello spettacolo avvenuta con successo!";
    }
    if(isset($_POST['submit'])){
        $debugMessage = modifyShow($db);
    }
    $context['debugMessage'] = $debugMessage;
    include_once 'parser.php';
    $template = new Parser();
    $template->render("editShow.html", $context);
?>