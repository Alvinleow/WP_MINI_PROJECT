// Get the login button and profile button elements
var loginButton = document.getElementById('loginButton');
var profileButton = document.getElementById('profileButton');
var userContainer = document.getElementById('user-container');

function getIsLoggedIn() {
    // Check user login status
    var isLoggedIn = localStorage.getItem('isLoggedIn'); // Retrieve the login status from localStorage
    var username = localStorage.getItem('username'); // Retrieve the username from localStorage

    if (isLoggedIn === 'true') {
        // User is logged in
        loginButton.style.display = 'none'; // Hide the login button
        profileButton.style.display = 'block'; // Show the profile button
        profileButton.innerText = 'Welcome, ' + username + '!'; // Update the button text
    } else {
        // User is not logged in
        loginButton.style.display = 'block'; // Show the login button
        profileButton.style.display = 'none'; // Hide the profile button
        eventText.innerText = 'Please login before participate the events!';
    }
}

function logOutUser() {
    localStorage.setItem('isLoggedIn', 'false'); // Set isLoggedIn to false
    localStorage.removeItem('username'); // Remove the username from localStorage
    window.location.href = "../User/HomePage.html";
}

function logOutEventUser() {
    localStorage.setItem('isLoggedIn', 'false'); // Set isLoggedIn to false
    localStorage.removeItem('username'); // Remove the username from localStorage
    window.location.href = "User/HomePage.html";
}

function logOutAdmin() {
    localStorage.setItem('isLoggedIn', 'false'); // Set isLoggedIn to false
    localStorage.removeItem('username'); // Remove the username from localStorage
    window.location.href = "../User/HomePage.html";
}

function logOutEventAdmin() {
    localStorage.setItem('isLoggedIn', 'false'); // Set isLoggedIn to false
    localStorage.removeItem('username'); // Remove the username from localStorage
    window.location.href = "User/HomePage.html";
}