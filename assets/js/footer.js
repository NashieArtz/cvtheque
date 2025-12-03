function applyRoleStyles() {
    const nav = document.getElementsByClassName("navbar")[0];
    const footer = document.getElementsByClassName("footer")[0];

    if (nav.classList.contains("navbar-guest")) {
        updateFooterStyle("footer-guest");
    } else if (nav.classList.contains("navbar-student")) {
        updateFooterStyle("footer-student");
    } else if (nav.classList.contains("navbar-admin")) {
        updateFooterStyle("footer-admin");
    }

    function updateFooterStyle(roleClass) {
        footer.classList.remove("footer-guest", "footer-student", "footer-admin");
        footer.classList.add(roleClass);
    }
}

document.addEventListener('DOMContentLoaded', applyRoleStyles);