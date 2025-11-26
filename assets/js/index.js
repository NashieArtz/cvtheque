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
let username = "<?php $username; ?>";

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

