const NeonModalFactory = (function(){
    function NeonModalClass(content_element, referring_element) {
        
        let frag = document.createDocumentFragment;
        let container = document.createElement('div');
        let backbutton = document.createElement('span');
        let content = document.createElement('div');
        let svg_blob='<svg id="neon_modal_balls" data-name="neon_modal_balls" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 222.9 397.98"><style>.neon_modal_colorfill{fill:#8ac53f}</style><title>NeonModal</title><path class="neon_modal_colorfill" d="M118,119A119,119,0,0,0,.94,0L.1,238A119,119,0,0,0,118,119Z" transform="translate(-0.1 -0.02)" /><circle class="neon_modal_colorfill" cx="182.9" cy="167.98" r="40" /><circle class="neon_modal_colorfill" cx="130.9" cy="316.98" r="81" /></svg>';
        let body = document.getElementsByTagName('body')[0];
        //let yposition = window.scrollY + referring_element.getBoundingClientRect().top;
        let yposition = window.scrollY 
        // add classes 
        container.classList.add('neon-modal');
        backbutton.classList.add('close');
        content.classList.add('neon-modal-content');
        if(referring_element.hasAttribute('data-modal-size')){
            content.classList.add( referring_element.dataset.modalSize );
        }
        //add background image
        content.innerHTML = svg_blob;
        //add content
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



const expandMenu = (e) => {
 console.log('pop menu');  
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


const submitRegistration = (e) => {  
	e.preventDefault();
	let confirm = document.getElementById('passwordconfirm');
	let pass = document.getElementById('password');
	let dialog = document.getElementById('frontendvalidation');
	dialog.innerText = '';
	dialog.classList.add('hidden');
	let error_msg = false;
	let password_match = false;
	let display_confirm = false;
	if(confirm.classList.contains('active')){
		if( confirm.value.length > 0 && pass.value.length > 0 ){
			if(confirm.value === pass.value){
				password_match = true;
			} else {
				error_msg = 'Passwords do not match.'
			}
		} else {
			error_msg = 'Please confirm password.'
		}
	} else {
		display_confirm = true;
	}
	let username = document.getElementById('username').value;

	if(! validateUsername(username) ){
		error_msg = 'Usernames must be 4 characters or more and only use alpha-numeric characters.'
	}
	
	let email = document.getElementById('email').value;

	if(! validateEmail(email) ){
		error_msg = 'Please enter a valid email.'
	}

	if(!error_msg && password_match){
		document.getElementById('registration').submit();
	} else if(!error_msg && display_confirm){
		confirm.value = '';
		let confirmlabel = document.getElementById('confirmlabel');
		confirm.classList.add('active');
		confirmlabel.classList.add('active');
		} else if( error_msg){
			dialog.innerText = error_msg;
			dialog.classList.remove('hidden');
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