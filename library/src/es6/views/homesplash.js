//hook into Bootstrap Carousel Slide

jQuery('#homesplashCarousel').bind('slid.bs.carousel', function (e) {
    moveThumbs( e.to );
});

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
const normalizeSlideHeights = (slides) => {

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