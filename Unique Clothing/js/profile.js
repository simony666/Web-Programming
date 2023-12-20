
document.addEventListener('DOMContentLoaded', () => {
    const profileLink = document.getElementById('profileLink');

    // Check if the user is already logged in
    if (localStorage.getItem('isLoggedIn') === 'true') {
            profileLink.href = 'profile.html'; // Redirect to profile.html  if logged in
            // Fetch user data from local storage
            console.log("not working.....")
            const username = localStorage.getItem('username');
            const email = localStorage.getItem('email');
            const birthday = localStorage.getItem('birthday');
            const gender = localStorage.getItem('gender');
            const address = localStorage.getItem('address');
            const city = localStorage.getItem('city');
            const state = localStorage.getItem('state');
            const postcode = localStorage.getItem('postcode');

            // Update the content of the corresponding elements
            document.getElementById('username').innerHTML = `<span style="font-weight: bold;">Name: </span><span>${username}</span>`;
            document.getElementById('email').innerHTML = `<span style="font-weight: bold;">Email:</span> <span>${email}</span>`;
            document.getElementById('gender').innerHTML = `<span style="font-weight: bold;">Gender: </span> <span>${gender}</span>`;

            document.getElementById('bday').innerHTML = `<span style="font-weight: bold;">Birthday:</span><span>${birthday}</span>`;

            document.getElementById('deliveryAddress').innerHTML = `<span style="font-weight: bold;">Address:</span><span>${address}, ${city}, ${state}, ${postcode}</span>
            `
        } else {
            profileLink.href = 'Login_Page.html'; // Keep the original link  to Login_Page.html
        }
    });

    function signOut(){
        // Set 'isLoggedIn' to false before removing it
        localStorage.setItem('isLoggedIn', 'false');

        // Optionally, you can redirect the user to the login page or any other desired page after sign-out
        window.location.href = 'Login_Page.html';
    }