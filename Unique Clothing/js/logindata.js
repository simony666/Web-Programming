// var usersArray = ["Ali","1111", "MeiLing", "2222"]; 

document.addEventListener('DOMContentLoaded', () => {
    const profileLink = document.getElementById('profileLink');

    // Check if the user is already logged in
    if (localStorage.getItem('isLoggedIn') === 'true') {
        profileLink.href = 'profile.html'; // Redirect to profile.html if logged in
    } else {
        profileLink.href = 'Login_Page.html'; // Keep the original link to Login_Page.html
    }
});



function login(){
    var enterUname = document.getElementById('Namebox').value;
    var enterPassword = document.getElementById('pwbox').value;

    // get data from local storage
    var getUname = localStorage.getItem('username');
    var getPassword= localStorage.getItem('password');

    if(enterUname === getUname){
        if(enterPassword === getPassword){
            alert("Login Successful");
            localStorage.setItem('isLoggedIn', 'true'); // Set isLoggedIn to true
            window.location.href = "Main_Menu.html";
        }
        else{
            alert("Wrong password")
        }
    }else{
        alert("Invalid details input")
    }
}