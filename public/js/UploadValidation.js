let sign_in_submit = document.getElementById('sign-in-guest');
function checkForm(logged) {
    if(logged) {
        return true;
    } else {
        sign_in_submit.click();
        return false;
    }
}