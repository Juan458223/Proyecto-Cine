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
const modalTitle = document.getElementById("modal-title");
const modalMessage = document.getElementById("modal-message");
const modalLoading = document.getElementById("modal-loading");
const modalForm = document.getElementById("modal-form");
const modalCodeInput = document.getElementById("verification-code");
const modalError = document.getElementById("modal-error");
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
    registerForm.reset();
    recoverForm.reset();
    registerSection.classList.add("hidden");
    recoverSection.classList.add("hidden");
    loginSection.classList.remove("hidden");
};

if (backToLogin) backToLogin.addEventListener("click", returnToLogin);
if (backToLoginRecover) backToLoginRecover.addEventListener("click", returnToLogin);

function openModal(title, message, email, type) {
    currentEmail = email;
    currentAuthType = type;
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    authModal.classList.remove("hidden");
    authModal.classList.add("flex");
    modalLoading.classList.remove("hidden");
    modalForm.classList.add("hidden");
    if (changePasswordForm) {
        changePasswordForm.classList.add("hidden");
        changePasswordForm.reset();
    }
    modalError.classList.add("hidden");
    modalCodeInput.value = "";
}

function stopLoading() {
    modalLoading.classList.add("hidden");
    modalForm.classList.remove("hidden");
}

if (closeModalBtn) {
    closeModalBtn.addEventListener("click", () => {
        authModal.classList.add("hidden");
        authModal.classList.remove("flex");
        modalForm.reset();
        if (changePasswordForm) changePasswordForm.reset();
    });
}

async function handleFormSubmit(form, action, type, title) {
    const formData = new FormData(form);

    let emptyFields = [];
    for (let [key, value] of formData.entries()) {
        if (!value.trim()) {
            emptyFields.push(key);
        }
    }

    if (emptyFields.length > 0) {
        openModal(title, "Error", "", type);
        stopLoading();
        modalForm.classList.add("hidden");
        modalError.textContent = "Por favor, llene todos los campos.";
        modalError.classList.remove("hidden");
        return;
    }

    const email = formData.get("email");
    if ((action === "register" || action === "recover") && email && !isValidGmail(email)) {
        openModal(title, "Error", "", type);
        stopLoading();
        modalForm.classList.add("hidden");
        modalError.textContent = "Solo se permiten correos de Gmail (@gmail.com).";
        modalError.classList.remove("hidden");
        return;
    }

    openModal(title, "Procesando solicitud...", email, type);
    try {
        const response = await fetch(`../src/Controller/AuthController.php?action=${action}`, {
            method: "POST",
            body: formData
        });
        const result = await response.text();

        if (result.includes("exitoso") || result.includes("éxito") || result.includes("enviado") || result.includes("pendiente")) {
            if (result.includes("pendiente")) {
                currentAuthType = "register_user";
            }
            modalMessage.textContent = result;
            stopLoading();
        } else {
            modalMessage.textContent = "Error";
            stopLoading();
            modalForm.classList.add("hidden");
            modalError.textContent = result;
            modalError.classList.remove("hidden");
        }
    } catch (error) {
        modalMessage.textContent = "Error de conexión.";
        stopLoading();
        modalForm.classList.add("hidden");
        modalError.textContent = "No se pudo conectar con el servidor.";
        modalError.classList.remove("hidden");
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
            openModal("Registro", "Error", "", "register_user");
            stopLoading();
            modalForm.classList.add("hidden");
            modalError.textContent = "Las contraseñas no coinciden.";
            modalError.classList.remove("hidden");
            return;
        }
        handleFormSubmit(registerForm, "register", "register_user", "Registro de Usuario");
    });
}

if (recoverForm) {
    recoverForm.addEventListener("submit", (e) => {
        e.preventDefault();
        handleFormSubmit(recoverForm, "recover", "reset_password", "Recuperar Contraseña");
    });
}

if (modalForm) {
    modalForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const code = modalCodeInput.value.trim();
        if (!code) {
            modalError.textContent = "Por favor, ingrese el código de verificación.";
            modalError.classList.remove("hidden");
            return;
        }
        modalError.classList.add("hidden");
        try {
            const response = await fetch("../src/Controller/AuthController.php?action=verify", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `email=${encodeURIComponent(currentEmail)}&token=${encodeURIComponent(code)}&type=${currentAuthType}`
            });
            const result = await response.text();
            if (result === "token valido") {
                if (currentAuthType === "reset_password") {
                    modalForm.classList.add("hidden");
                    modalMessage.textContent = "Token validado. Ingresa tu nueva contraseña.";
                    if (changePasswordForm) changePasswordForm.classList.remove("hidden");
                } else {
                    modalForm.classList.add("hidden");
                    modalMessage.innerHTML = "<span class='font-bold'>¡Verificación exitosa!</span><br>Redirigiendo...";
                    setTimeout(() => {
                        window.location.href = "home.php";
                    }, 2000);
                }
            } else {
                modalError.textContent = result === "token expirado" ? "El código ha expirado." : "Código incorrecto.";
                modalError.classList.remove("hidden");
            }
        } catch (error) {
            modalError.textContent = "Error al verificar.";
            modalError.classList.remove("hidden");
        }
    });
}

if (changePasswordForm) {
    changePasswordForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const pass = newPasswordInput.value.trim();
        const confirm = confirmNewPasswordInput.value.trim();

        modalError.classList.add("hidden");

        if (!pass || !confirm) {
            modalError.textContent = "Por favor, complete ambos campos de contraseña.";
            modalError.classList.remove("hidden");
            return;
        }

        if (pass !== confirm) {
            modalError.textContent = "Las contraseñas no coinciden.";
            modalError.classList.remove("hidden");
            return;
        }
        try {
            const response = await fetch("../src/Controller/AuthController.php?action=change_password", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `email=${encodeURIComponent(currentEmail)}&password=${encodeURIComponent(pass)}`
            });
            const result = await response.text();
            if (result.includes("éxito")) {
                changePasswordForm.classList.add("hidden");
                modalMessage.innerHTML = `<span class='font-bold'>${result}</span><br>Redirigiendo...`;
                setTimeout(() => {
                    window.location.href = "index.php";
                }, 2000);
            } else {
                modalError.textContent = result;
                modalError.classList.remove("hidden");
            }
        } catch (error) {
            modalError.textContent = "Error al actualizar contraseña.";
            modalError.classList.remove("hidden");
        }
    });
}