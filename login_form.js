// JavaScript Document
var currentUsername;

var getLoginUsername = function() {
	"use strict";
	currentUsername = document.getElementById("usernameInput").value;
};
document.getElementById("loginSubmit").onclick = getLoginUsername;

