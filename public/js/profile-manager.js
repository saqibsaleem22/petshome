let profile_tab = document.getElementById('profile-tab');
let msg_tab = document.getElementById('msg-tab');
let upload_tab = document.getElementById('upload-tab');
let msg_content_box = document.getElementById('msg-content-box');
let profile_content_box = document.getElementById('profile-content-box');
let upload_content_box = document.getElementById('upload-content-box');

profile_tab.addEventListener('click', function () {
    msg_tab.classList.remove('background-dark-panel');
    upload_tab.classList.remove('background-dark-panel');
    this.classList.add('background-dark-panel');
    toggleTabContent('profile');
});

msg_tab.addEventListener('click', function() {
    profile_tab.classList.remove('background-dark-panel');
    upload_tab.classList.remove('background-dark-panel');
    this.classList.add('background-dark-panel');
    toggleTabContent('message');
})

upload_tab.addEventListener('click', function() {
    msg_tab.classList.remove('background-dark-panel');
    profile_tab.classList.remove('background-dark-panel');
    this.classList.add('background-dark-panel');
    toggleTabContent('upload');
})

function toggleTabContent(tab) {
    msg_content_box.style.display = "none";
    upload_content_box.style.display = "none";
    profile_content_box.style.display = "none";

    if (tab == "profile") {
        profile_content_box.style.display = "block";
    }
    if (tab == "message") {
        msg_content_box.style.display = "block";
    }
    if (tab == "upload") {
        upload_content_box.style.display = "block";
    }
}

function changeToAdopted(id, status) {

    if (status == "available") {
        let formData = new FormData();
        formData.append('animal-id', id);
        displayLoading(true);
        fetch("status", {
            method: 'POST',
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                displayLoading(false);
                launchAlert('success', 'Pet status update', 'Your pet has been successfully to adopted. Please refresh page to see changes.')
            })  .catch( err => displayLoading(false));
    }
}