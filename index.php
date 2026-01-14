<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Générateur de CV Professionnel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-4" id="form-side">
                <div class="card p-4 shadow-sm">
                    <form id="cv-form" method="POST" action="generate_pdf.php">
                        <h5 class="mb-3">Identité & Contact</h5>
                        <input type="text" name="firstname" class="form-control mb-2" id="in-firstname" placeholder="Prénom">
                        <input type="text" name="lastname" class="form-control mb-2" id="in-lastname" placeholder="Nom">
                        <input type="text" name="headline" class="form-control mb-2" id="in-headline" placeholder="Titre (ex: Front End Developer)">
                        <textarea name="summary" class="form-control mb-2" id="in-summary" placeholder="Résumé professionnel"></textarea>
                        <input type="text" name="address" class="form-control mb-2" id="in-address" placeholder="Ville, Pays">
                        <input type="text" name="phone" class="form-control mb-2" id="in-phone" placeholder="Téléphone">
                        <input type="email" name="email" class="form-control mb-2" id="in-email" placeholder="Email">
                        <input type="text" name="linkedin" class="form-control mb-2" id="in-linkedin" placeholder="LinkedIn">

                        <hr>
                        <h5>Expériences (Colonne Gauche)</h5>
                        <div id="experience-list"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary w-100" id="add-exp">+ Ajouter Expérience</button>

                        <hr>
                        <h5>Barre Latérale (Colonne Droite)</h5>
                        <h6>Compétences</h6>
                        <div id="skills-list" class="mb-2"></div>
                        <button type="button" class="btn btn-sm btn-outline-secondary w-100 mb-3" id="add-skill">+ Ajouter Compétence</button>

                        <h6>Éducation</h6>
                        <div id="education-list" class="mb-2"></div>
                        <button type="button" class="btn btn-sm btn-outline-secondary w-100 mb-3" id="add-edu">+ Ajouter Formation</button>

                        <h6>Sections Libres</h6>
                        <div id="custom-sections-list" class="mb-2"></div>
                        <button type="button" class="btn btn-sm btn-dark w-100" id="add-custom">+ Ajouter Section Libre</button>

                        <button type="submit" class="btn btn-success btn-lg w-100 mt-4 shadow">Générer le PDF</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8 d-flex justify-content-center">
                <div id="cv-preview" class="cv-page shadow-lg bg-white">
                    <div class="main-content">
                        <header>
                            <h1 class="name-display"><span id="out-firstname">First</span> <span id="out-lastname">Last</span></h1>
                            <h2 id="out-headline" class="job-title-display">Front End Developer</h2>
                            <p id="out-summary" class="summary-display">Résumé professionnel...</p>
                        </header>
                        <section class="mt-4">
                            <h3 class="section-title">EXPERIENCE</h3>
                            <div id="out-experiences"></div>
                        </section>
                    </div>

                    <div class="sidebar">
                        <div class="contact-block text-end">
                            <div id="out-address">San Francisco, CA</div>
                            <div id="out-phone">+1 234 567-890</div>
                            <div id="out-email" class="text-primary fw-bold">email@exemple.com</div>
                            <div id="out-linkedin" class="text-primary">linkedin.com/in/user</div>
                        </div>

                        <div id="sidebar-dynamic-content">
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/app.js"></script>
</body>
</html>