let contact_form = document.getElementById('contact-us-form');
contact_form.addEventListener('submit', function(e) {
    e.preventDefault();
    displayLoading(true)
    let formData = new FormData();
    formData.append('name', e.target.Name.value);
    formData.append('email', e.target.Email.value);
    formData.append('phone', e.target.Phone.value);
    formData.append('message', e.target.Message.value);
    contact_form.reset();
    fetch("contactUs", {
        method: 'POST',
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            displayLoading(false);
            if(data) {
                launchAlert('success', 'Contact Us', 'Your message has been sent successfully');
            } else {
                launchAlert('danger', 'Contact Us', 'There was a problem sending your message');
            }

        })  .catch( err => displayLoading(false));
})