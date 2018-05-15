<html>
<head>
 <title>Audio Books</title>

 <script src="js/api.js" ></script>

 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

 <link rel="stylesheet" type="text/css" href="themes/dg/css/universal.css" />
</head>
<body>

<div id="pageContainer">
<!--Login Screen -->
<div id="loginContainer" style="display:none" class="loginContainer">
	<form id="loginForm">
	  <div class="loginRow" id="loginHeader"><h2>Login</h2></div>
	  <div class="loginRow alert alert-warning" style="display: none" id="loginFeedBack"></div>
	  <div class="loginRow" id="loginUsernameRow">
	    <label>Username: </label><input type="text" id="loginUsername" />
	  </div>
	  <div class="loginRow" id="loginUsernameRow" id="loginPasswordRow" />
	    <label>Password: </label><input type="password" id="loginPassword" />
	  </div>
	  <div class="loginRow" id="submitLoginRow">
	    <input type="button" id="submitLogin" value="Authenticate" onclick="authenticate()"/>
	  </div>
	</form>
</div>
<!-- End Login Screen -->
 

</div>

<script>
	runOnLoad();
</script>
</body>
