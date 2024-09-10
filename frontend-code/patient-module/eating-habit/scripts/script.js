function displayNumber() {
    // Get the value from the input box
    const numberInput = document.getElementById('number-input').value;
    // Display the value in the span element
    document.getElementById('display-number').textContent = numberInput;
}