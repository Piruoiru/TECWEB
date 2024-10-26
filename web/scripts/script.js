function layoutHandler(){
    var styleLink = document.getElementById("pagestyle");
    if(window.innerWidth < 900){
        styleLink.setAttribute("href", "/style/resized.css");
    }else {
        styleLink.setAttribute("href", "/style/style.css");
    }
}
window.onresize = layoutHandler;
layoutHandler();

document.getElementById('hamburger-menu').addEventListener('click', function() {
    const navMenu = document.getElementById('nav-menu');
    const hamburgerMenu = document.getElementById('hamburger-menu');

    if (navMenu.style.right === '0px') {
        navMenu.style.right = '-200px'; // Chiude il menu
        hamburgerMenu.style.display = 'flex'; // Mostra l'hamburger
    } else {
        navMenu.style.right = '0'; // Mostra il menu
        hamburgerMenu.style.display = 'none'; // Nasconde l'hamburger
    }
});

// Nasconde il menu quando si clicca fuori da esso
document.addEventListener('click', function(event) {
    const navMenu = document.getElementById('nav-menu');
    const hamburgerMenu = document.getElementById('hamburger-menu');
    
    if (!navMenu.contains(event.target) && !hamburgerMenu.contains(event.target)) {
        navMenu.style.right = '-200px'; // Nasconde il menu
        hamburgerMenu.style.display = 'flex'; // Mostra l'hamburger
    }
});
