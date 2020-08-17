const submitRegistration = (e) => {  
	e.preventDefault();
	console.log('submit clicked');
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
			}
		} else {
			error_msg = 'Please confirm password.'
		}
	} else {
		display_confirm = true;
	}

	let email = document.getElementById('email').value;
	if(! validateEmail(email) ){
		error_msg = 'Please enter a valid email.'
	}

	if(!error_msg && password_match){
		document.getElementById('username').value = email;
		//document.getElementById('registration').submit();
		registerIt(email, email, pass.value);
	} else if(!error_msg && display_confirm){
		//confirm.value = '';
		let confirmlabel = document.getElementById('confirmlabel');
		confirm.classList.add('active');
		confirmlabel.classList.add('active');
		} else if( error_msg){
			dialog.innerText = error_msg;
			dialog.classList.remove('hidden');
	}	
}
async function registerIt(username, email, password){
	console.log('registerit fired');
	const location = ajaxurl + '?action=register_user_front_end';
	let senddata = encodeURIComponent( 'username' ) + '=' + encodeURIComponent( username );
	senddata += '&' + encodeURIComponent( 'email' ) + '=' + encodeURIComponent( email );
	senddata += '&' + encodeURIComponent( 'password' ) + '=' + encodeURIComponent( password );
	
	let response = await sendit(location, senddata);
	if( response ){
		let dialog = document.getElementById('frontendvalidation');
		dialog.innerText = response.message;
		if( response.status == 200 ){
			//hide form
			document.getElementById('registration').classList.add('done');
			//show gravity form
			document.getElementById('register_payment').classList.add('active');
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
	console.log('sendit fired');
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