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

usernameRegister.addEventListener("input", usernameVerify);
pwdRegister.addEventListener("input", pwdVerify);

function updateStyle(element, isValid) {
    element.classList.remove("text-success", "text-danger");
    if (isValid) {
        element.classList.add("text-success");
    } else element.classList.add("text-danger");
}

function usernameVerify() {
    const username = usernameRegister.value;

    const usernameLength = username.length;
    const isFocused = (document.activeElement === usernameRegister)
    if (isFocused && usernameLength <= 0) {
        usernameRegisterP.textContent = "Username is empty!";
    } else {
        usernameRegisterP.textContent = "";
    }
}

function pwdVerify(){
    const pwd = pwdRegister.value;
    const pwdLength = pwd.length;

    const validLength = (pwdLength > 0 && pwdLength <= maxpwd);
    const validChar = (pwd === regexSpecial);
    const validMaj = (pwd === regexMaj);
    const validNum = (pwd === regexNumber);
    updateStyle(pwd45, validLength);
    updateStyle(pwdSpecialChar, validChar);
    updateStyle(pwdMaj, validMaj);
    updateStyle(pwdNumber,validNum);
    console.log(validNum);

}


function registerVerify() {


    if ($username === '') {
        $hasError = true;
    }
    if ($userExists) {
        echo('user empty');
        $hasError = true;
    }
    if ($pwd === '') {
        echo('user empty');
        $hasError = true;
    }
    if (strlen($pwd) > $maxpwd || !preg_match($specialpwd, $pwd)) {
        echo('user empty');
        $hasError = true;
    }
    if ($repwd === '' || $pwd !== $repwd) {
        echo('user empty');
        $hasError = true;
    }
    if ($email === '') {
        echo('user empty');
        $hasError = true;
    }
    if (!preg_match($regexemail, $email)) {
        echo('user empty');
        $hasError = true;
    }
    if ($hasError) {
        header("Location: index.php?page=register");
    }
}

