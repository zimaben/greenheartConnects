const NeonModalFactory = (function(){
    function NeonModalClass(content_element, referring_element) {
        
        let frag = document.createDocumentFragment;
        let container = document.createElement('div');
        let backbutton = document.createElement('span');
        let content = document.createElement('div');
        //let svg_blob='<svg id="neon_modal_balls" data-name="neon_modal_balls" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 222.9 397.98"><style>.neon_modal_colorfill{fill:#8ac53f}</style><title>NeonModal</title><path class="neon_modal_colorfill" d="M118,119A119,119,0,0,0,.94,0L.1,238A119,119,0,0,0,118,119Z" transform="translate(-0.1 -0.02)" /><circle class="neon_modal_colorfill" cx="182.9" cy="167.98" r="40" /><circle class="neon_modal_colorfill" cx="130.9" cy="316.98" r="81" /></svg>';
        let body = document.getElementsByTagName('body')[0];
        //let yposition = window.scrollY + referring_element.getBoundingClientRect().top;
        let yposition = window.scrollY 
        // add classes 
        container.classList.add('neon-modal');
        backbutton.classList.add('close');
        content.classList.add('neon-modal-content');
        console.log(referring_element); 
        if(referring_element.id == 'payment'){
            content.classList.add('payment');
        }
        if(referring_element.hasAttribute('data-modal-size')){
            content.classList.add( referring_element.dataset.modalSize );
        }
        //add background image
        //content.innerHTML = svg_blob;
        //add content
        if(!referring_element.id == 'payment'){ // no close option for payment
            content.appendChild(backbutton);
        } 
        content.appendChild(content_element);
        container.appendChild(content);
        //body.classList.add('modal-open');
        body.appendChild(container);
        //lock the scroll
        body.style.position = 'fixed';
        body.style.top = window.scrollY+'px';
        //bank the scroll
        backbutton.setAttribute('data-scrollback', parseInt( yposition ));
        prep_s3_video(content);
        prep_vimeo(content);
        //close modal event listener
        backbutton.addEventListener('click', function(e){
            e.preventDefault();
            var modal = document.getElementsByClassName('neon-modal')[0];
            var resp = modal.querySelector('#neonid-signup-response');
            if(resp !== null) resp.innerHTML = '';
            body.classList.remove('modal-open');
            body.removeChild(container);
            NeonModalFactory.instance = null;
        })
    }
    var instance;
    return {
        getInstance: function(htmlstring, referrer){
            if (this.instance == null) {
                instance = new NeonModalClass(htmlstring, referrer);
                // Hide the constructor so the returned object can't be new'd...
                instance.constructor = null;
            } else {
                if( NEON_THEME_debug === true ){
                    console.log( 'Can\'t call a modal from inside a modal.');
                }
            }
            return instance;
        }
    };
    function prep_s3_video(content){
        //if video add autoplay
        let isvideo = content.getElementsByTagName('video');    
        if(isvideo.length ){
            let thevideo = isvideo[0];
            if(typeof thevideo !== 'undefined'){
                thevideo.autoplay = true;
                thevideo.play();
            }
        }

    }
    function prep_vimeo(content){
        let isiframe = content.getElementsByTagName('iframe');
        if(isiframe.length){
            let theframe = isiframe[0];
            if(typeof theframe !== 'undefined'){
                console.log( content.getBoundingClientRect().width );
                let width = Math.round( content.getBoundingClientRect().width * .8 ); // 9:16 aspect for height
                let height = Math.round( 9 * ( width / 16 ));
                theframe.height = height;
                theframe.width = width;
            }
        }
    }
})();


//toggle avatar menu function
const expandMenu = (e) => {
    if( window.innerWidth < 993 ){
        let navcontainer = document.getElementById('mobile-menu-container');
    	navcontainer.classList.add('active');
    } else {
        let avatar_div = e.target.closest('.avatar-right'); //up the dom to avatar    
        let avatar_menu = avatar_div.querySelector('.avatar-right-dropdown');
        avatar_menu.classList.toggle('active');
    } 

    e.stopPropagation(); //so parent document doesn't notice the click and fire dismiss logic
}

const closeMobileMenu = (e) => { //do onclick from any element inside the mobile menu
    let container = findcontainer('avatar-right-dropdown', e.target);
    if(container) container.classList.remove('active');
} 
function findcontainer(id, el){
    if(el.id !== id && el.tagName !== 'BODY'){
        while(el.id !== id && el.tagName !== 'BODY'){
            el = el.parentElement;
        }
    }
    return (el.tagName === 'BODY' ) ? false : el;
}






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

/* color report */
const sendit = async(location, senddata ) => {
    const settings = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body:senddata
    };
    try {
        const fetchResponse = await fetch(location, settings);
        if(fetchResponse){
            if (!fetchResponse.ok) {
                return Error(response.statusText);
            }
            const receivedata = await fetchResponse.json();
            if(receivedata){
                return receivedata;
            } else {
                return 'there was a problem with your fetch request.'
            }
        }
        

        // do success stuff
    } catch (e) {
        
        return e;
    } 

}
const setName = async (e) => {
    e.preventDefault();
    if(e.target.classList.contains('saving')){
        //saving input 
        const location = ajaxurl + '?action=updateUserName';
        //build parameters
        const newName = document.querySelector(e.target.dataset.target).value
        let senddata = encodeURIComponent( 'userid' ) + '=' + encodeURIComponent( e.target.dataset.info );
        senddata += '&'+ encodeURIComponent( 'name' ) + '=' + encodeURIComponent( newName  );

        let response = await sendit(location, senddata);
        if( response.status == 200){
            let name1 = document.querySelector('.settings-name').innerText = newName;
            let name2 = document.getElementById('avatar-nav').innerText = newName;
            let message = document.querySelector('#profile-updated');
            message.innerText = response.message;
            speak(message); //system dialogue should follow this pattern
        } else {
            let message = document.querySelector('#profile-updated');
            message.innerText = response.message;
            speak(message);
        } 

        //reset the link
        let input = document.querySelector(e.target.dataset.target);
        input.setAttribute('readonly', 'readonly');
        e.target.innerText = 'Change';
        e.target.classList.remove('saving');
    } else {
        //set the link
        let input = document.querySelector(e.target.dataset.target);
        e.target.innerText = 'Save';
        e.target.classList.add('saving');
        input.removeAttribute('readonly');
        input.focus(); 
    }
}
const setEmail = async(e) => {
    e.preventDefault();
    if(e.target.classList.contains('saving')){
        const location = ajaxurl + '?action=updateEmail';
        //build parameters
        const newEmail = document.querySelector(e.target.dataset.target).value;
        let isNewEmail = validateEmail(newEmail);
        if( isNewEmail){
            let senddata = encodeURIComponent( 'userid' ) + '=' + encodeURIComponent( e.target.dataset.info );
            senddata += '&'+ encodeURIComponent( 'email' ) + '=' + encodeURIComponent( newEmail  );

        let response = await sendit(location, senddata);
        if( response ){
            let message = document.querySelector('#profile-updated');
            message.innerText = response.message;
            speak(message);
        }     
        } else {
            let message = document.querySelector('#profile-updated');
            message.innerText = 'Please enter a valid email.';
            speak(message);
            //set up link again
            let input = document.querySelector(e.target.dataset.target);
            input.value = '';
            input.setAttribute('readonly', 'readonly');
            e.target.innerText = 'Change';
            e.target.classList.remove('saving');
        }
        //set up link again
        let input = document.querySelector(e.target.dataset.target);
        input.setAttribute('readonly', 'readonly');
        e.target.innerText = 'Change';
        e.target.classList.remove('saving');
    } else {
        let input = document.querySelector(e.target.dataset.target);
        e.target.innerText = 'Save';
        e.target.classList.add('saving');
        input.removeAttribute('readonly');
        input.focus(); 
    }



}
const setPassword = async(e) => { 
    e.preventDefault();
    if(e.target.classList.contains('comparing')){
        let originalInput = document.querySelector(e.target.dataset.target);
        e.target.classList.remove('comparing');
        e.target.innerText = '';
        originalInput.setAttribute('readonly', 'readonly');
        //set confirm password area to visible
        let confirm_container = document.getElementById('password_confirm');
        confirm_container.classList.add('active');
        let confirmInput = document.querySelector(e.target.dataset.compare);
        confirmInput.classList.remove('inactive');
        confirmInput.classList.add('active');
        confirmInput.focus();

    }
    else if (e.target.classList.contains('confirming')){
        const inputPass = document.querySelector(e.target.dataset.target);
        const comparePass = document.querySelector(e.target.dataset.compare);
        //check if passwords match
        if(inputPass.value === comparePass.value){
            //matched passes
            const location = ajaxurl + '?action=updatePassword';
            let senddata = encodeURIComponent( 'userid' ) + '=' + encodeURIComponent( e.target.dataset.info );
            senddata += '&'+ encodeURIComponent( 'password' ) + '=' + encodeURIComponent( inputPass.value  );
            let response = await sendit(location, senddata);
            if( response ){
                //document.querySelector('#profile-updated').innerText = response.message;
                let message = document.querySelector('#profile-updated');
                message.innerText = response.message;
                speak(message);
                resetPassword(e.target);
            } 

        } else {
            resetPassword(e.target);
            let message = document.querySelector('#profile-updated');
            message.innerText = 'Passwords do not match.';
            speak(message);
        }

    } else {
        let input = document.querySelector(e.target.dataset.target);
        e.target.innerText = 'Save';
        e.target.classList.add('comparing');
        input.removeAttribute('readonly');
        input.placeholder = '';
        input.focus(); 
    }
}
const consentCheckbox = async(e) => {
    const location = ajaxurl + '?action=updateConsent';
    const consent_field = e.target.getAttribute('name');  //which consent field - TOC, PersistentID, etc.
    const consent_val = (e.target.checked) ? "yes" : "no";
    let senddata = encodeURIComponent( 'userid' ) + '=' + encodeURIComponent( e.target.dataset.userid );
    senddata += '&' + encodeURIComponent( consent_field ) + '=' + encodeURIComponent( consent_val );
    
    let response = await sendit(location, senddata);
    if( response ){
        let message = document.querySelector('#profile-updated');
        message.innerText = response.message;
        speak(message);
    }
}
/*const changeImage = (e) => { WP Media functions use jquery and are enqueued as seperate .js files
    e.preventDefault();

} */
const confirmDeleteGHC = async(e) => {
    const location = ajaxurl + '?action=ghc_BTTTTR_deleteUser';
    let senddata = encodeURIComponent( 'userid' ) + '=' + encodeURIComponent( e.target.dataset.userid );
    let response = await sendit(location, senddata);
    if( response.status == 200){
        let message = document.querySelector('#profile-updated');
        message.innerText = response.message;
        speak(message); //system dialogue should follow this pattern
        setTimeout(function() {
            window.location = '/daily-vibe';
        }, 4000)

    } else {
        let message = document.querySelector('#profile-updated');
        message.innerText = response.message;
        speak(message);
    } 

}

const deleteAccount = (e) => {
    //get nonce
    e.preventDefault();
    let userid = e.target.dataset.userid;
    let container = document.createElement('div');
    let span = document.createElement('span');
    let h4 = document.createElement('h4');
    let button = document.createElement('button');
    button.classList.add('modalbutton');
    button.setAttribute('data-userid', userid);
    button.setAttribute('onclick', 'confirmDeleteGHC(event); return false;');
    button.innerText = 'I get it. Delete Me';
    h4.innerText = 'Delete your Greenheart Connects account?'
    span.classList.add('warningtext');
    span.innerText = 'This action can\'t be reversed. Are you sure?';
    container.appendChild(h4);container.appendChild(span);container.appendChild(button);

    let body = document.getElementsByTagName('body')[0];
    if( ! body.classList.contains('modal-open') ){
        //can't call a modal from inside a modal
        //clone target element 
        let data_clone = container;
        body.classList.add('modal-open');
        NeonModalFactory.getInstance(data_clone, e.target );
    } 
    
} 
const validateEmail = (email) => {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
function resetPassword(link){ //resets password with either Password or Confirm Password link element parameter
    let input = document.querySelector(link.dataset.target);
    let inputlink = input.parentElement.querySelector('.change');
    let confirm_container = document.getElementById('password_confirm');
    let confirm_link = confirm_container.querySelector('.change');
    let confirm_input = document.querySelector(link.dataset.compare);
    input.value = '';
    input.setAttribute('readonly', 'readonly');
    input.placeholder="••••••••";
    inputlink.classList.remove(...inputlink.classList);
    inputlink.classList.add('change');
    inputlink.innerText = 'Change';
    confirm_link.classList.remove(...confirm_link.classList);
    confirm_link.classList.add('change', 'confirming');
    confirm_link.innerText = 'Confirm';
    confirm_input.value = '';
    confirm_input.classList.remove('active');
    confirm_input.classList.add('inactive');
    confirm_container.classList.remove('active');
}
function speak(el){
    el.classList.add('active');
    setTimeout(function() {
        el.classList.remove('active');
    }, 4000)
}


