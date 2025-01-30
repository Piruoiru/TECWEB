<?php
    session_start();
    include_once 'header.php';
    include_once 'db.php';

    if(!isset($_SESSION['username']) && $_SESSION['username'] !== 'admin'){
        header('Location: 401.php');
        exit();
    }

    $db = new DatabaseClient();
    $context['vecchio_titolo'] = sanitizeInput($_GET['vecchio_titolo']);

    $context['showsEditInfoMessage'] = '';
    $context['showsEditErrorMessage'] = [];

    function sanitizeInput($input){
        $sanitizedInput = trim($input);
        $sanitizedInput = htmlspecialchars($sanitizedInput,ENT_QUOTES);
        return $sanitizedInput;
    }

    function validate($title,$description,$imageDescription){
        global $context;
        $isValid = true;

        if(!preg_match('/^.{4,50}$/u', $title)){
            $isValid = false;
            array_push($context['showsEditErrorMessage'], "Il titolo deve essere composto da 4 a 50 caratteri");

        }
        if (!preg_match('/^.{4,500}$/u', $description)) {
            $isValid = false;
            array_push($context['showsEditErrorMessage'], "La descrizione deve essere composta da 4 a 500 caratteri");
        }

        if (!preg_match('/^.{4,100}$/u', $imageDescription)) {
            $isValid = false;
            array_push($context['showsEditErrorMessage'], "La descrizione dell'immagine deve essere composta da 4 a 100 caratteri");
        }
        return $isValid;
    }

    function modifyShow($db){
        global $context;

        $old_title = sanitizeInput($_POST['vecchio_titolo']);
        if(empty($db->fetchShow($old_title))){
            array_push($context['showsEditErrorMessage'], "Si sta provando a modificare uno spettacolo che non esiste");
            return;
        }
        $title = sanitizeInput($_POST['titolo']);
        $description = sanitizeInput($_POST['descrizione']);
        $imageDescription = sanitizeInput($_POST['descrizione_immagine']);
        if(validate($title,$description,$imageDescription)){
            if (!array_key_exists("immagine", $_FILES) || $_FILES["immagine"]["error"] == UPLOAD_ERR_NO_FILE){
                array_push($context['showsEditErrorMessage'], "È obbligatorio caricare un'immagine");
                return;
            }
            $image_file = $_FILES["immagine"];
            if ($image_file["error"] !== UPLOAD_ERR_OK){
                array_push($context['showsEditErrorMessage'], "Caricamento dell'immagine fallito");
                return;
            }
            if (!str_starts_with($image_file["type"], 'image/png') && !str_starts_with($image_file["type"], 'image/jpeg')) {
                array_push($context['showsEditErrorMessage'], "Non è stata caricata un'immagine valida");
                return;
            }
            $image_asset_path = 'admin_uploads/' . basename($image_file["full_path"]);
            $image_path = realpath(dirname(__FILE__) .'/assets') . '/' . $image_asset_path;
            if (!move_uploaded_file($image_file["tmp_name"], $image_path)) {
                array_push($context['showsEditErrorMessage'], "Salvataggio dell'immagine fallito");
                return;
            }
            $db->modifyShow($old_title,$title,$description,$image_asset_path,$imageDescription);
            return 'Modifica dello spettacolo avvenuta con successo!';
        }
    }
    if(isset($_POST['submit'])){
        $db->connect();
        $context['showsEditInfoMessage'] = modifyShow($db);
        $db->close();
    }
    include_once 'parser.php';
    $template = new Parser();
    $template->render("editShow.html", $context);
?>