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

const maxpwd = 45;
const regexSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
const regexMaj = /[A-Z]+/;
const regexNumber = /[0-9]+/;
const regexEmail = /^((?!\.)[\w\-_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;

let registerValid = {
    usernameValid: false,
    pwdValid: false,
    repwdValid: false,
    emailValid: false
    // MORE VALUES TO ADD

}

usernameRegister.addEventListener("input", usernameVerify);
pwdRegister.addEventListener("input", pwdVerify);
repwdRegister.addEventListener("input", repwdVerify)
registerVerify();

function updateStyle(element, isValid) {
    element.classList.remove("text-success", "text-danger");
    if (isValid) {
        element.classList.add("text-success");
    } else element.classList.add("text-danger");
}

function usernameVerify() {
    const username = usernameRegister.value;
    let bool = false;

    const usernameLength = username.length;
    const isFocused = (document.activeElement === usernameRegister)
    if (isFocused && usernameLength <= 0) {
        usernameRegisterP.textContent = "Username is empty!";
        registerValid.usernameValid = false;
    } else {
        usernameRegisterP.textContent = "";
        registerValid.usernameValid = true;
    }
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
    if (pwd45.classList.contains("text-success")
        && pwdSpecialChar.classList.contains("text-success")
        && pwdMaj.classList.contains("text-success")
        && pwdNumber.classList.contains("text-success")) {
        registerValid.pwdValid = true;
    }
}

function repwdVerify () {
    const repwd = repwdRegister.value;
    const validRePWD = (repwd === pwdRegister.value);
    if (!validRePWD) {
        repwdRegisterP.textContent = "Passwords do not match";
        registerValid.repwdValid = false;
    }
     else {
         repwdRegisterP.textContent = "";
         registerValid.repwdValid = true;
    }
    updateStyle(repwdRegisterP, validRePWD);
}

function registerVerify() {
    console.log(registerValid);
    if ( registerValid.usernameValid
    && registerValid.pwdValid
    && registerValid.repwdValid
    && registerValid.emailValid) {
        // CHECK HERE
    }
}