document.addEventListener('DOMContentLoaded', () => {
    // Mise à jour des champs simples
    const fields = ['firstname', 'lastname', 'headline', 'summary', 'address', 'phone', 'email', 'linkedin'];
    fields.forEach(id => {
        document.getElementById(`in-${id}`).addEventListener('input', (e) => {
            document.getElementById(`out-${id}`).innerText = e.target.value;
        });
    });

    // --- GESTION DES EXPÉRIENCES (GAUCHE) ---
    document.getElementById('add-exp').addEventListener('click', () => {
        const id = Date.now();
        const html = `
            <div class="card p-2 mb-2 bg-light" id="exp-f-${id}">
                <input type="text" name="exp_company[]" class="form-control form-control-sm mb-1" placeholder="Entreprise" oninput="refreshAll()">
                <input type="text" name="exp_title[]" class="form-control form-control-sm mb-1" placeholder="Poste" oninput="refreshAll()">
                <textarea name="exp_desc[]" class="form-control form-control-sm" placeholder="Missions (puces...)" oninput="refreshAll()"></textarea>
                <button type="button" class="btn btn-danger btn-sm mt-1" onclick="document.getElementById('exp-f-${id}').remove();refreshAll();">Supprimer</button>
            </div>`;
        document.getElementById('experience-list').insertAdjacentHTML('beforeend', html);
    });

    // --- GESTION SIDEBAR (DROITE) ---
    document.getElementById('add-skill').addEventListener('click', () => addSidebarInput('skills[]', 'Compétence (ex: HTML)'));
    document.getElementById('add-edu').addEventListener('click', () => {
        const id = Date.now();
        const html = `<div class="card p-2 mb-2" id="edu-f-${id}">
            <input type="text" name="edu_school[]" class="form-control form-control-sm mb-1" placeholder="École" oninput="refreshAll()">
            <input type="text" name="edu_degree[]" class="form-control form-control-sm" placeholder="Diplôme" oninput="refreshAll()">
            <button type="button" class="btn btn-danger btn-sm mt-1" onclick="this.parentElement.remove();refreshAll();">x</button>
        </div>`;
        document.getElementById('education-list').insertAdjacentHTML('beforeend', html);
    });
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

function addSidebarInput(name, placeholder) {
    const html = `<div class="input-group mb-1">
        <input type="text" name="${name}" class="form-control form-control-sm" placeholder="${placeholder}" oninput="refreshAll()">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentElement.remove();refreshAll();">x</button>
    </div>`;
    document.getElementById('skills-list').insertAdjacentHTML('beforeend', html);
}

function refreshAll() {
    // Update Experiences
    const exps = document.getElementsByName('exp_company[]');
    const titles = document.getElementsByName('exp_title[]');
    const descs = document.getElementsByName('exp_desc[]');
    document.getElementById('out-experiences').innerHTML = Array.from(exps).map((e, i) => `
        <div class="mb-3 text-start">
            <div class="fw-bold">${e.value}</div>
            <div class="fst-italic">${titles[i].value}</div>
            <div style="font-size:12px; white-space: pre-line;">${descs[i].value}</div>
        </div>`).join('');

    // Update Sidebar
    let sidebarHTML = '';
    
    // Skills
    const skills = document.getElementsByName('skills[]');
    if(skills.length > 0) {
        sidebarHTML += `<h3 class="section-title">SKILLS</h3><div class="text-end" style="font-size:12px;">` + 
            Array.from(skills).map(s => `<div>${s.value}</div>`).join('') + `</div>`;
    }

    // Education
    const schools = document.getElementsByName('edu_school[]');
    const degrees = document.getElementsByName('edu_degree[]');
    if(schools.length > 0) {
        sidebarHTML += `<h3 class="section-title">EDUCATION</h3>` + 
            Array.from(schools).map((s, i) => `<div class="text-end mb-2" style="font-size:12px;"><div class="fw-bold">${s.value}</div><div>${degrees[i].value}</div></div>`).join('');
    }

    // Custom
    const cTitles = document.getElementsByName('custom_title[]');
    const cBodys = document.getElementsByName('custom_body[]');
    sidebarHTML += Array.from(cTitles).map((t, i) => `
        <h3 class="section-title">${t.value.toUpperCase()}</h3>
        <div class="text-end" style="font-size:12px; white-space: pre-line;">${cBodys[i].value}</div>
    `).join('');

    document.getElementById('sidebar-dynamic-content').innerHTML = sidebarHTML;
}