// script.js

document.getElementById('reservation-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const guests = document.getElementById('guests').value;
    const requests = document.getElementById('requests').value;

    if (date === '' || time === '' || guests === '') {
        alert('Please fill in all required fields.');
        return;
    }

    alert(`Reservation confirmed for ${date} at ${time} for ${guests} guests. Special requests: ${requests}`);
});
