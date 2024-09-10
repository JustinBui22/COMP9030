//This page is for manually entering the sleep time. 
document.getElementById('manualEntry').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const hours = parseInt(document.getElementById('hours').value);
    const minutes = parseInt(document.getElementById('minutes').value);
    } 
);

function closeForm() {
    window.location.href = "Home.html";
}
