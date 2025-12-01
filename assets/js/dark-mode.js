(function () {

    const root = document.documentElement;

    function toggleDarkMode() {
        const currentTheme = root.getAttribute("data-theme");
        const newTheme = currentTheme === "dark" ? "light" : "dark";
        root.setAttribute("data-theme", newTheme);
        localStorage.setItem("theme", newTheme);
        const checkbox = document.querySelector('[data-theme-toggler]');
        if (checkbox) {
            checkbox.checked = newTheme === "dark";
        }
    }

    function init() {
        const storedPreference = localStorage.getItem("theme");
        const systemPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        const theme = storedPreference || (systemPrefersDark ? "dark" : "light");
        root.setAttribute("data-theme", theme);
        const checkbox = document.querySelector('[data-theme-toggler]');
        if (checkbox) {
            checkbox.checked = theme === "dark";
        }
    }

    init();

    document.addEventListener("DOMContentLoaded", function () {
        const togglers = document.querySelectorAll("[data-theme-toggler]");
        togglers.forEach((toggler) => {
            toggler.addEventListener("click", toggleDarkMode);
        });
    });

})();