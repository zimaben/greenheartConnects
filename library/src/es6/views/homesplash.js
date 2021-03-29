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
