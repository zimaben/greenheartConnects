//hook into Bootstrap Carousel Slide

jQuery('#homesplashCarousel').bind('slid.bs.carousel', function (e) {
    moveThumbs( e.to );

}); 
//separate ready function for YouTube API
window.YT.ready(function(){
    bind_youtubeclicks();
});
function bind_youtubeclicks(){

    let youtubes = document.getElementsByClassName("youtube_api");
    for(let youtube of youtubes){
        var player = new YT.Player(youtube, {
            events: {
  //            'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
        let carouselbuttons = document.getElementById('homesplashCarousel').querySelectorAll('a[data-slide]');
        for(let button of carouselbuttons){
            button.addEventListener('click', ()=>{
                var ytiframes = document.querySelectorAll('iframe.youtube_api');
                Array.prototype.forEach.call(ytiframes, iframe => { 
                    iframe.contentWindow.postMessage(JSON.stringify({ event: 'command', 
                    func: 'pauseVideo' }), '*');
                });
             
                 
            });
        }

    }
}

function onPlayerStateChange(event) {

    if (event.data == YT.PlayerState.PLAYING) {

       event.target.playing = true;
       jQuery('#homesplashCarousel').carousel('pause');
      }

    else if(event.data == YT.PlayerState.PAUSED){

          event.target.playing = false;
          jQuery('#homesplashCarousel').carousel('cycle');
    }
    else if(event.data == YT.PlayerState.ENDED){

          event.target.playing = false;
          jQuery('#homesplashCarousel').carousel('cycle');
    }
}

const viewportWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0)
const moveThumbs = (toIndex) => {

    let rightthumbs = document.querySelector('.thumbnails.right');
    if( rightthumbs && rightthumbs!== null) rightthumbs = rightthumbs.children;

    let leftthumbs = document.querySelector('.thumbnails.left');
    if( leftthumbs && leftthumbs!== null) leftthumbs = leftthumbs.children;


    if(rightthumbs && leftthumbs && (rightthumbs.length === leftthumbs.length) ){
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
const check_slides = () => {
    let slides = document.getElementsByClassName('carousel-item');
    if(slides) normalizeSlideHeights(slides);
}
/*
        if(slides){
            for(let slide of slides){
                slide.removeAttribute('style');
                let iframe = slide.querySelector('IFRAME');
                if(iframe) fit_iFrame_mobile( iframe )
              };  
        }
        
        return false;

*/
const fit_iFrame_mobile = ( iframe ) => {
    let h = iframe.height;
    let w = iframe.width;
    let multiplier = false;
    if(w > h){
        multiplier = h/w;
    }
    iframe.width  = iframe.contentWindow.document.body.scrollWidth;
    if(multiplier){
        iframe.height = iframe.height * multiplier;
    }
}
const set_iFrame = (source, dest) => {
    fit_iFrame_mobile(source);
    dest.height = source.height;
    dest.width = source.width;
}

const normalizeSlideHeights = (slides) => {
    let mobile_layout = false;
    if(!viewportWidth || viewportWidth < 768){ 
        mobile_layout = true;
    }

    let parent=slides[0].parentElement;
    let placeholder = document.createElement('div');
    placeholder.classList = parent.classList;
    placeholder.setAttribute('style', 'position:absolute;left:-10000px;visibility:hidden;');
    if(parent && slides.length){//will work on all but the body element
        var maxheight = 0;
        parent.parentElement.insertBefore(placeholder, parent);//to get correct CSS hierarchy
        for(let slide of slides){
            let clone = slide.cloneNode(true);
            clone.classList.add('active'); //otherwise in Bootstrap it's display:none
            placeholder.appendChild(clone);
            if(clone.clientHeight > maxheight) maxheight = clone.clientHeight
            if(mobile_layout){
                let iframe = clone.querySelector('IFRAME');
                let dest_iframe = slide.querySelector('IFRAME');
                set_iFrame(iframe, dest_iframe);
            }
            //remove clone before loop closes to avoid list mutation
            clone.remove();
        };
        placeholder.remove();
        if(!mobile_layout){
            for(let slide of slides){
                slide.setAttribute('style', 'height:'+ maxheight + 'px;');
            };  
        }
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