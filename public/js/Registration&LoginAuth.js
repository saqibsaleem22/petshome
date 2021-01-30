
let signin_form = document.getElementById('signin-form');
let CODE = "";
let EMAIL = "";
let PASSWORD = "";


/************* REGISTRATION *******************/
/*------------- Modal elements ------------------*/
let register_form = document.getElementById('register-form');
// Email input
let register_email_input = document.getElementById('register-email');
let register_email_error = document.getElementById('register-email-error');
// Password input
let register_password_input = document.getElementById('register-password');
let register_password_error = document.getElementById('register-password-error');
// Password repeat input
let register_password_input_repeat = document.getElementById('register-repeat-password');
let register_repeat_password_error = document.getElementById('register-repeat-password-error');
// Register submit
let register_submit = document.getElementById('register-submit');
// Redirect
let verification_redirect = document.getElementById('verification-redirect');
// Register List for validation
let registration_validation_elements = [register_email_input, register_password_input, register_password_input_repeat]
let registration_error_elements = [register_email_error, register_password_error, register_repeat_password_error]

/*------------- Event Listeners ------------------*/
// Email input
register_email_input.addEventListener('change', function() {
    let msg = "";
    let email_value = this.value;
    let email_regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    if (email_value.length === 0) {
        msg = ' * Email cant be blank';
    }
    else if(!email_regex.test(email_value)) {
        msg = ' * Email is incorrect!';
    }
    register_email_error.innerText = msg
})
// Password input
register_password_input.addEventListener('change', function() {
    let msg = "";
    let pass_value = this.value;
    if (pass_value.length === 0) {
        msg = ' * Password cant be blank';
    }
    else if(pass_value.length < 6) {
        msg = ' * Password should contain at least 6 characters!';
    }
    register_password_error.innerText = msg
})
// Password repeat input
register_password_input_repeat.addEventListener('change', function() {
    let msg = "";
    let rep_pass_value = this.value;
    let pass_value = register_password_input.value;
    if (rep_pass_value !== pass_value) {
        msg = ' * Both passwords must match!';
    }
    register_repeat_password_error.innerText = msg
})
// Form
register_form.addEventListener('submit', validationOnSubmit)

/*------------- Functions ------------------*/
// Form submit validation
function validationOnSubmit(e) {
    e.preventDefault();
    let email = register_email_input.value;
    let password = register_password_input.value;

    let error = false;
    for (const ele of registration_validation_elements) {
        ele.dispatchEvent(new Event('change'));
    }
    for (const ele of registration_error_elements) {

        if (ele.innerText.length > 1) {
            error = true;
        }
    }

    if (error === false) {
        registerUser(email, password, false);
    }
}

// Register user fetch method implemented
function registerUser(email, password, code) {
    let formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    formData.append('code', code);

    fetch("registration", {
        method: 'POST',
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            let error = data['exist-error'];
            let code = data['code'];
            let registered = data['registered'];
            if (registered) {
                success_redirect.click();
            } else {
                if (error) {
                    register_email_error.innerText = " * Email already exists!";
                } else {
                    if (code) {
                        CODE = code;
                        EMAIL = email;
                        PASSWORD = password;
                        verification_redirect.click();
                    } else {
                        register_email_error.innerText = " * Email is invalid. Please try again.";
                    }
                }
            }

        })  .catch( err => "hello");
}



/************* VERIFY *******************/
/*------------- Modal elements ------------------*/
// Verify code
let verify_code = document.getElementById('verify-code');
let verify_code_error = document.getElementById('verify-code-error');
// Verify submit
let verify_submit = document.getElementById('verify-submit');
// Redirect
let success_redirect = document.getElementById('success-redirect');

/*------------- Verify listeners ------------------*/
// Verify submit
verify_submit.addEventListener('click', () => {
    if (CODE == verify_code.value) {
        registerUser(EMAIL, PASSWORD, true);
        verify_code_error.innerText = '';
    } else {
        verify_code_error.innerText = '* code is invalid'
    }
})




/************* LOGIN *******************/
/*------------- Modal elements ------------------*/
let login_form = document.getElementById('login-form');
// Email input
let login_email_input = document.getElementById('login-email');
let login_email_error = document.getElementById('login-email-error');
// Password input
let login_password_input = document.getElementById('login-password');
let login_password_error = document.getElementById('login-password-error');
// Login hide button
let login_close_button = document.getElementById('login-close-button');
// Login submit
let login_button = document.getElementById('login-submit');

/*------------- Login listeners ------------------*/
login_form.addEventListener('submit', (e) => {
    e.preventDefault();
    let email_regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    let errorMsg = "";
    if (login_email_input.value.length == 0) {
        login_email_error.innerText = " * Email cant be blank";
    } else if (!email_regex.test(login_email_input.value)) {
        login_email_error.innerText = " * Email is incorrect";
    } else if (login_password_input.value.length == 0) {
        login_password_error.innerText = " * Password can't be blank";
    } else {
        login_email_error.innerText = "";
        login_password_error.innerText = "";
        login(login_email_input.value, login_password_input.value);
    }

})
/*------------- Functions ------------------*/
function login(email, password) {
    let formData = new FormData();
    formData.append("login-email", email);
    formData.append("login-password", password);
    fetch("loginCustom", {
        method: 'POST',
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if(data == true) {

                location.reload();
                /*
                let signGuest = document.getElementById('sign-in-guest');
                let signProfile = document.getElementById('sign-in-profile');
                signGuest.style.display = "None";
                signProfile.style.display = "inline-block";
                signProfile.innerText += " Go to profile";

                */
                login_close_button.click();
            } else {
                login_password_error.innerText = " * Email or password is incorrect!";
            }
        })  .catch( err => "Error has occurred!");
}


/************* FORGOT PASSWORD *******************/
/*------------- Modal elements ------------------*/
// Email input
let forgot_input = document.getElementById('forgot-email');
let forgot_error = document.getElementById('forgot-password-error');
let forgot_success = document.getElementById('forgot-password-success');
// Forgot submit
let forgot_submit = document.getElementById('forgot-btn');

/*------------- Forgot listeners ------------------*/
forgot_submit.addEventListener('click', () => {
    let forgotEmail = forgot_input.value;
    let errorOrSuccessMsg = "";
    let email_regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    if (forgotEmail.length === 0) {
        errorOrSuccessMsg = ' * Email cant be blank';
    }
    else if(!email_regex.test(forgotEmail)) {
        errorOrSuccessMsg = ' * Email is incorrect!';
    } else {
        recoverPassword(forgotEmail);
    }
    forgot_error.innerText = errorOrSuccessMsg;
})

/*------------- Functions ------------------*/
// function for password recovery email
function recoverPassword(email) {
    displayLoading(true);
    let formData = new FormData();
    formData.append('forgot-email', email);

    fetch("recover", {
        method: 'POST',
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            displayLoading(false);
            if(data == true) {
                launchAlert('success', 'Forgot Password', 'An email has been sent to your account with new password');
            } else {
                launchAlert('danger', 'Forgot Password', 'Email is not registered!');
            }
        })  .catch( err => displayLoading(false));
}



/************* UPDATE PASSWORD *******************/
/*------------- Modal elements ------------------*/
// Update password form
let update_form = document.getElementById('update-password');
// Old password input
let old_password_input = document.getElementById('password');
let old_password_error = document.getElementById('update-password-error');
// New password input
let new_password_input = document.getElementById('password2');
let new_password_error = document.getElementById('update-password2-error');


/*------------- Event Listeners ------------------*/
update_form.addEventListener('submit', function(e) {
    e.preventDefault();


    if (old_password_input.value.length == 0) {
        old_password_error.innerText = " * Password can't be blank";
        new_password_error.innerText = "";
    } else if (new_password_input.value.length == 0) {
        old_password_error.innerText = "";
        new_password_error.innerText = " * New password can't be blank";
    } else if (new_password_input.value.length < 6) {
        old_password_error.innerText = "";
        new_password_error.innerText =" * Password must contain at least 6 characters!";
    } else {
        old_password_error.innerText = "";
        new_password_error.innerText = "";
        updatePassword(old_password_input.value, new_password_input.value);
    }
})

/*------------- Functions ------------------*/
// function for password update
function updatePassword(oldPassword, newPassword) {
    displayLoading(true);
    let formData = new FormData();
    formData.append('old-password', oldPassword);
    formData.append('new-password', newPassword);

    fetch("updatePassword", {
        method: 'POST',
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if(data == true) {
                launchAlert('success', 'Password update', 'Your password has been updated successfully!');
            } else {
                launchAlert('danger', 'Password update', 'Old password is incorrect!');
            }
            displayLoading(false);
        })  .catch( err => displayLoading(false));
}








