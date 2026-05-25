const loginSection = document.getElementById("login-section");
const registerSection = document.getElementById("register-section");
const recoverSection = document.getElementById("recover-section");

const loginForm = document.getElementById("login-form");
const registerForm = document.getElementById("register-form");
const recoverForm = document.getElementById("recover-form");

const registerLink = document.getElementById("register-link");
const recoverLink = document.getElementById("recover-link");
const backToLogin = document.getElementById("back-to-login");
const backToLoginRecover = document.getElementById("back-to-login-recover");

const authModal = document.getElementById("auth-modal");
const modalContainerInner = document.getElementById("modal-container-inner");
const modalMessage = document.getElementById("modal-message");
const modalMessageContainer = document.getElementById("modal-message-container");
const modalLoading = document.getElementById("modal-loading");
const modalForm = document.getElementById("modal-form");
const modalCodeInput = document.getElementById("verification-code");
const modalError = document.getElementById("modal-error");
const modalErrorBox = document.getElementById("modal-error-box");
const contactAdminContainer = document.getElementById("contact-admin-container");
const closeModalBtn = document.getElementById("close-auth-modal");

const changePasswordForm = document.getElementById("change-password-form");
const newPasswordInput = document.getElementById("new-password");
const confirmNewPasswordInput = document.getElementById("confirm-new-password");

window.togglePasswordVisibility = function(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    const btn = input.parentElement.querySelector(".password-toggle-btn");
    const type = input.getAttribute("type") === "password" ? "text" : "password";
    input.setAttribute("type", type);
    
    // Cambiar icono (Añadir/Quitar tachado)
    if (btn) {
        const slashPath = btn.querySelector(".eye-slash");
        if (type === "text") {
            slashPath.classList.remove("hidden");
        } else {
            slashPath.classList.add("hidden");
        }
    }
};

let currentEmail = "";
let currentAuthType = "validate_user";

function isValidGmail(email) {
    const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
    return gmailRegex.test(email.toLowerCase());
}

function showSection(toShow, toHide, direction = "right") {
    if (toHide) {
        toHide.classList.add("hidden");
        toHide.classList.remove("animate-slide-right", "animate-slide-left");
    }
    if (toShow) {
        toShow.classList.remove("hidden");
        const animationClass = direction === "right" ? "animate-slide-right" : "animate-slide-left";
        toShow.classList.add(animationClass);
    }
}

if (registerLink) {
    registerLink.addEventListener("click", (e) => {
        e.preventDefault();
        if (loginForm) loginForm.reset();
        showSection(registerSection, loginSection, "right");
    });
}

if (recoverLink) {
    recoverLink.addEventListener("click", (e) => {
        e.preventDefault();
        if (loginForm) loginForm.reset();
        showSection(recoverSection, loginSection, "right");
    });
}

const returnToLogin = (e) => {
    e.preventDefault();
    if (registerForm) registerForm.reset();
    if (recoverForm) recoverForm.reset();
    
    const currentSection = !registerSection.classList.contains("hidden") ? registerSection : recoverSection;
    showSection(loginSection, currentSection, "left");
};

if (backToLogin) backToLogin.addEventListener("click", returnToLogin);
if (backToLoginRecover) backToLoginRecover.addEventListener("click", returnToLogin);

/**
 * Función Principal de Apertura de Modal (MANTENIENDO TU LÓGICA Y TUS IDS)
 */
window.openModal = function(title_ignored, message, email, type) {
    currentEmail = email;
    currentAuthType = type;
    
    // Título eliminado por UX
    if (message) {
        modalMessage.textContent = message;
        modalMessageContainer.classList.remove("hidden");
    } else {
        modalMessageContainer.classList.add("hidden");
    }
    
    // Reset visual
    authModal.classList.remove("hidden", "pointer-events-none");
    requestAnimationFrame(() => {
        authModal.classList.add("opacity-100");
        if (modalContainerInner) {
            modalContainerInner.classList.remove("scale-95");
            modalContainerInner.classList.add("scale-100");
        }
    });

    modalLoading.classList.remove("hidden");
    modalForm.classList.add("hidden");
    if (changePasswordForm) changePasswordForm.classList.add("hidden");
    
    // Limpiar errores previos al abrir
    if (modalErrorBox) modalErrorBox.classList.add("hidden");
    if (contactAdminContainer) contactAdminContainer.classList.add("hidden");
    if (modalCodeInput) {
        modalCodeInput.classList.remove("border-red-500");
    }
};

window.stopLoading = function() {
    modalLoading.classList.add("hidden");
    modalForm.classList.remove("hidden");
    setTimeout(() => { if (modalCodeInput) modalCodeInput.focus(); }, 100);
};

window.closeModal = function() {
    authModal.classList.remove("opacity-100");
    if (modalContainerInner) {
        modalContainerInner.classList.add("scale-95");
        modalContainerInner.classList.remove("scale-100");
    }
    setTimeout(() => {
        authModal.classList.add("hidden", "pointer-events-none");
        // Los inputs de las secciones NO se borran aquí por petición del usuario
    }, 500);
};

if (closeModalBtn) closeModalBtn.addEventListener("click", window.closeModal);

// Cierre por fondo
authModal.addEventListener("click", (e) => {
    if (e.target === authModal) window.closeModal();
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !authModal.classList.contains("hidden")) window.closeModal();
});

if (modalCodeInput) {
    modalCodeInput.addEventListener("input", (e) => {
        e.target.value = e.target.value.replace(/\D/g, "");
        // Limpiar error al escribir
        modalErrorBox.classList.add("hidden");
        modalCodeInput.classList.remove("border-red-500");
    });
}

/**
 * Envío de formularios (CON TUS VALIDACIONES ORIGINALES)
 */
async function handleFormSubmit(form, action, type, title) {
    const formData = new FormData(form);

    let emptyFields = [];
    for (let [key, value] of formData.entries()) {
        if (!value.trim()) emptyFields.push(key);
    }
    if (emptyFields.length > 0) {
        // Para formularios externos seguimos usando el modal de error completo
        window.showErrorInModal("Por favor complete todos los campos");
        return;
    }

    const email = formData.get("email");
    if ((action === "register" || action === "recover") && email && !isValidGmail(email)) {
        window.showErrorInModal("Solo correos Gmail (@gmail.com)");
        return;
    }

    window.openModal(title, "Procesando...", email, type);
    try {
        const response = await fetch(`../src/Controller/AuthController.php?action=${action}`, {
            method: "POST",
            body: formData
        });
        const result = await response.text();

        if (result.includes("exitoso") || result.includes("éxito") || result.includes("enviado") || result.includes("pendiente")) {
            if (result.includes("pendiente")) currentAuthType = "register_user";
            modalMessage.textContent = result;
            window.stopLoading();
        } else {
            window.showErrorInModal(result, true);
        }
    } catch (error) {
        window.showErrorInModal("Error de comunicación");
    }
}

window.showErrorInModal = function(message, showContact = false) {
    if (authModal.classList.contains("hidden")) window.openModal("", "", "", "");
    
    modalLoading.classList.add("hidden");
    
    // Ocultamos el mensaje previo SIEMPRE en caso de error de validación
    // para que no se mezcle "Escriba su contraseña" con "Complete los campos"
    modalMessageContainer.classList.add("hidden");
    modalMessage.textContent = "";

    modalForm.classList.remove("opacity-50", "pointer-events-none");
    
    // Siempre mostramos el error en el error box
    if (modalErrorBox) {
        modalErrorBox.classList.remove("hidden");
        modalError.textContent = message;
    }
    
    // Si el mensaje sugiere contactar al administrador o showContact es true
    if (showContact || message.includes("contacte al administrador")) {
        if (contactAdminContainer) contactAdminContainer.classList.remove("hidden");
    }

    // Si el input de token existe, lo resaltamos
    if (modalCodeInput && !modalForm.classList.contains("hidden")) {
        modalCodeInput.classList.add("border-red-500");
    }
};

if (loginForm) loginForm.onsubmit = (e) => { e.preventDefault(); handleFormSubmit(loginForm, "login", "validate_user", "Sesión"); };
if (registerForm) registerForm.onsubmit = (e) => { 
    e.preventDefault(); 
    if (registerForm.password.value !== registerForm.confirm_password.value) {
        window.showErrorInModal("Las contraseñas no coinciden");
        return;
    }
    handleFormSubmit(registerForm, "register", "register_user", "Registro"); 
};
if (recoverForm) recoverForm.onsubmit = (e) => { e.preventDefault(); handleFormSubmit(recoverForm, "recover", "reset_password", "Recuperar"); };

/**
 * Lógica del Token (RESTAURADA)
 */
if (modalForm) {
    modalForm.onsubmit = async (e) => {
        e.preventDefault();
        const code = modalCodeInput.value.trim();
        if (code.length !== 6) {
            window.showErrorInModal("Ingrese el código de 6 dígitos");
            return;
        }

        modalErrorBox.classList.add("hidden");
        modalLoading.classList.remove("hidden");
        modalForm.classList.add("opacity-50", "pointer-events-none");

        try {
            const response = await fetch("../src/Controller/AuthController.php?action=verify", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `email=${encodeURIComponent(currentEmail)}&token=${encodeURIComponent(code)}&type=${currentAuthType}`
            });
            const result = await response.text();
            
            modalLoading.classList.add("hidden");
            modalForm.classList.remove("opacity-50", "pointer-events-none");

            if (result === "token valido") {
                modalForm.classList.add("hidden");
                if (currentAuthType === "reset_password") {
                    modalMessage.textContent = "Escriba su nueva contraseña";
                    changePasswordForm.classList.remove("hidden");
                } else {
                    modalMessage.innerHTML = "¡Éxito!<br><span class='text-sm opacity-60 font-medium'>Redireccionando...</span>";
                    setTimeout(() => window.location.href = "../src/View/home.php", 1500);
                }
            } else {
                window.showErrorInModal(result === "token expirado" ? "Código expirado" : "Código incorrecto");
            }
        } catch (error) {
            modalLoading.classList.add("hidden");
            modalForm.classList.remove("opacity-50", "pointer-events-none");
            window.showErrorInModal("Error de sistema");
        }
    };
}

/**
 * Lógica de Cambio de Contraseña Final (CON VALIDACIÓN)
 */
if (changePasswordForm) {
    changePasswordForm.onsubmit = async (e) => {
        e.preventDefault();
        const pass = document.getElementById("new-password").value;
        const confirm = document.getElementById("confirm-new-password").value;

        if (!pass || !confirm) {
            window.showErrorInModal("Por favor complete ambos campos");
            return;
        }

        if (pass !== confirm) {
            window.showErrorInModal("Las contraseñas no coinciden");
            return;
        }

        modalLoading.classList.remove("hidden");
        changePasswordForm.classList.add("opacity-50", "pointer-events-none");

        try {
            const response = await fetch("../src/Controller/AuthController.php?action=change_password", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `email=${encodeURIComponent(currentEmail)}&password=${encodeURIComponent(pass)}`
            });
            const result = await response.text();
            
            modalLoading.classList.add("hidden");
            if (result.includes("exitoso") || result.includes("éxito")) {
                modalMessage.innerHTML = "Contraseña actualizada con éxito.<br><span class='text-sm opacity-60 font-medium'>Redirigiendo...</span>";
                modalMessageContainer.classList.remove("hidden");
                changePasswordForm.classList.add("hidden");
                setTimeout(() => window.location.href = "index.php", 2000);
            } else {
                changePasswordForm.classList.remove("opacity-50", "pointer-events-none");
                window.showErrorInModal(result);
            }
        } catch (error) {
            modalLoading.classList.add("hidden");
            changePasswordForm.classList.remove("opacity-50", "pointer-events-none");
            window.showErrorInModal("Error de servidor");
        }
    };
}
