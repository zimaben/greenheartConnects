//check payment;
window.onload=function(){
    if( document.getElementById('home-hero').classList.contains('loggedout') ){
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
    else if( document.getElementById('home-hero').classList.contains('payment-needed') ){
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
}
