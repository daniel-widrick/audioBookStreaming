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
<style>
.headerContainer {
	background-color: #222;
}
.row {
	margin-left: 0px;
}
.resumeMEdiaContainer {
	width: auto;
	overflow: auto;
}
.bookCard {
	background-color: #111;
	margin-left: 5px;
	padding: 3px;
	text-align: center;
	width: 215px;
}
.bookCard img {
	width: 128px;
	margin-left: auto;
	margin-right: auto;
}
</style>
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
<div id="mainContainer">
 <div class="row headerContainer" id="headerContainer">
  <div class="col-sm-2"><h1>Audiobooks</h1></div>
  <div class="col-sm-6"> </div>
  <div class="col-sm-2">username</div>
 </div>

 <div id="mediaSelectContainer">
  <h2>Continue Listening</h2>
  <div class="row resumeMediaContainer" id="resumeMediaContainer">
  </div>
 </div>
</div>
 

</div>

<script>
	runOnLoad();
</script>
</body>
