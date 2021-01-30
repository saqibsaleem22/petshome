let conversationBox = document.getElementById('conversation-list');
let conversationDetailBox = document.getElementById('conv-detail-box');
let clickedId;
let conversationType = "request";

function getConversations(type=conversationType) {
    let formData = new FormData();
    formData.append('type', conversationType);

    fetch("messages", {
        method: 'POST',
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            conversationBox.innerHTML = "";

                for (let d of data) {
                    let readStatus = d.unread == true ? "fa fa-envelope":"";
                    let email = type == "request" ? d.animalPlacerEmail : d.animalRequesterEmail;
                    conversationBox.innerHTML += `
                <li id="conv-${d.convId}" class="contact">
<div class="wrap">
<span class="no-status"><i class="${readStatus}" aria-hidden="true"></i></span>
<img src="assets/${d.animalPhoto}" alt="" />
<div class="meta">
<p class="name">${d.animalName}</p>
<p class="preview">${email}</p>
</div>
</div>
</li>`;
                }
                for(let d of data) {
                    document.getElementById(`conv-${d.convId}`).addEventListener('click',function(){
                        document.querySelectorAll('#conversation-list .active').forEach(ele => ele.classList.remove('active'))
                        this.classList.add('active');
                        updateMessageStatus(d.convId, type);
                        loadMessages(d, type);
                    });
                }


            if(clickedId) {
                document.getElementById(`conv-${clickedId}`).click();
            }

    })  .catch( err => "hello");
}



function loadMessages(conversation, type) {

    let messagesContent = "";
    clickedId = conversation.convId;
    for (let msg of conversation.convMessages) {
        let className = msg.type === type ? "replies" : "sent";
        let attachment = msg.attach !== "" ? msg.attach:"";
        let displayType = msg.attach !== "" ? "block": "none";
        messagesContent += `<li class="${className}">
\t\t\t\t\t<p><span>${msg.text}</span><a target="_blank" href="../public/assets/${attachment}" style="display: ${displayType}; width: 150px; height: 150px; background: url('../public/assets/${attachment}') no-repeat; background-size: cover;"></a>
\t\t\t\t</li></p>
`;
    }
        conversationDetailBox.innerHTML = `<div class="contact-profile">
\t\t\t<img src="assets/${conversation.animalPhoto}" alt="" />
\t\t\t<p>${conversation.animalName}</p>
\t\t\t<div class="social-media">
\t\t\t\t<i style="visibility: hidden" class="fa fa-facebook" aria-hidden="true"></i>
\t\t\t\t<i style="visibility: hidden" class="fa fa-twitter" aria-hidden="true"></i>
\t\t\t\t<i style="visibility: hidden;" class="fa fa-instagram" aria-hidden="true"></i>
\t\t\t</div>
\t\t</div>
\t\t<div id="msg-box" class="messages">
\t\t\t<ul>${messagesContent}
\t\t\t</ul>
\t\t</div>
\t\t<div class="message-input">
<form id="msg-form" method="post">
\t\t\t<div class="wrap">
\t\t\t\t<input type="text" name="msg_text" placeholder="Write your message..." />
<input name="conv_id" id="conv_id" type="text" value="${conversation.convId}" hidden>
<input name="msg_type" id="msg_type" type="text" hidden value="${type}">
<label for="msg_attach"><i class="fa fa-paperclip attachment" aria-hidden="true"></i></label>
\t\t\t\t<input id="msg_attach" style="display: none" name="msg_attach" type="file">
\t\t\t\t<button class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
\t\t\t</div>
</form>
\t\t</div>`;
document.getElementById('msg-form').addEventListener('submit', sendMessage);
}

function sendMessage(e) {
    e.preventDefault();
    let msgText = e.target.msg_text.value;
    let msgAttach = e.target.msg_attach;
    let convId = e.target.conv_id.value;
    let msgType = e.target.msg_type.value;
    // Sends message if any of the input has values
    if (msgText.length > 0 || msgAttach.files.length > 0) {
        if (msgAttach.files.length > 0) {
            msgAttach = msgAttach.files[0];
        } else {
            msgAttach = "";
        }
        let formData = new FormData();
        formData.append('msg-text', msgText);
        formData.append('msg-attach', msgAttach);
        formData.append('msg-type', msgType);
        formData.append('conv-Id', convId);

        fetch("updateConversationMessages", {
            method: 'POST',
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => getConversations(conversationType))  .catch( err => "hello");

    }
}



// toggle between request messages and adoption messages
let toggle_request = document.getElementById('conv-toggle-1');
let toggle_adopt = document.getElementById('conv-toggle-2');


toggle_request.addEventListener('click', function() {
    this.classList.add('background-dark');
    toggle_adopt.classList.remove('background-dark');
    conversationType = "request";
    getConversations(conversationType);
})

toggle_adopt.addEventListener('click', function() {
    this.classList.add('background-dark');
    toggle_request.classList.remove('background-dark');
    conversationType = "response";
    getConversations(conversationType);
})


function updateMessageStatus(conversationId, type) {
    let formData = new FormData();
    formData.append('conv-id', conversationId);
    formData.append('conv-type', type);
    fetch("updateMessageStatus", {
        method: 'POST',
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => console.log(data))  .catch( err => "hello");
}

/*
let interval = setInterval(() => {

    getConversations(conversationType);
}, 10000);*/
