

const do_video_modal = (e) => {
    let link = e.target.dataset.link;
    let ytid = link.split( '/' ).pop();
    let markup = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' + ytid +'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    let body = document.getElementsByTagName('BODY')[0];
    let container = document.createElement('div');
    container.innerHTML = markup;
    body.classList.add('modal-open');
    NeonModalFactory.getInstance(container, e.target );
}
