const API_BASE_URL = "http://localhost/intranetV1/web/php/vsm-api/public";
const token = localStorage.getItem("token");

/* ======================
   UTILITAIRES
====================== */

function logout(e) {
    if (e) e.preventDefault();
    localStorage.removeItem("token");
    window.location.href = "login.html";
}

const logoutBtn = document.getElementById("logout");
if (logoutBtn) {
    logoutBtn.addEventListener("click", logout);
}

/**
 * On protège les pages sensibles (dashboard, patient, create)
 * mais on ne bloque pas login.html.
 */
const isLoginPage = window.location.pathname.includes("login.html");
const isProtectedPage = !isLoginPage; // toutes les pages sauf login

if (isProtectedPage && !token) {
    // Pas de token -> retour login
    window.location.href = "login.html";
}

/* ======================
   LOGIN
====================== */

const loginForm = document.getElementById("loginForm");

if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");
        const errorEl = document.getElementById("error");

        const email = emailInput.value;
        const password = passwordInput.value;

        errorEl.textContent = "";

        try {
            const res = await fetch(`${API_BASE_URL}/auth/login`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            });

            const data = await res.json();

            if (!data.success) {
                errorEl.textContent = data.message || "Erreur de connexion";
                return;
            }

            localStorage.setItem("token", data.data.token);
            window.location.href = "dashboard.html";

        } catch (err) {
            console.error(err);
            errorEl.textContent = "Impossible de contacter l’API";
        }
    });
}

/* ======================
   DASHBOARD
====================== */

const dashboardStatus = document.getElementById("dashboardStatus");

if (dashboardStatus) {
    fetch(`${API_BASE_URL}/auth/me`, {
        headers: { Authorization: `Bearer ${token}` }
    })
        .then(r => r.json())
        .then(d => {
            dashboardStatus.textContent = d.success
                ? "Utilisateur authentifié (JWT valide)"
                : "Session invalide";
        })
        .catch(() => {
            dashboardStatus.textContent = "Erreur lors de l’appel API";
        });
}

/* ======================
   PAGE PATIENT (LISTE + FICHE)
====================== */

const patientsList = document.getElementById("patientsList");
const patientNom = document.getElementById("patientNom");

/**
 * LISTE DES PATIENTS
 * (mock simple pour la démo AT3)
 */
if (patientsList) {
    const mockPatients = [
        { id: 1, nom: "Durand", prenom: "Marie" },
        { id: 2, nom: "Dupont", prenom: "Raymon" },
        { id: 3, nom: "Martin", prenom: "Alice" }
    ];

    patientsList.innerHTML = "";

    mockPatients.forEach(p => {
        const li = document.createElement("li");
        li.innerHTML = `
            <span>${p.prenom} ${p.nom}</span>
            <a href="#" data-id="${p.id}">Voir</a>
        `;
        patientsList.appendChild(li);
    });

    patientsList.addEventListener("click", (e) => {
        if (e.target.tagName === "A") {
            e.preventDefault();
            const id = e.target.dataset.id;
            loadPatient(id);
        }
    });
}

/**
 * CHARGEMENT FICHE PATIENT (API RÉELLE)
 */
function loadPatient(id) {
    if (!id) return;

    fetch(`${API_BASE_URL}/patient?id=${id}`, {
        headers: { Authorization: `Bearer ${token}` }
    })
        .then(r => r.json())
        .then(d => {
            if (!d.success) {
                alert(d.message || "Patient introuvable");
                return;
            }

            const p = d.data;

            const nomEl = document.getElementById("patientNom");
            const prenomEl = document.getElementById("patientPrenom");
            const naissanceEl = document.getElementById("patientNaissance");
            const vulnEl = document.getElementById("patientVulnerabilite");

            if (!nomEl || !prenomEl || !naissanceEl || !vulnEl) {
                console.error("Éléments de fiche patient manquants dans le DOM");
                return;
            }

            nomEl.textContent = p.nom;
            prenomEl.textContent = p.prenom;
            naissanceEl.textContent = p.date_naissance ?? "—";
            vulnEl.textContent = p.vulnerabilite ?? "—";
        })
        .catch(err => {
            console.error(err);
            alert("Erreur lors de l’appel API patient");
        });
}

/* ======================
   CREATE PATIENT
====================== */

const createForm = document.getElementById("createPatientForm");

if (createForm) {
    createForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const nom = document.getElementById("nom").value;
        const prenom = document.getElementById("prenom").value;
        const date_naissance = document.getElementById("date_naissance").value;
        const vulnerabilite = document.getElementById("vulnerabilite").value;

        try {
            const res = await fetch(`${API_BASE_URL}/patient`, {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    nom,
                    prenom,
                    date_naissance,
                    vulnerabilite
                })
            });

            const data = await res.json();

            if (!data.success) {
                alert(data.message || "Erreur création patient");
                return;
            }

            window.location.href = "patient.html?id=" + data.data.patient_id;

        } catch (err) {
            console.error(err);
            alert("Impossible de contacter l’API");
        }
    });
}

/* ======================
   CHARGEMENT AUTO SI ID DANS L’URL
====================== */

const urlId = new URLSearchParams(window.location.search).get("id");
if (urlId && patientNom) {
    loadPatient(urlId);
}
