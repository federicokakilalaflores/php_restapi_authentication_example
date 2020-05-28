$(document).ready(function(){

	// when signup link clicked
	$(document).on('click', '#signup', function(ev){
		ev.preventDefault();

		let html = `
			<div id="message">
				
			</div>
			<h2>Sign up</h2>
			<hr />
			<form id="signup-form">
				<div class="input-group">
					<label for="firstname">Firstname</label>
					<input type="text" name="firstname" class="input-field" id="firstname">		
				</div>		
				<div class="input-group">
					<label for="lastname">Lastname</label>
					<input type="text" name="lastname" class="input-field" id="lastname">		
				</div>
				<div class="input-group">
					<label for="email">Email</label>
					<input type="text" name="email" class="input-field" id="email">		
				</div>	
				<div class="input-group">
					<label for="password">Password</label>
					<input type="password" name="password" class="input-field" id="password">		
				</div>	
				<button type="submit" class="btn btn-primary" id="signup-btn">Signup</button> 		
			</form>
		`;

		$('#content').html( html );
	});


	// when signup form submitted
	$(document).on('submit', '#signup-form', function(ev){
		ev.preventDefault();

		let signupForm = $(this);

		

	});

});