var username = false;
var loginFeedbackEl = "";
var loginScreenEl = "";

function apiCall(endPoint,method,parameters,content,callBack)
{
//AJAX Abstraction

	var xhr = new XMLHttpRequest();
	
	xhr.open(method,"api/" + endPoint + ".php?" + parameters);
	xhr.onload = function()
	{
		debug("response status: " + xhr.status);
		debug("response: " + xhr.response);
		if(xhr.status === 200)
			callBack(xhr.responseText);
		else
			callBack(false);
	};
	if(method === "POST")
	{
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(content);
	}
	else
		xhr.send();
}

function getUsername()
{
	apiCall("authentication","GET","mode=getUsername","",getUsernameCallback)
}

function getUsernameCallback(rsp)
{
	if(rsp === false)
	{
		debug("not logged in");
		showLogin();
		return false;
	}
	else
	{
		var usernameResponse = JSON.parse(rsp);
		debug(usernameResponse);
		hideLogin();
	}
	
}
function authenticate()
{
	var password = document.getElementById("loginPassword").value;
	var username = document.getElementById("loginUsername").value;
	loginFeedbackEl.innerHTML = "";
	if(username == "")
	{
		loginFeedbackEl.style.display = "block";
		loginFeedbackEl.innerHTML += "Username Cannot be Blank<br />";
		return false;
	}
	if(password == "")
	{
		loginFeedbackEl.style.display = "block";
		loginFeedbackEl.innerHTML += "Password Cannot be Blank<br />";
		return false;
	}
	//TODO:: Encode
	var postData = "username="+username+"&password="+password;
	apiCall("authentication","POST","mode=login",postData,authenticateCallback);
}
function authenticateCallback(rsp)
{
	if(rsp === false)
	{
		showLogin();
		loginFeedbackEl.style.display = "block";
		loginFeedbackEl.innerHTML = "Invalid Username/password";
	}
	else
		getUsername();
		
}


function showLogin()
{
	//TODO: Hide other screens
	loginScreenEl.style.display = "block";
}
function hideLogin()
{
	loginScreenEl.style.display = "none";
}
function runOnLoad()
{
	loginFeedbackEl = document.getElementById("loginFeedBack");
	loginScreenEl = document.getElementById("loginContainer");
	getUsername();
}

function debug(msg)
{
	var debugMode = true;
	if(debugMode)
		console.log(new Date().valueOf() + " :: " + msg);
	return debugMode;
}
