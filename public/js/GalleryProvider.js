let gallery = document.getElementById('gallery-div');
let search_btn = document.getElementById('searchBtn');
let search_name = document.getElementById('table_filter');
let load_more_btn = document.getElementById('load-more-btn');
let animals = [];
let filteredAnimals = [];
let limit = 9;

function loadAllAnimals() {
    fetch("galleryload", {
        method: 'GET',
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
        .then(res => res.json())
        .then(data => {
            animals = data;
            filteredAnimals = data;
            loadOnPage(0);
        })  .catch( err => "hello");
}

function loadOnPage(start) {

    for(let i=start; i < (start + limit) && i < filteredAnimals.length; i++) {
        let animal = filteredAnimals[i];
        let animalView = `<div class="col-sm-4 col-xs-6 w3gallery-grids">
\t\t\t\t<a href="details?id=${animal.id}" class="imghvr-hinge-right figure">
\t\t\t\t\t<img src="../public/assets/${animal.photo}" alt="" title="Pets Care Image"/>
\t\t\t\t\t<div class="agile-figcaption">
\t\t\t\t\t\t<h4>Pets Care</h4>
\t\t\t\t\t\t<h6>Pet's Name</h6>
\t\t\t\t\t\t<p>${animal.name}</p>
\t\t\t\t\t\t<h6>Pet's Category</h6>
\t\t\t\t\t\t<p>${animal.type}</p>
\t\t\t\t\t\t<p id="adopt-status">${animal.status}</p>
\t\t\t\t\t</div>
\t\t\t\t</a>
\t\t\t</div>`;
        gallery.innerHTML += animalView;
    }
}


loadAllAnimals();

search_btn.addEventListener('click', searchByFilter);

function searchByFilter() {
    filteredAnimals = animals;
    let current_value = document.querySelector('input[name="radios"]:checked').value;
    let search_value = search_name.value;
    if (current_value == "all") {
        filteredAnimals = animals;
    } else {
        filteredAnimals = filteredAnimals.filter(ani => ani.type == current_value);
    }
    if (search_value != "") {
        filteredAnimals = filteredAnimals.filter(ani => ani.name.toLowerCase().includes(search_value.toLowerCase()));
    }
    gallery.innerHTML = "";
    loadOnPage(0);
    load_more_btn.style.display = "block";
}

load_more_btn.addEventListener('click', function () {
    let totalLoaded = document.querySelectorAll('.w3gallery-grids').length;
    if(totalLoaded < filteredAnimals.length) {
        loadOnPage(totalLoaded);
    } else {
        this.style.display = "None";
    }
})


