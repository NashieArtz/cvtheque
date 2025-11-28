
const usernameInput = document.getElementById("username-register");
const usernameTakenP = document.getElementById("username-taken-p");
usernameInput.addEventListener("input", showUsernameTaken);
function showUsernameTaken() {
    let usernameValue = usernameInput.value;
    const xhttp = new XMLHttpRequest();
    xhttp.open("GET", "./api/check-username.php?username=" + usernameValue);
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            let data = JSON.parse(this.responseText);
            if (data[0] === true) {
                usernameTakenP.textContent = "Nom d'utilisateur déjà pris";
            }
        }
    };
    xhttp.send()
}



