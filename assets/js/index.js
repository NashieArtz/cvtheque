const items = document.querySelectorAll('.accordion button');

function toggleAccordion() {
    const itemToggle = this.getAttribute('aria-expanded');

    for (i = 0; i < items.length; i++) {
        items[i].setAttribute('aria-expanded', 'false');
    }

    if (itemToggle == 'false') {
        this.setAttribute('aria-expanded', 'true');
    }
}

items.forEach((item) => item.addEventListener('click', toggleAccordion));



// PAGE REGISTER

// S'assurer que le script n'exécute que quand le document est chargé
document.addEventListener('DOMContentLoaded', registering);

function registering() {
    const registerPage = document.querySelector(".register-page");
    if(registerPage){

        // SÉLÉCTEURS
        const usernameRegister = document.getElementById("username-register");
        const pwdRegister = document.getElementById("pwd-register");
        const repwdRegister = document.getElementById("repwd-register");
        const emailRegister = document.getElementById("email-register");

        const usernameRegisterP = document.getElementById("username-register-p");
        const pwd45 = document.getElementById("pwd-45");
        const pwdSpecialChar = document.getElementById("pwd-specialchar");
        const pwdMaj = document.getElementById("pwd-maj");
        const pwdNumber = document.getElementById("pwd-number");
        const repwdRegisterP = document.getElementById("repwd-register-p");
        const emailRegisterP = document.getElementById("email-register-p");

        // CONSTANTES & REGEX
        const maxpwd = 45;
        const regexSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
        const regexMaj = /[A-Z]+/;
        const regexNumber = /[0-9]+/;
        const regexEmail = /^((?!\.)[\w\-_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;

        // VALIDATION
        let registerValid = {
            usernameValid: false,
            pwdValid: false,
            repwdValid: false,
            emailValid: false

        };

        // LISTENERS
        usernameRegister.addEventListener("input", usernameVerify);
        pwdRegister.addEventListener("input", pwdVerify);
        repwdRegister.addEventListener("input", repwdVerify);
        emailRegister.addEventListener("input", emailVerify);

        const form = registerPage.querySelector('form');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            registerVerify();
        });

        function updateStyle(element, isValid) {
            if (!element) return;

            element.classList.remove("text-success", "text-danger");
            if (isValid) {
                element.classList.add("text-success");
            } else element.classList.add("text-danger");
        }

        function usernameVerify() {
            const username = usernameRegister.value;
            const usernameLength = username.length;

            const isValid = usernameLength > 0;
            if (!isValid) {
                usernameRegisterP.textContent = "Le nom d'utilisateur est vide!";
                updateStyle(usernameRegisterP, false);
            } else {
                usernameRegisterP.textContent = "";
                updateStyle(usernameRegisterP, true);
            }
            registerValid.usernameValid = isValid;
        }

        function pwdVerify() {
            const pwd = pwdRegister.value;
            const pwdLength = pwd.length;

            const validLength = (pwdLength > 0 && pwdLength <= maxpwd);
            const validChar = (regexSpecial.test(pwd));
            const validMaj = (regexMaj.test(pwd));
            const validNum = (regexNumber.test(pwd));

            updateStyle(pwd45, validLength);
            updateStyle(pwdSpecialChar, validChar);
            updateStyle(pwdMaj, validMaj);
            updateStyle(pwdNumber, validNum);

            if (validLength && validChar && validMaj && validNum) {
                registerValid.pwdValid = true;
            } else {
                registerValid.pwdValid = false;
            }

            repwdVerify();
        }

        function repwdVerify () {
            const repwd = repwdRegister.value;
            const validRePWD = (repwd.length > 0) && (repwd === pwdRegister.value);

            if (!validRePWD) {
                repwdRegisterP.textContent = "Les mots de passe ne correspondent pas ou sont vides.";
                updateStyle(repwdRegisterP, false);
                registerValid.repwdValid = false;
            }
            else {
                repwdRegisterP.textContent = "";
                updateStyle(repwdRegisterP, true);
                registerValid.repwdValid = true;
            }
        }

        function emailVerify() {
            const email = emailRegister.value;
            const validEmail = regexEmail.test(email);

            if (!validEmail) {
                emailRegisterP.textContent = "Le format de l'email est invalide.";
                updateStyle(emailRegisterP, false);
                registerValid.emailValid = false;
            } else {
                emailRegisterP.textContent = "";
                updateStyle(emailRegisterP, true);
                registerValid.emailValid = true;
            }
        }

        function emailVerify() {
            const email = emailRegister.value;
            const validEmail = regexEmail.test(email);

            if (!validEmail) {
                emailRegisterP.textContent = "Le format de l'email est invalide.";
                updateStyle(emailRegisterP, false);
                registerValid.emailValid = false;
            } else {
                emailRegisterP.textContent = "";
                updateStyle(emailRegisterP, true);
                registerValid.emailValid = true;
            }
        }
    }
}