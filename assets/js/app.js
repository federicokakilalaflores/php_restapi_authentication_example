function init(){

		let data = {
			email: "johndoe@example.com",
			password: "testlang" 
		};

		var xhttp = new XMLHttpRequest(); 

		xhttp.onreadystatechange = function(){

			if(xhttp.status == 200 && xhttp.readyState == 4){
				console.log( JSON.parse( this.responseText ) ); 
			}

		}

		xhttp.open('POST', 'api/login.php', true);
		xhttp.setRequestHeader('Content-Type', 'application/json');
		xhttp.send( JSON.stringify( data ) ); 

}

	init();