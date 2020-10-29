new Swiper(".swiper-container", {
    direction: "horizontal",
    loop: true,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});

window.onload = function () {
    AOS.init({
        offset: 120,
    });
    // loading menu responsive
    let html = document.querySelector(".menu-list > ul").innerHTML;
    document.querySelector(".menu-list-movil").innerHTML = html;

    // open menu
    document.getElementById("btn-menu").addEventListener("click", function () {
        document.querySelector(".overlay").classList.toggle("open");
        document.querySelector(".menu-main-movil").classList.toggle("open");
    });

    document.querySelector('.overlay').addEventListener('click', function () {
        closeMenu();
    });

    document.querySelector('#btn-close').addEventListener('click', function () {
        closeMenu();
    });

    // add event to menu-resposive
    let menuItems = document.getElementsByClassName('menu-item');
    for (var i = 0; i < menuItems.length; i++) {
        menuItems[i].addEventListener('click', closeMenu, false);
    }
};

function closeMenu() {
    document.querySelector(".overlay").classList.remove("open");
    document.querySelector(".menu-main-movil").classList.remove("open");
}

let btnUp = document.getElementById("btn-up");

const rootElement = document.documentElement

window.onscroll = function () {
    let scrollTotal = rootElement.scrollHeight - rootElement.clientHeight
    if ((rootElement.scrollTop / scrollTotal) > 0.60) {
        btnUp.style.display = "block";
    } else {
        btnUp.style.display = "none";
    }
}

function gotoTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}


function closeNav() {
    document.querySelector('.nav').remove();
}
