
const form = document.querySelector("form");

form.addEventListener("submit", function(event) {

    const password = document.querySelector(
        'input[name="password"]'
    ).value;

    const konfirmasi = document.querySelector(
        'input[name="konfirmasi_password"]'
    ).value;

    if (password !== konfirmasi) {
        event.preventDefault();
        alert("Password dan konfirmasi password tidak sama!");
    }

});
