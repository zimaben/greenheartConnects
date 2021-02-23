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
        //add background image
        //content.innerHTML = svg_blob;

        content.appendChild(backbutton);
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
            var body = document.getElementsByTagName('BODY')[0];
            var modal = document.getElementsByClassName('neon-modal')[0];
            var resp = modal.querySelector('#neonid-signup-response');
            if(resp !== null) resp.innerHTML = '';
            body.classList.remove('modal-open');
            body.setAttribute("style", "");
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



const submitRegistration = (e) => {  
	e.preventDefault();
	let confirm = document.getElementById('passwordconfirm');
	let pass = document.getElementById('password');
	let dialog = document.getElementById('frontendvalidation');
	dialog.innerText = '';  
	dialog.classList.add('hidden');
	let error_msg = false; 
	let password_match = false; 
	let display_confirm = true;
	if(confirm.classList.contains('active')){
		if( confirm.value.length > 0 && pass.value.length > 0 ){
			if(confirm.value === pass.value){
				password_match = true;
			} else {
                error_msg = 'Passwords do not match.'
                let message = new Dialog({'text': error_msg});
			}
		} else {
            error_msg = 'Please confirm password.'
            let message = new Dialog({'text': error_msg});
		}
	} else {
		display_confirm = true;
	}

	let email = document.getElementById('email').value;
	if(! validateEmail(email) ){
        error_msg = 'Please enter a valid email.';
        let message = new Dialog({'text': error_msg});
	}

	if(!error_msg && password_match){
		document.getElementById('username').value = email;
        //document.getElementById('registration').submit();
        let first = document.getElementById('firstname').value;
        let last = document.getElementById('lastname').value;
		registerIt(email, email, pass.value,first, last );
	} else if(!error_msg && display_confirm){
		//confirm.value = '';
		let confirmlabel = document.getElementById('confirmlabel');
		confirm.classList.add('active');
		confirmlabel.classList.add('active');
		}
}
async function registerIt(username, email, password, first, last){
	const location = ajaxurl + '?action=register_user_front_end';
	let senddata = encodeURIComponent( 'username' ) + '=' + encodeURIComponent( username );
	senddata += '&' + encodeURIComponent( 'email' ) + '=' + encodeURIComponent( email );
    senddata += '&' + encodeURIComponent( 'password' ) + '=' + encodeURIComponent( password );
    senddata += '&' + encodeURIComponent( 'firstname' ) + '=' + encodeURIComponent( first );
    senddata += '&' + encodeURIComponent( 'lastname' ) + '=' + encodeURIComponent( last );
	let response = await sendit(location, senddata);
	if( response ){
		let dialog = document.getElementById('frontendvalidation');
		//dialog.innerText = response.message;
		if( response.status == 200 ){  
            console.log(response);
            let resp = JSON.parse(response.message);
            //console.log(response.message);
			//hide form
			//document.getElementById('registration').classList.add('done');
			//show gravity form
            //document.getElementById('register_payment').classList.add('active');
           // window.location='/login/?&message=newRegistration&email='+response.email
           window.location='/?&firstname='+resp.firstname+'&lastname='+resp.lastname+'&email='+resp.email;
             
		} else {
            console.log(response);
            let message = new Dialog({text: response.message});
        }	
	}
	
}
const goToMyInfo = (e) => {
	e.preventDefault();
	let prev_section = document.getElementById('section_membership');
	prev_section.classList.add('done');
	let next_section = document.getElementById('section_myinfo');
	next_section.classList.add('active');
	let submit = document.querySelector('.gform_footer>input[type="submit"]');
	submit.classList.add('active');

}
const sendit = async(location, senddata ) => {
	console.log(senddata);
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
			console.log(receivedata);
            return receivedata;
        }
        // do success stuff
    } catch (e) {
        console.log(e);
        let message = new Dialog({text: JSON.stringify(e)})
        return e;
    } 

}

function validateUsername(username){
	if(username.length >= 4){
		console.log(username);
		var pattern = /^[a-z0-9]+$/;

		//let alphanumeric = username.match(pattern); 
		let alphanumeric = pattern.test(username);
		console.log(alphanumeric);
		return alphanumeric;
	} else {
	return false; 
	} 
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
} 
const loginModal = (e) => {
	e.preventDefault();
	let data_target = document.getElementById( e.target.dataset.modalTarget);
	let body = document.getElementsByTagName('body')[0];
	if( ! body.classList.contains('modal-open') ){
		//can't call a modal from inside a modal
		//clone target element 
		let data_clone = data_target.cloneNode(true);

		body.classList.add('modal-open');
		NeonModalFactory.getInstance(data_clone, e.target );
	}  	
}