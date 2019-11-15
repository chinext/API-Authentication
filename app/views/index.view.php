<?php require('partials/header.php'); ?>


<!-- container -->
<main role="main" class="container starter-template">
<h1>Home Page</h1>
    <div class="row">
        <div class="col">
 
            <!-- prompt messages -->
            <div id="response"></div>
 
            <!-- main content -->
            <div id="content">            	

            </div>
        </div>
    </div>
 
</main>
<!-- /container -->


 

<?php require('partials/footer.php'); ?>








<script>

$(document).ready(function(){

	showHomePage();

    // show registration form
    $(document).on('click', '#sign_up', function(){ 
        var html = `
            <h2>Sign Up</h2>
            <form id='sign_up_form'>
                <div class="form-group">
                    <label for="firstname">Firstname</label>
                    <input type="text" class="form-control" name="firstname" id="firstname" required />
                </div>
 
                <div class="form-group">
                    <label for="lastname">Lastname</label>
                    <input type="text" class="form-control" name="lastname" id="lastname" required />
                </div>
 
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required />
                </div>
 
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required />
                </div>
 
                <button type='submit' class='btn btn-primary'>Sign Up</button>
            </form>
            `;
        clearResponse();
        $('#content').html(html);
    }); 
    // Submit registration form
    $(document).on('submit', '#sign_up_form', function(){ 
		    var sign_up_form=$(this);
		    var form_data=JSON.stringify(sign_up_form.serializeObject());		 
		    // submit form data to api
		    $.ajax({
		        url: "register",
		        type : "POST",
		        contentType : 'application/json',
		        data : form_data,
		        success : function(result) {
		            $('#response').html("<div class='alert alert-success'>Successful sign up. Please login.</div>");
		            sign_up_form.find('input').val('');
		        },
		        error: function(xhr, resp, text){
		        	if(xhr.responseJSON.message=="Email exist"){
				        $('#response').html("<div class='alert alert-danger'>Unable to sign up, Email exist</div>");
				    }else{
				    	$('#response').html("<div class='alert alert-danger'>Unable to sign up. Please contact admin.</div>");
				    }

		           
		        }
		    });		 
		    return false;
	});


    // show login form
	$(document).on('click', '#login', function(){
	   showLoginPage();
	});
	// submit login form 
	$(document).on('submit', '#login_form', function(){
	    var login_form=$(this);
	    var form_data=JSON.stringify(login_form.serializeObject());	 
	    $.ajax({
		    url: "login",
		    type : "POST",
		    contentType : 'application/json',
		    data : form_data,
		    success : function(result){
		        setCookie("jwt", result.jwt, 1);
		        showHomePage();
		        $('#response').html("<div class='alert alert-success'>Successful login.</div>");
		    },
		    error: function(xhr, resp, text){
			    $('#response').html("<div class='alert alert-danger'>Login failed. Email or password is incorrect.</div>");
			    login_form.find('input').val('');
			}
		});
	 
	    return false;
	});

	// show home page
	$(document).on('click', '#home', function(){
	    showHomePage();
	    clearResponse();
	});

	function showHomePage(){
	    var jwt = getCookie('jwt');
	    $.post("validateToken", JSON.stringify({ jwt:jwt })).done(function(result) {	 
	        var html = `<div class="card">
				        <div class="card-header">Welcome to Home!</div>
				        <div class="card-body">
				            <h5 class="card-title">You are logged in.</h5>
				            <p class="card-text">You won't be able to access the home and account pages if you are not logged in.</p>
				        </div>
					  </div>`; 
			$('#content').html(html);
			showLoggedInMenu();
	    }).fail(function(result){
		    showLoginPage();
		    $('#response').html("<div class='alert alert-danger'>Please login to access the home page.</div>");
		});
	}

	
	function showLoginPage(){
	    setCookie("jwt", "", 1);
	    var html = `<h2>Login</h2>
		        <form id='login_form'>
		            <div class='form-group'>
		                <label for='email'>Email address</label>
		                <input type='email' class='form-control' id='email' name='email' placeholder='Enter email'>
		            </div>		 
		            <div class='form-group'>
		                <label for='password'>Password</label>
		                <input type='password' class='form-control' id='password' name='password' placeholder='Password'>
		            </div>		 
		            <button type='submit' class='btn btn-primary'>Login</button>
		        </form>`;
	    $('#content').html(html);
	    clearResponse();
	    showLoggedOutMenu();
	}



	$(document).on('click', '#update_account', function(){
	    showUpdateAccountForm();
	});

	function showUpdateAccountForm(){
	    var jwt = getCookie('jwt');
	    $.post("validateToken", JSON.stringify({ jwt:jwt })).done(function(result) {	 
	    var html = `<h2>Update Account</h2>
	        <form id='update_account_form'>
	            <div class="form-group">
	                <label for="firstname">Firstname</label>
	                <input type="text" class="form-control" name="firstname" id="firstname" required value="` + result.data.firstname + `" />
	            </div>	 
	            <div class="form-group">
	                <label for="lastname">Lastname</label>
	                <input type="text" class="form-control" name="lastname" id="lastname" required value="` + result.data.lastname + `" />
	            </div>	 
	            <div class="form-group">
	                <label for="email">Email</label>
	                <input type="email" class="form-control" name="email" id="email" required value="` + result.data.email + `" />
	            </div>	 
	            <div class="form-group">
	                <label for="password">Password</label>
	                <input type="password" class="form-control" name="password" id="password" />
	            </div>
	 
	            <button type='submit' class='btn btn-primary'>
	                Save Changes
	            </button>
	        </form>`;
 		clearResponse();
		$('#content').html(html);
	    }).fail(function(result){
		    showLoginPage();
		    $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
		});	 
	}
    // submit update account form 
	$(document).on('submit', '#update_account_form', function(){
	    var update_account_form=$(this);
	    // validate jwt to verify access
	    var jwt = getCookie('jwt');
	    var update_account_form_obj = update_account_form.serializeObject();
		update_account_form_obj.jwt = jwt;
		var form_data=JSON.stringify(update_account_form_obj);
		$.ajax({
		    url: "updateUser",
		    type : "POST",
		    contentType : 'application/json',
		    data : form_data,
		    success : function(result) {
		        $('#response').html("<div class='alert alert-success'>Account was updated.</div>");
		        setCookie("jwt", result.jwt, 1);
		    },
		    error: function(xhr, resp, text){
			    if(xhr.responseJSON.message=="Unable to update user."){
			        $('#response').html("<div class='alert alert-danger'>Unable to update account.</div>");
			    }			 
			    else if(xhr.responseJSON.message=="Access denied."){
			        showLoginPage();
			        $('#response').html("<div class='alert alert-success'>Access denied. Please login</div>");
			    }
			}
		});


	 
	    return false;
	});


	// logout the user
	$(document).on('click', '#logout', function(){
	    showLoginPage();
	    $('#response').html("<div class='alert alert-info'>You are logged out.</div>");
	});
	 


    
    function clearResponse(){
	    $('#response').html('');
	}


	// function to make form values to json format
	$.fn.serializeObject = function(){	 
	    var o = {};
	    var a = this.serializeArray();
	    $.each(a, function() {
	        if (o[this.name] !== undefined) {
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

	// function to set cookie
	function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+ d.toUTCString();
	    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	// function to get cookie
	function getCookie(cname){
	    var name = cname + "=";
	    var decodedCookie = decodeURIComponent(document.cookie);
	    var ca = decodedCookie.split(';');
	    for(var i = 0; i <ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) == ' '){
	            c = c.substring(1);
	        }	 
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length, c.length);
	        }
	    }
	    return "";
	}



	// if the user is logged out
	function showLoggedOutMenu(){
	    // show login and sign up from navbar & hide logout button
	    $("#login, #sign_up").show();
	    $("#logout").hide();
	}

   // if the user is logged in
	function showLoggedInMenu(){
	    // hide login and sign up from navbar & show logout button
	    $("#login, #sign_up").hide();
	    $("#logout").show();
	}
 

 //10.5 Trigger when login form is submitted
 
 


});
</script>
