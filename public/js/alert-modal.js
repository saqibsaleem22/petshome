/************* ALERT MODAL *******************/
/*------------- Alert elements ------------------*/
let alert_header = document.getElementById('alert-modal-header');
let alert_text = document.getElementById('alert-modal-text');
let alert_pic = document.getElementById('alert-modal-pic');
let alert_open = document.getElementById('alert-modal-open');


/*------------- Functions ------------------*/
function launchAlert(type, header, content) {
    let pic_url = type == "success" ? "../public/images/success-icon.png" : "../public/images/danger-icon.png";
    alert_pic.style.background = `url('${pic_url}') no-repeat center`;
    alert_pic.style.backgroundSize = "contain";
    alert_header.innerText = header;
    alert_text.innerText = content;
    alert_open.click();
}


/************* LOADING MODAL *******************/
let loading_modal = document.getElementById('loading-modal');

function displayLoading(display) {
    if(display) {
        loading_modal.style.display = "block";
    } else {
        loading_modal.style.display = "none";
    }
}
