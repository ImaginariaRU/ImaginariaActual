document.addEventListener('DOMContentLoaded', function(){
	let bLocal = !location.host;
	let script_url= bLocal? "" : "https://imaginaria.ru/registration_ajax_callback.php" ;
	
	function randd(min, max) {
		return Math.floor(arguments.length > 1 ? (max - min + 1) * Math.random() + min : (min + 1) * Math.random());
	};
	
	function hide_form(){
		let oForm = document.querySelector('.reg_form');	
		if(oForm) {
			oForm.classList.add('hide');
			setTimeout(function(){
				oForm.remove(); 

				}, 20);
		}
		
	}
	async function show_loader(){
		let oLoader = document.querySelector('.loading');	
		if(oLoader) {
			oLoader.classList.remove('hide');
		}
	}
	function set_loader(){
		let oCube = document.querySelector('.cube');
		if(oCube) {
			let nVal =	randd(2,5);		
			oCube.classList.add('roll-'+nVal);			
		}
	}
	
	async function die_ok(){
		let oCube = document.querySelector('.die');
		if(oCube) {				
			pretty_roll(20)
		}
	}
	async function die_bad(){
		let oCube = document.querySelector('.die');
		if(oCube) {		
			pretty_roll(1, true)			
		}
	}
	
	function show_success(){
		let oForm = document.querySelector('.form');	
		if(oForm) {
			oForm.classList.remove('hide');
		}
		
		let oEl = document.querySelector('.success');
		if(oEl) {	
			oEl.classList.remove('hide');			
		}
	}
	function show_fail(sText = 'Увы. Что-то пошло не так'){
		setTimeout(function(){
			let oForm = document.querySelector('.form');	
			if(oForm) {
				oForm.classList.remove('hide');
			}
			
			let oEl = document.querySelector('.fail');
			if(oEl) {	
				let aTele = 'Если что, пишите в <a href="https://t.me/Imaginaria2021">телеграм чат Имажинарии</a>';
				oEl.innerHTML = sText + "<br>" + aTele;
				oEl.classList.remove('hide');			
			}
		}, 3000);
		
	}
	
	function sleep(nMs = 1000) {
		let oP = new Promise((resolve, reject) => {
			setTimeout(function(){resolve()}, nMs);
		});
		
		return oP;
	}

	async function _sendRequest(url = '', data = {}, method='POST') {
			let abortController = new AbortController();
			window.onbeforeunload = function(e) { abortController.abort(); };
		
			//Default options are marked with *
			const response = await fetch(url, {
				method: method, // *GET, POST, PUT, DELETE, etc.
				mode: 'cors', // no-cors, *cors, same-origin
				cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
				credentials: 'same-origin', // include, *same-origin, omit
				headers: {
					'Content-Type': 'application/json;charset=utf-8' // 'Content-Type': 'application/x-www-form-urlencoded',					
				},
				redirect: 'follow', // manual, *follow, error
				referrerPolicy: 'no-referrer', // no-referrer, *client
				body: JSON.stringify(data), // body data type must match "Content-Type" header
				signal : abortController.signal 
			});
			//return await response.json(); // parses JSON response into native JavaScript objects
			return await response.text(); // parses JSON response into native JavaScript objects
			
	}
	/*
	 * Collect user's data
	 * No tests here (like injection)
	 */
	async function sendRegisterData(oEvent){
		oEvent.preventDefault(); // prevent page reloading
		let oLogin = document.querySelector('#login');
		let oMail = document.querySelector('#email');
		let oPass = document.querySelector('#pass1');
		let oPass2 = document.querySelector('#pass2');
		
		let sLogin = oLogin.value.trim();
		let sMail = oMail.value.trim();
		let sPass = oPass.value.trim();
		let sPass2 = oPass2.value.trim();
		
		if(sLogin.length<3) {
			alert('Слишком короткий логин');
			return;			
		}
		if(sMail.length<3) {
			alert('Укажите приемлимый email');
			return;			
		}
		if(sPass.length<3) {
			alert('Пароль слишком короткий');
			return;			
		}
		
		if(sPass != sPass2){
			alert('Введенные пароли не совпадают!');
			return;
		}
		
		var sCaptcha = grecaptcha.getResponse();
		if(sCaptcha || bLocal){
		
			hide_form();
			show_loader();
			loading_roll();//rollTo(randomFace());
			let oData = {
				login: sLogin, // user data
				email: sMail, // user data
				pass: sPass, // user data
				stat : 'add',
				captcha: sCaptcha // !important
			};
			try {
				let oRegResp = await _sendRequest(script_url, oData, 'POST');
				
				//debugger;
				switch(oRegResp) {
					case 'ok':
							die_ok();
							sleep(1500);
							show_success();
						break;
					case 'err_cap':
							die_bad();
							sleep(1500);
							show_fail('Каптча показала, что вы подозрителньо напоминаете робота');
						break;	
					case 'err_login':
							die_bad();
							sleep(1500);
							show_fail('С логином что-то не так');
						break;	
					case 'err_email':
							die_bad();
							sleep(1500);
							show_fail('С указанной почтой что-то не так');
						break;	
					case 'err_pass':
							die_bad();
							sleep(1500);
							show_fail('С указанным паролем что-то не так');
						break;
					case 'err_empty':
							die_bad();
							sleep(1500);
							show_fail('Внезапно выяснилось, что логин, пароль или почта не заполнены');
						break;	
					case 'err_login_mail':
							die_bad();
							sleep(1500);
							show_fail('Логин или почта уже зарегистрированы');
						break;	
					case 'not_ok':
							die_bad();
							sleep(1500);
							show_fail('Что-то пошло не так при добавлении пользователя');
						break;	
					default:
							die_bad();
							sleep(1500);
							show_fail();
				}
				oLogin.value = '';
				oMail.value = '';
				sPass.value = '';
				sPass2.value = '';
				
			} catch (error){
				die_bad();
				sleep(500);
				show_fail('Ошибка запроса');
			}
			
	 } else {
		 alert('Каптча не пройдена');
	 }
	}
	 let $die = document.querySelector('.die'),
		sides = 20,
    initialSide = 1,
    lastFace,
    timeoutId,
    transitionDuration = 500, 
    animationDuration  = 3000
		
	function randomFace() {
		var face = Math.floor((Math.random() * sides)) + initialSide;
		lastFace = face == lastFace ? randomFace() : face;
		return face;
	}

	function rollTo(face) {
		clearTimeout(timeoutId);
		$die.classList.remove('rolling');	
				
		$die.dataset.face = face;
	}

	function reset() {
		$die.dataset.face = null;
		$die.classList.remove('rolling');
	}
	function loading_roll(){
		let nVal =	randd(2,19);
		pretty_roll(nVal, false);
	}
	function pretty_roll(nSide, bBad){
		clearTimeout(timeoutId);
		$die.classList.add('rolling');	
  
		timeoutId = setTimeout(function () {
			$die.classList.remove('rolling');	
			if(bBad){
				$die.classList.add('bad');
			}	 else {
				$die.classList.remove('bad');
			}				
			rollTo(nSide);
		}, animationDuration);
		
		return false;
	}
	

	$die.onclick = function(){
		if(!$die.classList.contains('rolling')) {
			rollTo(randomFace());
		}
	}

	rollTo(randomFace());

	//make_form();
	//remove_old_recaptcha_script();
	//set_recaptcha_script();
	set_loader();
	let oButton = document.querySelector('#registration');
	if(oButton) {
		oButton.onclick = sendRegisterData; // bind event		
	}
});
