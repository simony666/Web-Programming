function showpw() {
    var inputpw = document.getElementById("uPassword");
    if (inputpw.type === "password") {
        inputpw.type = "text";
    } else {
        inputpw.type = "password";
    }
};

function storeUserData(username, email, password, gender, birthday, address,city, postcode) {
    // Perform validation to check if any of the values are empty
    if (username.trim() !== '') {
      localStorage.setItem('username', username);
    }
    if (email.trim() !== '') {
      localStorage.setItem('email', email);
    }
    if (password.trim() !== '') {
      localStorage.setItem('password', password);
    }
    localStorage.setItem('gender', gender);
    localStorage.setItem('birthday', birthday);
    localStorage.setItem('address', address);
    localStorage.setItem('city', city);
    localStorage.setItem('postcode', postcode);

    // Optionally, you can redirect to another page after successful registration
    window.location.href = 'Login_Page.html';
};

const setError = (element, message) => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = message;
    inputControl.classList.add('error');
    inputControl.classList.remove('success');
};

const setSuccess = element => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = '';
    inputControl.classList.add('success');
    inputControl.classList.remove('error');
};

const isValidEmail = email => {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
};

function calculateAge(birthDate) {
    const today = new Date();
    const birthDateArray = birthDate.split("-");
    const userBirthDate = new Date(birthDateArray[0], birthDateArray[1] - 1,    birthDateArray[2]);

    let age = today.getFullYear() - userBirthDate.getFullYear();
    const monthDiff = today.getMonth() - userBirthDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < userBirthDate.   getDate())) {
      age--;
    }

    return age;
};

const getSelectedGender = () => {
    const genderButtons = document.getElementsByName('gender');
    let selectedGender = '';

    // Loop through the radio buttons to find the selected one
    genderButtons.forEach((button) => {
        if (button.checked) {
            selectedGender = button.value;
        }
    });

    return selectedGender;
};


// Get the form element and attach the event listener
const form = document.getElementById('signup');
form.addEventListener('submit', e => {
    e.preventDefault();
    validateInputs();
});

function validateInputs() {
    // Retrieve the values from the form elements
    const username = document.getElementById('uName');
    const email = document.getElementById('uEmail');
    const password = document.getElementById('uPassword');
    const gender = document.getElementById('gender');
    const birthday = document.getElementById('bday');

    const usernameValue = username.value.trim();
    const emailValue = email.value.trim();
    const dateValue = document.getElementById('bday').value;

    // Retrieve the values of address, city, and postcode input fields
    const address = document.getElementById('address');
    const city = document.getElementById('city');
    const postcode = document.getElementById('postcode');

    const addressValue = address.value.trim();
    const cityValue = city.value.trim();
    const postcodeValue = postcode.value.trim();

    if (usernameValue === '') {
        setError(username, 'Username is required');
    } else {
        setSuccess(username);
    };

    if (emailValue === '') {
        setError(email, 'Email is required');
    } else if (!isValidEmail(emailValue)) {
        setError(email, 'Provide a valid email address');
    } else {
        setSuccess(email);
    };

    if (passwordValue === '') {
        setError(password, 'Password is required');
    } else if (passwordValue.length < 8) {
        setError(password, 'Password must be at least 8 characters.');
    } else {
        setSuccess(password);
    };

    if (addressValue === '') {
        setError(document.getElementById('address'), 'address is required');
    } else {
        setSuccess(document.getElementById('address'));
    };

    if (cityValue === '') {
        setError(city, 'Username is required');
    } else {
        setSuccess(city);
    };

    if (postcodeValue === '') {
        setError(postcode, 'Username is required');
    } else {
        setSuccess(postcode);
    };


    // Check if the user has selected a date
    if (dateValue === '') {
      setError(document.getElementById('bday'), 'Please select a date');
    } else {
    //   setSuccess(document.getElementById('bday'));
    
      // Calculate the age of the user
      const age = calculateAge(dateValue);
    
      // Check if the user is under 18 years old
      if (age < 18) {
        setError(document.getElementById('bday'), 'You must be at least 18 years old to register.');
        return; // Return early, no need to proceed further.
      } else {
        setSuccess(document.getElementById('bday'));
      }
    };

    // Get the selected gender
    const selectedGender = getSelectedGender();

    // Check if the user agrees to the policy
    const policyCheckbox = document.getElementById('policyCheckbox');
    if (!policyCheckbox.checked) {
      setError(policyCheckbox, 'You must agree to the policy.');
      return; // Return early, no need to proceed further.
    } else {
      setSuccess(policyCheckbox);
    }


    // If there are no validation errors, store the values into the local storage
    if (!document.querySelectorAll('.input-control.error').length) {
    storeUserData(usernameValue, emailValue, passwordValue, selectedGender,dateValue,addressValue,cityValue,postcodeValue);

    // Redirect to Login_Page.html after successful registration
    window.location.href = 'Login_Page.html';
};
};