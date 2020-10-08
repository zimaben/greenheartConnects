//set up ajax comment button
const addAjaxComment = async(e) => {
    e.preventDefault();
    e.stopPropagation();//so we don't need to dump other ajax comments just yet
    let form = document.getElementById('commentform');
    let comment = document.getElementById('comment');
    let url = form.getAttribute('action');
    
    let senddata = encodeURIComponent( 'comment' ) + '=' + encodeURIComponent(comment.value);
    senddata+= '&'+ encodeURIComponent('doing_ghc_ajax_comment') + '=' + encodeURIComponent("true"); 
    let inputs = form.getElementsByTagName('INPUT');
    for(let input of inputs){
        senddata+= '&'+ encodeURIComponent(input.name) + '=' + encodeURIComponent(input.value); 
    }
    let response = await sendit(url, senddata);
    if(response){
        console.log(response);
        let comment = document.getElementById('comment');
        let comments = response.markup;
        let comment_list = document.getElementById('comment_list');
        let comment_form = document.getElementById('respond');
        let isReply = comment_form.querySelector('#cancel-comment-reply-link');
        if(isReply.getAttribute('style') !== 'display:none;'){
            comment.value='';
            isReply.click();
            console.log(comments);
            if(comments)comment_list.innerHTML = comments;
        } else {
            comment.value='';
            console.log(comments);
            if(comments) comment_list.innerHTML = comments;
        }
    }
}