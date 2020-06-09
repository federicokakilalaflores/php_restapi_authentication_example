$(document).ready(function(){


	// serializeObject() for form handling as Object
	$.fn.serializeObject = function()
	{
	   var o = {};
	   var a = this.serializeArray();
	   $.each(a, function() {
	       if (o[this.name]) {
	           if (!o[this.name].push) {
	               o[this.name] = [o[this.name]];
	           }
	           o[this.name].push(this.value || '');
	       } else {
	           o[this.name] = this.value || '';
	       }
	   });
	   return o;
	};

	//getCookie function
	function getCookie(cname) {
	    var name = cname + "=";
	    var decodedCookie = decodeURIComponent(document.cookie);
	    var ca = decodedCookie.split(';');
	    for(var i = 0; i <ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) == ' ') {
	            c = c.substring(1);
	        }
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length, c.length);
	        }
	    }
	    return "";
	}

	// setCookie function
	function setCookie(cname, cvalue, exdays) {
   		var d = new Date();
    	d.setTime(d.getTime() + (exdays*24*60*60*1000));
    	var expires = "expires="+ d.toUTCString();
    	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	// show current menu when user is logged out
	function showLoggedOutMenu(){
		$('#signup, #login').show();
		$('#logout').hide();
	}

	// show current menu when user is logged in
	function showLoggedInMenu(){
		$('#signup, #login').hide();
		$('#logout').show();
	}



	
	// EVENTS SECTION 

	$(document).on('click', '#home', function(ev){
		showHomePage(ev); 
	});

	$(document).on('click', '#update_account', function(ev){
		showUpdateAccountForm(ev);
	});

	$(document).on('click', '#signup', function(ev){
		showSignupForm(ev);
	});

	$(document).on('click', '#login', function(ev){
		showLoginForm(ev);
	});

	$(document).on('click', '#logout', function(ev){
		showLoginForm(ev)
		$('#message').html( 
			'<div class="alert alert-info">You are now logged out.</div>'  
		);  
	});

	showLoggedOutMenu();
	

	// REQUEST SECTION 
	
	$(document).on('submit', '#login-form', function(ev){
		ev.preventDefault();
		let loginForm = $(this);
		let formData = loginForm.serializeObject();
		 
		$.ajax({
			url: 'api/login.php',
			type: 'POST',
			contentType: 'application/json',
			dataType: 'json',
			data: JSON.stringify(formData),
			success: function(result){
				setCookie('jwt', result.jwt, 1);
				showHomePage(ev);
				loginForm.find('.input-field').val('');
			},
			error: function(xhr, status, err){
				$('#message').html( 
					'<div class="alert alert-danger">Unable to login.</div>' 
				);
				loginForm.find('.input-field').val('');
			}
		});

	});


	$(document).on('submit', '#signup-form', function(ev){
		ev.preventDefault();

		let signupForm = $(this);
		let formData = signupForm.serializeObject();


		$.ajax({
			url: 'api/create_user.php',
			type: 'POST',
			dataType: 'json', 
			data: JSON.stringify( formData ),  
			success: function(result){
				$('#message').html( 
					'<div class="alert alert-success">Signup successfully. Please login.</div>' 
				);
				signupForm.find('.input-field').val('');
			},
			error: function(xhr, status, err){
				$('#message').html( 
					'<div class="alert alert-danger">Unable to signup.</div>' 
				);
			} 
		});
		                                                                                                           
	});


	$(document).on('submit', '#update-account-form', function(ev){
		ev.preventDefault();

		let updateform = $(this);
		let jwt = getCookie('jwt');
		var formData = updateform.serializeObject();
		formData.jwt = jwt; 

		$.ajax({
			url: 'api/update_user.php',
			type: 'POST',
			dataType: 'json',
			contentType: 'application/json',
			data: JSON.stringify( formData ),   	
			success: function(result){
				$('#message').html( 
					'<div class="alert alert-success">Save changes successfully.</div>' 
				); 
				setCookie('jwt', result.jwt, 1);
			},
			error: function(xhr, status, err){
				$('#message').html( 
					'<div class="alert alert-danger">Unable to save your changes.</div>' 
				);
			}
		});


	});


	// PAGES CONTENT

	function showSignupForm(ev){
		ev.preventDefault(); 

		let html = `
			<div id="message"></div>
			<h2>Sign up</h2>
			<hr />
			<form id="signup-form">
				<div class="input-group">
					<label for="firstname">Firstname</label>
					<input type="text" name="firstname" class="input-field" id="firstname" required>		
				</div>		
				<div class="input-group">
					<label for="lastname">Lastname</label>
					<input type="text" name="lastname" class="input-field" id="lastname" required>		
				</div>
				<div class="input-group">
					<label for="email">Email</label>
					<input type="text" name="email" class="input-field" id="email" required>		
				</div>	
				<div class="input-group">
					<label for="password">Password</label>
					<input type="password" name="password" class="input-field" id="password" required>		
				</div>	
				<button type="submit" class="btn btn-primary" id="signup-btn">Signup</button> 		
			</form>
		`;

		$('#content').html( html );
	}


	function showLoginForm(ev){
		ev.preventDefault();

		setCookie('jwt', '', 1);

		html = `
			<div id="message"></div>
			<h2>Login</h2>
			<hr />
			<form id="login-form">
				<div class="input-group">
					<label for="email">Email</label>
					<input type="text" name="email" class="input-field" id="email">		
				</div>	
				<div class="input-group">
					<label for="password">Password</label>
					<input type="password" name="password" class="input-field" id="password">		
				</div>	
				<button type="submit" class="btn btn-primary" id="login-btn">Login</button> 		
			</form>  
		`;

		$('#content').html( html );
		showLoggedOutMenu();
	}

	function showHomePage(ev){
		ev.preventDefault(); 

 		let jwt = getCookie('jwt'); 

 		// validate Token
 		$.post(
 			'api/validate_token.php',
 			JSON.stringify({jwt: jwt}) 
 		).done(function(res){

 			let html = `
 				<div id="message">
 					<div class="alert alert-success">Login successfully.</div>
 				</div>
 				<div class="card">
					<div class="card-header">
						<h4>Welcome Home ${res.data.firstname + " " + res.data.lastname}!.</h4>
					</div>
					<div class="card-body">
						<h3 class="card-title">You are logged in.</h3>
						<p class="card-text">
							You won't be able to access home and account pages
							if you are not logged in.
						</p>
					</div>
				</div>
 			`;

 			$('#content').html( html );
  			showLoggedInMenu(); 

 		}).fail(function(res){

 			showLoginForm(ev);
 			$('#message').html( 
				'<div class="alert alert-danger">Please Login to access the homepage.</div>' 
			);

 		});

	}

	function showUpdateAccountForm(ev){ 
		ev.preventDefault(); 

		let jwt = getCookie('jwt'); 

		$.post(
			'api/validate_token.php',
			JSON.stringify({jwt: jwt})
		).done(function(res){    
			let html = ` 
				<div id="message"></div> 
				<h2>My Account</h2>
				<hr />
				<form id="update-account-form">
					<div class="input-group">
						<label for="firstname">Firstname</label>
						<input type="text" name="firstname" class="input-field" id="firstname"
						value="${res.data.firstname}" required>		
					</div>		
					<div class="input-group">
						<label for="lastname">Lastname</label>
						<input type="text" name="lastname" class="input-field" id="lastname"
						value="${res.data.lastname}" required>		
					</div>
					<div class="input-group">
						<label for="email">Email</label>
						<input type="text" name="email" class="input-field" id="email"
						value="${res.data.email}" required>		
					</div>	
					<div class="input-group">
						<label for="password">Password</label>
						<input type="password" name="password" class="input-field" id="password"
						required>		
					</div>	   
					<button type="submit" class="btn btn-primary" id="update-btn">Save changes</button> 		
				</form> 
			`;

		$('#content').html( html );  
		showLoggedInMenu();  

		}).fail(function(){

			showLoginForm(ev);
 			$('#message').html( 
				'<div class="alert alert-danger">Please Login to access the homepage.</div>' 
			);

		});

 	
	}


});