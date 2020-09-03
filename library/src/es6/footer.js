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
    doCountdowns();
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
function doCountdowns(){
    console.log('doing countdowns');
    let countdowns = document.querySelectorAll('[data-seconds]');
    for( let countdown of countdowns ){
        console.log(countdown);
        let seconds = countdown.dataset.seconds;
        let cd = new theCountdown(seconds,'seconds',0,countdown);
    }
} 

function theCountdown(input = '', format='seconds', offset = 0, target){
    var x = setInterval(function() {
        if(input > 1){
            let diff = input;
            let days = Math.floor( input / 86400 );
            diff = diff - (days * 86400);
            let hours = Math.floor(diff / 3600 );
            diff = diff - (hours * 3600);
            let minutes = Math.floor(diff / 60 );
            let seconds = diff - (minutes * 60);
            let string = (days) ? days + ' days, ' + hours + ':'+ minutes+':'+seconds : hours + ':'+ minutes+':'+seconds;
            target.innerHTML = string;
            input--;
        } else {
            clearInterval(x);
            target.innerHTML = 'now';
        }

    },1000);
}