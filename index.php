<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de CV Pro - Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --cv-dark: #212529; }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Colonne de gauche : Formulaire */
        #form-side { height: 100vh; overflow-y: auto; padding: 2.5rem; background: #ffffff; border-right: 1px solid #dee2e6; }
        
        /* Colonne de droite : Aperçu */
        #preview-side { height: 100vh; overflow-y: auto; padding: 2rem; background: #525659; display: flex; justify-content: center; }
        
        /* Conteneur A4 Réel */
        .cv-page { 
            width: 210mm; min-height: 297mm; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: flex; overflow: hidden; position: relative;
        }

        /* Mise en page interne du CV */
        .cv-main { width: 67%; padding: 40px; }
        .cv-sidebar { 
            width: 33%; 
            background: var(--cv-dark); 
            color: white; 
            padding: 40px 20px; 
            display: flex;
            flex-direction: column;
            align-items: center; 
            text-align: center;
        }

        /* Styles de texte de l'aperçu */
        .name-display { font-size: 38px; font-weight: 800; text-transform: uppercase; line-height: 1; margin: 0; color: #212529; }
        .job-display { font-size: 18px; color: #6c757d; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .section-title { border-bottom: 2px solid var(--cv-dark); font-weight: bold; text-transform: uppercase; margin-top: 25px; margin-bottom: 15px; font-size: 14px; }
        
        .sidebar-title { 
            width: 100%; border-bottom: 1px solid #495057; 
            margin-top: 25px; padding-bottom: 5px; 
            font-size: 12px; font-weight: bold; text-transform: uppercase; 
        }

        /* Photo ronde centrée */
        #out-photo { 
            width: 130px; height: 130px; 
            object-fit: cover; border-radius: 50%; 
            border: 3px solid #fff; margin-bottom: 20px;
        }

        .contact-preview { font-size: 13px; color: #ced4da; margin-top: 10px; line-height: 1.6; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4" id="form-side">
            <form action="generate_pdf.php" method="POST" enctype="multipart/form-data">
                <h3 class="mb-4 fw-bold text-primary">Mon CV Pro</h3>
                
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">IDENTITÉ</label>
                    <div class="row g-2">
                        <div class="col"><input type="text" name="firstname" id="in-firstname" class="form-control" placeholder="Prénom" required></div>
                        <div class="col"><input type="text" name="lastname" id="in-lastname" class="form-control" placeholder="Nom" required></div>
                    </div>
                    <input type="text" name="headline" id="in-headline" class="form-control mt-2" placeholder="Titre (ex: Développeur Fullstack)">
                    <textarea name="summary" id="in-summary" class="form-control mt-2" rows="3" placeholder="Résumé de vos points forts..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">CONTACT & PHOTO</label>
                    <input type="text" name="address" id="in-address" class="form-control mb-1" placeholder="Ville, Pays">
                    <input type="email" name="email" id="in-email" class="form-control mb-1" placeholder="Email">
                    <input type="text" name="phone" id="in-phone" class="form-control mb-1" placeholder="Téléphone">
                    <input type="text" name="linkedin" id="in-linkedin" class="form-control mb-2" placeholder="Lien LinkedIn">
                    <input type="file" name="photo" id="in-photo" class="form-control" accept="image/*">
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="fw-bold m-0">EXPÉRIENCES</label>
                    <button type="button" class="btn btn-sm btn-primary rounded-pill" id="add-exp">+ Ajouter</button>
                </div>
                <div id="experience-list" class="mb-4"></div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="fw-bold m-0">COMPÉTENCES</label>
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill" id="add-skill">+ Ajouter</button>
                </div>
                <div id="skills-list" class="mb-4"></div>

                <button type="submit" class="btn btn-success w-100 btn-lg shadow fw-bold mt-2">Générer le PDF</button>
            </form>
        </div>

        <div class="col-md-8 d-none d-md-flex" id="preview-side">
            <div id="cv-preview" class="cv-page">
                <div class="cv-main">
                    <h1 class="name-display"><span id="out-firstname">PRÉNOM</span> <span id="out-lastname">NOM</span></h1>
                    <div id="out-headline" class="job-display">Titre du profil</div>
                    
                    <div class="section-title">Profil</div>
                    <p id="out-summary" class="small text-secondary" style="white-space: pre-line; text-align: justify;">Votre présentation apparaîtra ici...</p>
                    
                    <div class="section-title mt-4">Expérience Professionnelle</div>
                    <div id="out-experiences"></div>
                </div>

                <div class="cv-sidebar">
                    <img id="out-photo" src="" class="d-none">
                    
                    <div class="sidebar-title">Contact</div>
                    <div class="contact-preview">
                        <div id="out-address">Ville, Pays</div>
                        <div id="out-email">email@exemple.com</div>
                        <div id="out-phone">06 00 00 00 00</div>
                    </div>
                    
                    <div id="sidebar-dynamic-content" class="w-100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Fonction de mise à jour globale
    const refreshAll = () => {
        // Expériences
        const exps = document.getElementsByName('exp_company[]');
        const titles = document.getElementsByName('exp_title[]');
        const descs = document.getElementsByName('exp_desc[]');
        
        document.getElementById('out-experiences').innerHTML = Array.from(exps).map((e, i) => `
            <div class="mb-3">
                <div class="fw-bold text-dark">${e.value || 'Entreprise'}</div>
                <div class="text-primary small fw-semibold">${titles[i].value || 'Poste occupé'}</div>
                <div class="text-muted small" style="white-space: pre-line;">${descs[i].value}</div>
            </div>`).join('');

        // Compétences
        const skills = document.getElementsByName('skills[]');
        let skillHTML = skills.length ? '<div class="sidebar-title">Compétences</div>' : '';
        skillHTML += Array.from(skills).map(s => `<div class="small mt-1 text-light-50">${s.value}</div>`).join('');
        document.getElementById('sidebar-dynamic-content').innerHTML = skillHTML;
    };

    // Binding des champs simples
    ['firstname', 'lastname', 'headline', 'summary', 'address', 'email', 'phone'].forEach(id => {
        document.getElementById(`in-${id}`).addEventListener('input', (e) => {
            document.getElementById(`out-${id}`).innerText = e.target.value || (id.includes('name') ? id.replace('in-','').toUpperCase() : '');
        });
    });

    // Prévisualisation Photo
    document.getElementById('in-photo').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = (ev) => {
            const out = document.getElementById('out-photo');
            out.src = ev.target.result;
            out.classList.remove('d-none');
        };
        reader.readAsDataURL(e.target.files[0]);
    });

    // Ajouter une expérience
    document.getElementById('add-exp').addEventListener('click', () => {
        const html = `
            <div class="card p-3 mb-2 bg-light border-0">
                <input type="text" name="exp_company[]" class="form-control form-control-sm mb-2" placeholder="Entreprise">
                <input type="text" name="exp_title[]" class="form-control form-control-sm mb-2" placeholder="Poste">
                <textarea name="exp_desc[]" class="form-control form-control-sm mb-2" placeholder="Description des missions"></textarea>
                <button type="button" class="btn btn-sm btn-outline-danger border-0 text-start p-0" onclick="this.parentElement.remove(); refreshAll();">✕ Supprimer</button>
            </div>`;
        document.getElementById('experience-list').insertAdjacentHTML('beforeend', html);
    });

    // Ajouter une compétence
    document.getElementById('add-skill').addEventListener('click', () => {
        const html = `
            <div class="input-group mb-1">
                <input type="text" name="skills[]" class="form-control form-control-sm" placeholder="Ex: PHP, Gestion de projet...">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentElement.remove(); refreshAll();">✕</button>
            </div>`;
        document.getElementById('skills-list').insertAdjacentHTML('beforeend', html);
    });

    // Actualisation automatique
    document.addEventListener('input', refreshAll);
});
</script>
</body>
</html>