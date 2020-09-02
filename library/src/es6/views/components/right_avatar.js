//toggle avatar menu function
const expandMenu = (e) => {
    if( window.innerWidth < 993 ){
        let navcontainer = document.getElementById('mobile-menu-container');
    	navcontainer.classList.add('active');
    } else {
        let avatar_div = e.target.closest('.avatar-right'); //up the dom to avatar    
        let avatar_menu = avatar_div.querySelector('.avatar-right-dropdown');
        avatar_menu.classList.toggle('active');
    } 

    e.stopPropagation(); //so parent document doesn't notice the click and fire dismiss logic
}

const closeMobileMenu = (e) => { //do onclick from any element inside the mobile menu
    let container = findcontainer('avatar-right-dropdown', e.target);
    if(container) container.classList.remove('active');
} 
function findcontainer(id, el){
    if(el.id !== id && el.tagName !== 'BODY'){
        while(el.id !== id && el.tagName !== 'BODY'){
            el = el.parentElement;
        }
    }
    return (el.tagName === 'BODY' ) ? false : el;
}