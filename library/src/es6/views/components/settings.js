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
        const receivedata = await fetchResponse.json();
        if(receivedata.status == 200){
            return receivedata;
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

