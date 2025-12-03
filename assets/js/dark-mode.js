const moonSunIcon = document.getElementsByClassName("moon-sun-icon")[0];

const themeSwitch = document.getElementById('toggle');
let darkmode = localStorage.getItem('dark');

const enableDarkmode = () => {
    document.documentElement.setAttribute('data-theme', 'dark');
    moonSunIcon.setAttribute('src', './assets/img/sun.png');
    themeSwitch.checked = true;
    localStorage.setItem('dark', 'active');
}

const disableDarkmode = () => {
    document.documentElement.setAttribute('data-theme', 'light');
    moonSunIcon.setAttribute('src', './assets/img/moon.png');
    themeSwitch.checked = false;
    localStorage.removeItem('dark');
}

if (darkmode === "active") enableDarkmode();

themeSwitch.addEventListener("click", () => {
    darkmode = localStorage.getItem('dark');
    darkmode !== "active" ? enableDarkmode() : disableDarkmode();
})
