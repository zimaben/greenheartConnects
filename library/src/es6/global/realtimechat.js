//set up game loop
let lastRenderTime = 0; 
let lastCommentCount = 0;
var chat_speed = 12; //refreshes per minute - 12 is every 5 seconds

async function main(currentTime){
    const cont = currentTime;
    if(cont){
        window.requestAnimationFrame(main); 
        const sinceLastRender = (currentTime - lastRenderTime) / 1000;
        if(sinceLastRender < 60 / chat_speed) return;
        
        // we only get to this point in the code every 1 second divided by chat_speed
        lastRenderTime = currentTime;
        console.log('sending nudge');
        let comment_list = document.getElementById('comment_list');
        let comment_num = comment_list.dataset.comments;
        let postid = comment_list.dataset.postid;
        //location
        const location = ajaxurl + '?action=get_new_comments';
        //parameters
        let senddata = encodeURIComponent( 'post_id' ) + '=' + encodeURIComponent( postid );
        senddata += '&'+ encodeURIComponent( 'comment_count' ) + '=' + encodeURIComponent( comment_num );
        let response = await sendit(location, senddata);
        console.log('response comment num: ' + response.commentCount );
        if( response.status == 200){
            //only update if we need to
            if( response.commentCount >= comment_num 
                && response.markup ){
                comment_list.dataset.comments = response.commentCount;
                comment_list.innerHTML = response.markup;
            } else if(response.killRTC == "true"){
                comment_list.innerHTML = '<div>'+response.markup+'<br><br></div>';
                chat_speed = false;
            }
        } else {
            console.log(response.message);
        }
    }

}
window.requestAnimationFrame(main);
