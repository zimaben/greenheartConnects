//check payment; 
window.addEventListener('DOMContentLoaded', (event) => { 
    let actions = document.querySelectorAll('[data-action]');
    if(actions.length){
        for(let action of actions){
            //for now we just have the one
            if(action.dataset.action == "final_countdown") final_countdown();

            if(action.dataset.action == "final_countdown_simple") final_countdown_simple(action);
        } 
    }

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

//hook into Bootstrap Carousel Slide

jQuery('#homesplashCarousel').bind('slid.bs.carousel', function (e) {
    moveThumbs( e.to );
});

const moveThumbs = (toIndex) => {

    let rightthumbs = document.querySelector('.thumbnails.right').children;
    let leftthumbs = document.querySelector('.thumbnails.left').children;

    if(rightthumbs.length && rightthumbs.length === leftthumbs.length){
        let ridx = 0;
        let lidx = 0;
        resetThumbs();
        if(toIndex === rightthumbs.length - 1){
            //left previous right next
            ridx = 0;
            lidx = toIndex - 1;
        } else if(toIndex === 0){
            ridx = toIndex + 1;
            lidx = rightthumbs.length - 1;
        } else {
            ridx = toIndex +1;
            lidx = toIndex -1;
        }
        rightthumbs[ridx].classList.add('active');
        leftthumbs[lidx].classList.add('active');
    }
}
const resetThumbs = () => {
    let rightthumbs = document.querySelector('.thumbnails.right').children;
    let leftthumbs = document.querySelector('.thumbnails.left').children;
    for(let item of rightthumbs){
        item.classList.remove('active');
    }
    for(let item of leftthumbs){
        item.classList.remove('active');
    }
}
const goToHomeSlide = (e) =>{
    e.preventDefault();
    //catch the event target link without dupe events
    let link = (e.target.tagName === 'A') ? e.target : e.target.closest("A");
    let targetslide = parseInt(link.dataset.slide);
    let len = document.getElementsByClassName('carousel-item').length - 1;

    
    let previous = (targetslide === 0 ) ? len : targetslide - 1;
    let next = (targetslide === len ) ? 0 : targetslide + 1;

    let rightthumbs = document.querySelector('.thumbnails.left');
    let leftthumbs = document.querySelector('.thumbnails.right');
    let isright = rightthumbs.querySelectorAll('.homeslide_thumb')[previous];
    let isleft = leftthumbs.querySelectorAll('.homeslide_thumb')[next];
    if(isleft && isright){
        jQuery('#homesplashCarousel').carousel(targetslide);
        resetThumbs();
        isright.classList.add('active');
        isleft.classList.add('active');
    }
};
function final_countdown(){
    
    let seconds = parseInt( document.getElementById('timewrap').dataset.seconds );
    let days_element = document.getElementById('hero_closest_days');
    let hours_element = document.getElementById('hero_closest_hours');
    let mins_element = document.getElementById('hero_closest_mins');
    let seconds_element = document.getElementById('hero_closest_seconds');
    final_countdown_loop( seconds, 
                          days_element,
                          hours_element,
                          mins_element,
                          seconds_element 
                        );                       
}

function final_countdown_simple( element ){
    if(element.dataset.seconds){
        let totalseconds = parseInt(element.dataset.seconds);
        final_countdown_loop_simple( totalseconds );
    }
}
async function final_countdown_loop_simple( seconds ){
    console.log('for loop about to be called');
    for(let s=seconds; s < 300; s--){
        /* @TODO - this loop isn't running */
        await sleepCN(1000);
        let time_array = final_countdown_combinator( s );
        let days = time_array[0];
        let hours = time_array[1];
        let minutes = time_array[2];
        let secs = time_array[3];
        if(days){
            document.getElementById('hero_closest_days').innerHTML = days + 'days';
        }

        let stamp = hours + ':';
        stamp+= minutes + ':';
        stamp+= secs;
        document.getElementById('time2stream').innerHTML = stamp;
        
    } 
}

async function final_countdown_loop( seconds, de,he,mn,sx ){
    for (let s = seconds; s < 300; s--){
        await sleep(1000);
        let time_array = final_countdown_combinator( s );
        de.innerHTML = time_array[0];
        he.innerHTML = time_array[1];
        mn.innerHTML = time_array[2];
        sx.innerHTML = time_array[3];
    }
}

function final_countdown_combinator( seconds ){//returns array of days, hours mins, seconds from seconds
    let step = secs2days_wremainder( seconds );
    let days = step[0];
    seconds = step[1];//remaining seconds after days 
    step = secs2hours_wremainder( seconds );
    let hours = step[0];
    seconds = step[1];
    step = secs2mins_wremainder( seconds );
    let mins = step[0];
    seconds = step[1];
    return [days, hours, mins, seconds];

}
function secs2mins_wremainder ( seconds ){
    //takes a chunk of seconds and returns an array of hours and remaining seconds
    let mins = 0;
    let secs = 0;
    while( seconds > 60 ){ //60 in a minute
        mins++;
        seconds = seconds - 60;
    }
    secs = seconds; //remaining seconds after looping off days
    return [mins, secs];
}
function secs2hours_wremainder ( seconds ){
    //takes a chunk of seconds and returns an array of hours and remaining seconds
    let hours = 0;
    let secs = 0;
    while( seconds > 3600 ){ //60 * 60 in an hour
        hours++;
        seconds = seconds - 3600;
    }
    secs = seconds; //remaining seconds after looping off days
    return [hours, secs];
}
function secs2days_wremainder ( seconds ){
    //takes a chunk of seconds and returns an array of days and remaining seconds
    let days = 0;
    let secs = 0;
    while( seconds > 86400 ){ //24 * 60 * 60
        days++;
        seconds = seconds - 86400;
    }
    secs = seconds; //remaining seconds after looping off days
    console.log('days: ' + days + 'secs: ' + secs);
    return [days, secs];
}
function sleepCN(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}