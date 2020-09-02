//check payment;
window.addEventListener('DOMContentLoaded', (event) => {
    if( document.getElementById('home-hero') && document.getElementById('home-hero').classList.contains('loggedout') ){
        let data_target = document.getElementById( 'pleaselogin');
        window.location = 'https://greenheartconnects.org/login/?action=login';
        //let body = document.getElementsByTagName('body')[0];
        ///if( ! body.classList.contains('modal-open') ){
            //can't call a modal from inside a modal
            //clone target element 
         /*   let data_clone = data_target.cloneNode(true)
            body.classList.add('modal-open');
            NeonModalFactory.getInstance(data_clone, data_target );
        }
        */
    }
    
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
