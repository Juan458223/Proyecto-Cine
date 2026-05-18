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
const modalHeader = document.getElementById("modal-header");
const modalTitle = document.getElementById("modal-title");
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

let currentEmail = "";
let currentAuthType = "validate_user";

function isValidGmail(email) {
    const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
    return gmailRegex.test(email.toLowerCase());
}

if (registerLink) {
    registerLink.addEventListener("click", (e) => {
        e.preventDefault();
        loginForm.reset();
        loginSection.classList.add("hidden");
        registerSection.classList.remove("hidden");
    });
}

if (recoverLink) {
    recoverLink.addEventListener("click", (e) => {
        e.preventDefault();
        loginForm.reset();
        loginSection.classList.add("hidden");
        recoverSection.classList.remove("hidden");
    });
}

const returnToLogin = (e) => {
    e.preventDefault();
    if (registerForm) registerForm.reset();
    if (recoverForm) recoverForm.reset();
    registerSection.classList.add("hidden");
    recoverSection.classList.add("hidden");
    loginSection.classList.remove("hidden");
};

if (backToLogin) backToLogin.addEventListener("click", returnToLogin);
if (backToLoginRecover) backToLoginRecover.addEventListener("click", returnToLogin);

/**
 * Función Principal de Apertura de Modal
 * El título se oculta permanentemente por JS para dejar solo la X
 */
function openModal(title, message, email, type) {
    currentEmail = email;
    currentAuthType = type;
    
    // Título siempre oculto por requerimiento del usuario
    modalTitle.classList.add("hidden");
    
    // Mensaje informativo (Blanco/Zinc)
    if (!message) {
        modalMessageContainer.classList.add("hidden");
    } else {
        modalMessage.textContent = message;
        modalMessageContainer.classList.remove("hidden");
    }
    
    // Reset visual completo
    authModal.classList.remove("hidden");
    authModal.classList.add("flex");
    modalLoading.classList.remove("hidden");
    modalForm.classList.add("hidden");
    if (changePasswordForm) changePasswordForm.classList.add("hidden");
    if (modalErrorBox) modalErrorBox.classList.add("hidden");
    if (contactAdminContainer) contactAdminContainer.classList.add("hidden");
}

function stopLoading() {
    modalLoading.classList.add("hidden");
    modalForm.classList.remove("hidden");
    setTimeout(() => modalCodeInput.focus(), 100);
}

const closeModal = () => {
    authModal.classList.add("hidden");
    authModal.classList.remove("flex");
    if (modalForm) modalForm.reset();
    if (changePasswordForm) changePasswordForm.reset();
};

if (closeModalBtn) closeModalBtn.addEventListener("click", closeModal);

// Backdrop Click
authModal.addEventListener("click", (e) => {
    if (e.target === authModal) closeModal();
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !authModal.classList.contains("hidden")) closeModal();
});

/**
 * Validación de entrada del Token (Solo números)
 */
if (modalCodeInput) {
    modalCodeInput.addEventListener("input", (e) => {
        // Eliminar cualquier caracter que no sea número
        e.target.value = e.target.value.replace(/\D/g, "");
    });
}

/**
 * Envío de formularios principales
 */
async function handleFormSubmit(form, action, type, title) {
    const formData = new FormData(form);

    let emptyFields = [];
    for (let [key, value] of formData.entries()) {
        if (!value.trim()) emptyFields.push(key);
    }

    if (emptyFields.length > 0) {
        showErrorInModal("Por favor complete todos los campos");
        return;
    }

    const email = formData.get("email");
    if ((action === "register" || action === "recover") && email && !isValidGmail(email)) {
        showErrorInModal("Solo correos Gmail (@gmail.com)");
        return;
    }

    openModal(title, "Procesando...", email, type);
    try {
        const response = await fetch(`../src/Controller/AuthController.php?action=${action}`, {
            method: "POST",
            body: formData
        });
        const result = await response.text();

        if (result.includes("exitoso") || result.includes("éxito") || result.includes("enviado") || result.includes("pendiente")) {
            if (result.includes("pendiente")) currentAuthType = "register_user";
            modalMessage.textContent = result;
            modalMessageContainer.classList.remove("hidden");
            stopLoading();
        } else {
            // Error en login/registro: Ocultamos "Procesando..." y mostramos el error
            modalMessageContainer.classList.add("hidden");
            showErrorInModal(result, true);
        }
    } catch (error) {
        modalMessageContainer.classList.add("hidden");
        showErrorInModal("Error de conexión");
    }
}

/**
 * Muestra errores sin "romper" el flujo actual del modal
 */
function showErrorInModal(message, showContact = false) {
    if (authModal.classList.contains("hidden")) {
        openModal("", "", "", "");
    }
    
    modalLoading.classList.add("hidden");
    
    if (modalErrorBox) {
        modalErrorBox.classList.remove("hidden");
        modalError.textContent = message;
        if (showContact && contactAdminContainer) {
            contactAdminContainer.classList.remove("hidden");
        }
    }
}

if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
        e.preventDefault();
        handleFormSubmit(loginForm, "login", "validate_user", "Inicio de Sesión");
    });
}

if (registerForm) {
    registerForm.addEventListener("submit", (e) => {
        e.preventDefault();
        if (registerForm.password.value !== registerForm.confirm_password.value) {
            showErrorInModal("Las contraseñas no coinciden");
            return;
        }
        handleFormSubmit(registerForm, "register", "register_user", "Registro");
    });
}

if (recoverForm) {
    recoverForm.addEventListener("submit", (e) => {
        e.preventDefault();
        handleFormSubmit(recoverForm, "recover", "reset_password", "Recuperar");
    });
}

/**
 * Lógica de Verificación (Token) - Mantiene mensajes anteriores
 */
if (modalForm) {
    modalForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const code = modalCodeInput.value.trim();
        
        // Validación en cliente: 6 dígitos numéricos
        if (code.length !== 6) {
            showErrorInModal("Ingrese los 6 dígitos del código");
            return;
        }


        const originalInstruction = modalMessage.textContent; // Guardar instrucción actual
        
        modalErrorBox.classList.add("hidden");
        modalForm.classList.add("hidden");
        modalLoading.classList.remove("hidden");
        modalMessageContainer.classList.remove("hidden");
        modalMessage.textContent = "Verificando...";

        try {
            const response = await fetch("../src/Controller/AuthController.php?action=verify", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `email=${encodeURIComponent(currentEmail)}&token=${encodeURIComponent(code)}&type=${currentAuthType}`
            });
            const result = await response.text();
            
            if (result === "token valido") {
                modalLoading.classList.add("hidden");
                if (currentAuthType === "reset_password") {
                    modalMessage.textContent = "Escriba su nueva contraseña";
                    if (changePasswordForm) changePasswordForm.classList.remove("hidden");
                } else {
                    modalMessage.innerHTML = "¡Verificación exitosa!<br>Redireccionando...";
                    setTimeout(() => window.location.href = "home.php", 1500);
                }
            } else {
                modalLoading.classList.add("hidden");
                modalForm.classList.remove("hidden");

                // RESTAURAR instrucción anterior y mostrar error abajo
                modalMessage.textContent = "Ingrese el código";


                
                const errorMsg = result === "token expirado" ? "El código ha expirado" : "Código incorrecto";
                showErrorInModal(errorMsg);
            }
        } catch (error) {
            modalLoading.classList.add("hidden");
            modalForm.classList.remove("hidden");
            modalMessage.textContent = originalInstruction;
            showErrorInModal("Error en el sistema de verificación");
        }
    });
}

/**
 * Cambio de contraseña final
 */
if (changePasswordForm) {
    changePasswordForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const pass = newPasswordInput.value.trim();
        const confirm = confirmNewPasswordInput.value.trim();

        modalErrorBox.classList.add("hidden");

        if (!pass || !confirm) {
            showErrorInModal("Complete todos los campos");
            return;
        }

        if (pass !== confirm) {
            showErrorInModal("Las contraseñas no coinciden");
            return;
        }
        
        modalLoading.classList.remove("hidden");
        changePasswordForm.classList.add("hidden");
        modalMessageContainer.classList.remove("hidden");
        modalMessage.textContent = "Actualizando...";
        
        try {
            const response = await fetch("../src/Controller/AuthController.php?action=change_password", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `email=${encodeURIComponent(currentEmail)}&password=${encodeURIComponent(pass)}`
            });
            const result = await response.text();
            
            if (result.includes("éxito")) {
                modalLoading.classList.add("hidden");
                modalMessage.innerHTML = "¡Contraseña actualizada!<br>Reiniciando sesión...";
                setTimeout(() => window.location.href = "index.php", 2000);
            } else {
                modalLoading.classList.add("hidden");
                changePasswordForm.classList.remove("hidden");
                showErrorInModal(result);
            }
        } catch (error) {
            modalLoading.classList.add("hidden");
            changePasswordForm.classList.remove("hidden");
            showErrorInModal("Error al actualizar");
        }
    });
}