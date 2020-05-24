function init(){

		let data = {
			jwt: "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLm9yZyIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUuY29tIiwiaWF0IjoxMzU2OTk5NTI0LCJuYmYiOjEzNTcwMDAwMDAsImRhdGEiOnsiaWQiOjMsImxhc3RuYW1lIjoiRG9lIiwiZmlyc3RuYW1lIjoiSm9obiIsImVtYWlsIjoiam9obmRvZUBleGFtcGxlLmNvbSJ9fQ.ZMgDCOJh_vbQ1Xtm_wS1jWaO3mKJZp2tynjh_mKSdCg"
		};

		var xhttp = new XMLHttpRequest();  

		xhttp.onreadystatechange = function(){

			if(xhttp.status == 200 && xhttp.readyState == 4){
				console.log( JSON.parse( this.responseText ) ); 
			}

		}

		xhttp.open('POST', 'api/validate_token.php', true);
		xhttp.setRequestHeader('Content-Type', 'application/json');
		xhttp.send( JSON.stringify( data ) ); 

}

	init(); 