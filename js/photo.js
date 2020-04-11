(function($) {
		//set the global variables
		var code = "";
		var imageURL = "";
		var downloadURL = "";
		var defaultIntro = "<p id='intro'>Welcome to the find my photo app!<br/>Please enter the code you recieved from our photographer to   retrieve your photo.<br/><i aria-hidden='true' class='fa fa-long-arrow-down'></i><br/></p>";
		//add the external CSS to the page
		$(document).ready(function() {
			console.log(introText);
			var fonts_link = $("<link>", {
				rel: "stylesheet",
				type: "text/css",
				href: "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
			});
			fonts_link.appendTo('head');
			/*setup the modal 
			$('#survey').modal({
				backdrop:true,
				keyboard:true
			}); */
			//get image
			$('#getImage').click(function() {
				retrieveImage();
			});
			//hide survey
			$('body').click(function() {
				$('#survey').modal('hide')
			});
			//post to FB
			$('#share').click(function() {
				loginStatus();
			});
			//remove the error warnings
			$("#theCode").on("click", function() {
				$('#errorMsg').hide();
				$('#theCode').css('border', '1px solid black');
			});
			//change the intro text if entered in WP otherwise set to default
			if (introText.length > 0) {
				$('#intro').html(introText);
			} else {
				$('#intro').html(defaultIntro);
			}
		});
		//retrieve the image based on code entered
		function retrieveImage() {
			code = $('#theCode').val(); //get the code entered 
			imageURL = 'http://test.rossmaguire.com/wp-content/uploads/' + code + '.jpg'; //build the image url - change to jpg or png
			downloadURL = code + '.jpg';
			//put the image url into the HTML
			$('#imageHolder #yourPhoto').attr('src', imageURL);
			//if there is an error with the code entered 
			$('#imageHolder #yourPhoto').on('error', function() {
				$('#errorMsg').show();
				$('#theCode').css('border', '1px solid red');
			});
			//if it is successfull, show the image
			$('#imageHolder #yourPhoto').on('load', function() {
				$('#codeDiv').fadeOut(100);
				$('#imageHolder').fadeIn(500);
				setTimeout(function() {
					$('#yourPhoto').show(function() {
						$('#imageHolder').css('background-image', 'none');
						$('#imageHolder').css('height', 'auto');
						$('#buttonsHolder').css('display', 'table');
						//check if Facebook and Download buttons should be shown or hidden from plugin admin
						//these variables are generated from admin functions in the main php file							
						if (showFacebook == 'false') {
							$('#share').hide();
						} else {
							$('#share').css('display', 'table-cell');
						}
						if (showDownload == 'false') {
							$('#save').hide();
						} else {
							$('#save').css('display', 'table-cell');
							$('#save').attr('href', imageURL);
							$('#save').attr('download', downloadURL);
						}
						if (showSurvey == 'true') {
							$('#surveyHolder').fadeIn(2000);
						} else {
							$('#survey').hide();
						}
					})
				}, 2500);
			});
		}
		/*trigger the survey at the start of the app 
		$(window).on('load',function(){
			if(showSurvey == 'false') {
			    $('#survey').modal('hide');
			}
			else {
				$('#survey').modal('show');
			}
		});
					
		/* try another image - reset the application
		function reset() {
			$('#imageHolder, #save, #share, #yourPhoto, #another, #errorMsg').hide();
			$('#imageHolder').css('background-image','url("/ross-test-wp/wp-content/plugins/photo_experience_irl/css/spinner.gif")');
			$('#imageHolder #yourPhoto').attr('src','');
			$('#errorMsg').hide();
			$('#codeDiv').fadeIn(800);
			
		} */
		/////////////////////////////////////////////////////////	
		//facebook SDK	//////////////////////////////////////////
		window.fbAsyncInit = function() {
			FB.init({
				appId: '755564021295761',
				autoLogAppEvents: true,
				xfbml: true,
				version: 'v2.11'
			});
		};
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {
				return;
			}
			js = d.createElement(s);
			js.id = id;
			js.src = "https://connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		//check the login status 
		function loginStatus() {
			FB.getLoginStatus(function(response) {
				if (response.authResponse) {
					console.log('we are connected to FB');
					getFBInfo();
				} else {
					console.log('we are not connected to FB' + response);
					FB.login(function(response) {
						if (response.authResponse) {
							console.log('Logging In');
							getFBInfo();
						} else {
							console.log('User cancelled login or did not fully authorize.');
						}
					}), {
						scope: 'publish_actions'
					};
				}
			});
		}
		//get the users info 
		function getFBInfo() {
			FB.api('/me/', 'GET', {
				fields: 'first_name,last_name,name,id'
			}, function(response) {
				console.log(response.id);
				console.log(response.name);
				uploadPhoto();
			});
		}
		//set up the Facebook sharing
		function uploadPhoto() {
			 /* FB.api('/me/feed', 'post', {
				    message: 'My Photo from Photo Experience Ireland',
					source: imageURL
				}, function(response) {
					if (!response || response.error) {
						console.log('upload failed' + response);
					} else {
						console.log('Success! Post ID: ' + response.id);
					}
				}); */
				FB.ui({
					  method: 'feed',
					  link: imageURL,
					  caption: 'Say something about your photo..',
					}, function(response){
						if (!response || response.error) {
						console.log('upload failed' + response);
					} else {
						console.log('Success! Post ID: ' + response.id);
					}
					});
			}
		})(jQuery);