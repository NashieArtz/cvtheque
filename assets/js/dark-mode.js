let darkmode = localStorage.getItem('dark');
const themeSwitch = document.getElementById('theme-switch');
const html = document.getElementsByTagName("html");

const enableDarkmode = () => {
    html.body.classList.add('dark');
    localStorage.setItem('dark', 'active');
}

const disableDarkmode = () => {
    html.body.classList.remove('dark');
    localStorage.setItem('dark', null);
}

if (darkmode === "active") enableDarkmode();

themeSwitch.addEventListener("click", () => {
    darkmode = localStorage.getItem('dark');
    darkmode !== "active" ? enableDarkmode() : disableDarkmode();
})
