// Add event listener for reservation form submission
document.getElementById('reservation-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the form from submitting normally

    // Get form field values
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const guests = document.getElementById('guests').value;
    const requests = document.getElementById('requests').value;

    // Check if required fields are filled
    if (date === '' || time === '' || guests === '') {
        alert('Please fill in all required fields.');
        return;
    }

    // Show confirmation alert
    alert(`Reservation confirmed for ${date} at ${time} for ${guests} guests. Special requests: ${requests}`);
});

// Function to validate password match in registration form
function validateForm() {
    var password = document.getElementById("customerPass").value;
    var confirmPassword = document.getElementById("customerPassValidate").value;
    var errorMessage = document.getElementById("errorMessage");

    // Check if passwords match
    if (password != confirmPassword) {
        errorMessage.innerHTML = "Passwords do not match!";
        errorMessage.style.display = "block";
        return false;
    }
    return true;
}

// Check URL parameters when the window loads
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);

    // Show alert if 'registered' parameter is present in the URL
    if (urlParams.has('registered')) {
        alert("You are now registered! Please login by clicking the login button.");
    }
};
