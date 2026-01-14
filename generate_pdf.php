<?php
require_once 'vendor/autoload.php';
use Dompdf\Dompdf;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dompdf = new Dompdf();
    extract($_POST);

    $html = "
    <style>
        body { font-family: sans-serif; color: #333; margin: 0; padding: 0; }
        .wrapper { width: 100%; display: flex; }
        .main { width: 65%; float: left; padding: 30px; }
        .sidebar { width: 30%; float: right; padding: 30px; border-left: 1px solid #eee; text-align: right; }
        .section-title { color: #0066cc; border-bottom: 2px solid #0066cc; font-size: 12px; padding-bottom: 5px; text-transform: uppercase; }
        .name { font-size: 32px; font-weight: bold; margin: 0; }
        .contact-item { font-size: 10px; margin-bottom: 3px; }
    </style>
    <div class='main'>
        <h1 class='name'>$firstname $lastname</h1>
        <p style='font-size: 18px; color: #666;'>$headline</p>
        <p style='font-size: 11px;'>$summary</p>
        <h3 class='section-title'>Experience</h3>";
        
    if(isset($exp_company)) {
        foreach($exp_company as $k => $comp) {
            $html .= "<div style='margin-bottom:15px;'><div style='font-weight:bold;'>$comp</div><div style='font-style:italic;'>{$exp_title[$k]}</div><div style='font-size:10px;'>{$exp_desc[$k]}</div></div>";
        }
    }

    $html .= "</div><div class='sidebar'>
        <div class='contact-item'>$address</div>
        <div class='contact-item'>$phone</div>
        <div class='contact-item' style='color:#0066cc;'>$email</div>
        <div class='contact-item' style='color:#0066cc;'>$linkedin</div>";

    if(isset($skills)) {
        $html .= "<h3 class='section-title'>Skills</h3>";
        foreach($skills as $s) { $html .= "<div style='font-size:11px;'>$s</div>"; }
    }

    if(isset($edu_school)) {
        $html .= "<h3 class='section-title'>Education</h3>";
        foreach($edu_school as $k => $sch) { 
            $html .= "<div style='font-size:11px; margin-bottom:5px;'><strong>$sch</strong><br>{$edu_degree[$k]}</div>"; 
        }
    }

    $html .= "</div>";

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("CV_$lastname.pdf");
}