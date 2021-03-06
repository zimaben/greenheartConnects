//check payment; 
window.addEventListener( 'resize', (event) => {
    if( document.getElementById('homesplashCarousel') ) check_slides(); 
});
window.addEventListener('DOMContentLoaded', (event) => { 
    let actions = document.querySelectorAll('[data-action]');
    if(actions.length){
        for(let action of actions){
            //for now we just have the one
            if(action.dataset.action == "final_countdown") final_countdown();

            if(action.dataset.action == "final_countdown_simple") final_countdown_simple(action);
        } 
    }
    if( document.getElementById('homesplashCarousel') ) check_slides(); 
    /*
    if( document.getElementById('home-hero') && document.getElementById('home-hero').classList.contains('loggedout') ){
        let data_target = document.getElementById( 'pleaselogin');
        let body = document.getElementsByTagName('body')[0];
        if( ! body.classList.contains('modal-open') ){
            //can't call a modal from inside a modal
            //clone target element 
            let data_clone = data_target.cloneNode(true)
            body.classList.add('modal-open');
            NeonModalFactory.getInstance(data_clone, data_target );
        }
    }
    else if( document.getElementById('home-hero') && document.getElementById('home-hero').classList.contains('payment-needed') ){
        let data_target = document.getElementById( 'payment');
        let body = document.getElementsByTagName('body')[0];
        if( ! body.classList.contains('modal-open') ){
            //can't call a modal from inside a modal
            //clone target element 
            let data_clone = data_target.cloneNode(true)
            body.classList.add('modal-open');
            NeonModalFactory.getInstance(data_clone, data_target );
        }
    }
    */
    document.addEventListener("click", function(event){
        //if click isn't doing something else       
        if(event.target.onclick !== "function"){
            //gather dismissable elements
            const clickables = document.querySelectorAll('[data-clickable="dismiss"]');
            for(let item of clickables){
                //inactivate all dismissable
                if ( item !== event.target && item.classList.contains('active')){
                     item.classList.remove('active');
                 }
            }  
        }
    
    });
});
