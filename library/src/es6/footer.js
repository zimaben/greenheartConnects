//check payment;
window.addEventListener('DOMContentLoaded', (event) => {
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

//carousel functions
function normalizeSlideHeights(slides){
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
        //remove clone before loop closes to avoid list mutation
        clone.remove();
      };
      placeholder.remove();
      for(let slide of slides){
        slide.setAttribute('style', 'height:'+ maxheight + 'px;');
      };  
    }
  }
//On Load Check for Videos inside Carousels & bolt on logic to deal with them
window.addEventListener('DOMContentLoaded', (event) => {
    if(document.getElementsByClassName('carousel').length){
        let the_slides = document.getElementsByClassName("carousel-item");
        normalizeSlideHeights(the_slides);

        let iframes = document.querySelectorAll('iframe.vimeo');
        var collection=[];
      
        if(iframes){
            for(let i=0;i<iframes.length;i++){
                collection[i] = new Vimeo.Player(iframes[i]);
                collection[i].on('play', function() {              
                    pauseCarousel('homesplashCarousel')
                });
            }
        }
        //hook into Bootstrap Carousel Slide
        jQuery('#homesplashCarousel').bind('slid.bs.carousel', function (e) {
            moveThumbs( e.to );
            //pause any playing videos
            pauseVimeos();
        
        });
    }
});



const pauseCarousel = (carouselid) => {
    
    if(carouselid.indexOf('#') !== 0) carouselid = '#'+carouselid;

    jQuery(carouselid).carousel('pause');

}

const pauseVimeos = () => {

    //Note Requires Vimeo SDK if you see undefined functions
    let iframes = document.querySelectorAll('iframe.vimeo');

    if(iframes){
        for(let iframe of iframes){
            var pausethis = new Vimeo.Player(iframe);
            pausethis.pause();
        }
    }
}

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