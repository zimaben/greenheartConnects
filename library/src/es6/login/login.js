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