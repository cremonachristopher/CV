document.addEventListener('DOMContentLoaded', () => {
    // 1. MISE À JOUR DES CHAMPS SIMPLES
    const fields = ['firstname', 'lastname', 'headline', 'summary', 'address', 'phone', 'email', 'linkedin'];
    fields.forEach(id => {
        const input = document.getElementById(`in-${id}`);
        if (input) {
            input.addEventListener('input', (e) => {
                document.getElementById(`out-${id}`).innerText = e.target.value || "";
                if(id === 'summary') refreshAll(); // Gère le formatage du texte
            });
        }
    });

    // 2. PRÉVISUALISATION DE LA PHOTO
    const photoInput = document.getElementById('in-photo');
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('out-photo');
                preview.src = event.target.result;
                preview.classList.remove('d-none'); // Affiche l'image
            }
            reader.readAsDataURL(file);
        }
    });

    // 3. AJOUT DYNAMIQUE DES SECTIONS
    // --- Expériences ---
    document.getElementById('add-exp').addEventListener('click', () => {
        const id = Date.now();
        const html = `
            <div class="card p-2 mb-2 bg-light" id="exp-f-${id}">
                <input type="text" name="exp_company[]" class="form-control form-control-sm mb-1" placeholder="Entreprise" oninput="refreshAll()">
                <input type="text" name="exp_title[]" class="form-control form-control-sm mb-1" placeholder="Poste" oninput="refreshAll()">
                <textarea name="exp_desc[]" class="form-control form-control-sm" placeholder="Missions..." oninput="refreshAll()"></textarea>
                <button type="button" class="btn btn-danger btn-sm mt-1" onclick="this.parentElement.remove();refreshAll();">Supprimer</button>
            </div>`;
        document.getElementById('experience-list').insertAdjacentHTML('beforeend', html);
    });

    // --- Compétences ---
    document.getElementById('add-skill').addEventListener('click', () => {
        const html = `<div class="input-group mb-1">
            <input type="text" name="skills[]" class="form-control form-control-sm" placeholder="Ex: PHP" oninput="refreshAll()">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentElement.remove();refreshAll();">x</button>
        </div>`;
        document.getElementById('skills-list').insertAdjacentHTML('beforeend', html);
    });

    // --- Éducation ---
    document.getElementById('add-edu').addEventListener('click', () => {
        const id = Date.now();
        const html = `<div class="card p-2 mb-2" id="edu-f-${id}">
            <input type="text" name="edu_school[]" class="form-control form-control-sm mb-1" placeholder="École" oninput="refreshAll()">
            <input type="text" name="edu_degree[]" class="form-control form-control-sm" placeholder="Diplôme" oninput="refreshAll()">
            <button type="button" class="btn btn-danger btn-sm mt-1" onclick="this.parentElement.remove();refreshAll();">x</button>
        </div>`;
        document.getElementById('education-list').insertAdjacentHTML('beforeend', html);
    });

    // --- Section Libre ---
    document.getElementById('add-custom').addEventListener('click', () => {
        const id = Date.now();
        const html = `<div class="card p-2 mb-2 bg-dark text-white" id="cust-f-${id}">
            <input type="text" name="custom_title[]" class="form-control form-control-sm mb-1" placeholder="Titre Section" oninput="refreshAll()">
            <textarea name="custom_body[]" class="form-control form-control-sm" placeholder="Contenu" oninput="refreshAll()"></textarea>
            <button type="button" class="btn btn-danger btn-sm mt-1" onclick="this.parentElement.remove();refreshAll();">x</button>
        </div>`;
        document.getElementById('custom-sections-list').insertAdjacentHTML('beforeend', html);
    });
});

// 4. RÉGÉNÉRATION GLOBALE DE L'APERÇU
function refreshAll() {
    // Mise à jour des Expériences (Main Content)
    const exps = document.getElementsByName('exp_company[]');
    const titles = document.getElementsByName('exp_title[]');
    const descs = document.getElementsByName('exp_desc[]');
    document.getElementById('out-experiences').innerHTML = Array.from(exps).map((e, i) => `
        <div class="mb-3">
            <div class="fw-bold" style="font-size: 18px;">${e.value || 'Entreprise'}</div>
            <div class="fst-italic">${titles[i].value || 'Poste'}</div>
            <div style="font-size: 14px; white-space: pre-line; margin-top:5px;">${descs[i].value}</div>
        </div>`).join('');

    // Mise à jour de la Sidebar (Dynamic Content)
    let sidebarHTML = '';

    // Compétences
    const skills = document.getElementsByName('skills[]');
    if(skills.length > 0) {
        sidebarHTML += `<h3 class="section-title">COMPÉTENCES</h3><div class="text-end">` + 
            Array.from(skills).map(s => `<div>${s.value}</div>`).join('') + `</div>`;
    }

    // Éducation
    const schools = document.getElementsByName('edu_school[]');
    const degrees = document.getElementsByName('edu_degree[]');
    if(schools.length > 0) {
        sidebarHTML += `<h3 class="section-title">FORMATION</h3>` + 
            Array.from(schools).map((s, i) => `<div class="text-end mb-2"><strong>${s.value}</strong><br><span>${degrees[i].value}</span></div>`).join('');
    }

    // Sections Libres
    const cTitles = document.getElementsByName('custom_title[]');
    const cBodys = document.getElementsByName('custom_body[]');
    sidebarHTML += Array.from(cTitles).map((t, i) => `
        <h3 class="section-title">${t.value.toUpperCase() || 'SECTION'}</h3>
        <div class="text-end" style="white-space: pre-line;">${cBodys[i].value}</div>
    `).join('');

    document.getElementById('sidebar-dynamic-content').innerHTML = sidebarHTML;
}