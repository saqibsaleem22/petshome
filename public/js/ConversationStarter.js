let sign_in_submit = document.getElementById('sign-in-guest');

function startConversation(id, user) {
    if(user) {
        displayLoading(true);
        createConversation(id);
    } else {
        sign_in_submit.click();
    }


}

function createConversation(animalId) {

    let formData = new FormData();
    formData.append('animalId', animalId);
    fetch("profile", {

        method: 'POST',
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (!data) {
                displayLoading(false);
                launchAlert("danger", "Conversation", "You can't start a conversation for a pet uploaded by you!");
            } else {
                displayLoading(false);
                window.location.href = "profile?conv=" + data;
            }

        })
}


