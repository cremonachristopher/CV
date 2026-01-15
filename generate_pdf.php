<?php
// 1. On empêche toute sortie de texte parasite (espaces, warnings)
ob_start();

require_once 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Configuration de Dompdf
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans'); // Meilleur support des accents
    $dompdf = new Dompdf($options);

    // 3. Traitement de la photo (Conversion Base64)
    $photoHtml = "";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $path = $_FILES['photo']['tmp_name'];
        $type = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $photoHtml = "<div style='text-align: center; width: 100%; margin-bottom: 20px;'>
                        <img src='$base64' style='width: 120px; height: 120px; object-fit: cover; border: 3px solid #fff; border-radius: 50%;'>
                      </div>";
    }

    // 4. Récupération et nettoyage des données
    $fn = strtoupper(htmlspecialchars($_POST['firstname'] ?? 'Prénom'));
    $ln = strtoupper(htmlspecialchars($_POST['lastname'] ?? 'Nom'));
    $hl = htmlspecialchars($_POST['headline'] ?? '');
    $sm = nl2br(htmlspecialchars($_POST['summary'] ?? ''));

    // 5. Structure HTML du PDF
    $html = "
    <!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
        <style>
            @page { margin: 0px; }
            body { 
                margin: 0px; padding: 0px; 
                font-family: 'DejaVu Sans', sans-serif; 
                height: 297mm; width: 210mm;
            }
            
            /* Fond de la sidebar (Pleine hauteur) */
            .sidebar-bg {
                position: absolute;
                right: 0; top: 0; bottom: 0;
                width: 33%;
                background-color: #212529;
                z-index: -1;
            }

            .wrapper { width: 100%; border-collapse: collapse; table-layout: fixed; }
            
            .main-cell { width: 67%; padding: 40px; vertical-align: top; background-color: white; }
            
            .sidebar-cell { 
                width: 33%; padding: 40px 20px; 
                vertical-align: top; color: white; 
                text-align: center; 
            }

            /* Styles Typographie */
            .name { font-size: 26pt; font-weight: bold; color: #212529; margin: 0; line-height: 1.1; }
            .headline { font-size: 14pt; color: #6c757d; margin-bottom: 25px; text-transform: uppercase; }
            .section-title { border-bottom: 2px solid #212529; font-weight: bold; text-transform: uppercase; margin-top: 30px; padding-bottom: 5px; font-size: 11pt; text-align: left; }
            
            .sidebar-title { border-bottom: 1px solid #555; margin-top: 25px; padding-bottom: 5px; font-weight: bold; font-size: 10pt; text-transform: uppercase; color: #ffffff; text-align: center; }
            
            .exp-item { margin-bottom: 18px; text-align: left; }
            .exp-company { font-weight: bold; font-size: 11pt; color: #212529; }
            .exp-title { font-style: italic; color: #444; font-size: 10pt; margin-bottom: 4px; }
            .desc { font-size: 9pt; color: #333; line-height: 1.5; text-align: justify; }

            .small-text { font-size: 9pt; color: #ced4da; margin-top: 6px; line-height: 1.4; text-align: center; }
        </style>
    </head>
    <body>
        <div class='sidebar-bg'></div>

        <table class='wrapper'>
            <tr>
                <td class='main-cell'>
                    <h1 class='name'>$fn $ln</h1>
                    <div class='headline'>$hl</div>
                    
                    <div class='section-title'>Profil Professionnel</div>
                    <p class='desc' style='font-size: 10pt; margin-top: 10px;'>$sm</p>

                    <div class='section-title'>Expérience Professionnelle</div>";

    if(!empty($_POST['exp_company'])) {
        foreach($_POST['exp_company'] as $k => $comp) {
            if(empty($comp)) continue;
            $title = htmlspecialchars($_POST['exp_title'][$k]);
            $d = nl2br(htmlspecialchars($_POST['exp_desc'][$k]));
            $html .= "
                <div class='exp-item'>
                    <div class='exp-company'>$comp</div>
                    <div class='exp-title'>$title</div>
                    <div class='desc'>$d</div>
                </div>";
        }
    }

    $html .= "
                </td>

                <td class='sidebar-cell'>
                    $photoHtml
                    
                    <div class='sidebar-title'>Contact</div>
                    <div class='small-text'>
                        " . (isset($_POST['address']) ? htmlspecialchars($_POST['address']) . "<br>" : "") . "
                        " . (isset($_POST['email']) ? htmlspecialchars($_POST['email']) . "<br>" : "") . "
                        " . (isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : "") . "
                    </div>";

    if(!empty($_POST['skills'])) {
        $html .= "<div class='sidebar-title'>Compétences</div>";
        foreach($_POST['skills'] as $s) {
            if(!empty($s)) $html .= "<div class='small-text'>• " . htmlspecialchars($s) . "</div>";
        }
    }

    $html .= "
                </td>
            </tr>
        </table>
    </body>
    </html>";

    // 6. Rendu du PDF
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // 7. Nettoyage et Envoi
    ob_get_clean(); 
    $dompdf->stream("CV_" . $ln . ".pdf", ["Attachment" => true]);
    exit();
}