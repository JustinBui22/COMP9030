//Open the form
function openEntryForm() {
    document.getElementById("entryFormContainer").style.display = "flex";
}

//Adding entries into the form
function addEntry() {
    const date = document.getElementById("date").value;
    const hours = document.getElementById("hours").value;
    const minutes = document.getElementById("minutes").value;

    if (date && hours && minutes) {
        const entry = document.createElement("p");
        entry.innerHTML = `<span>${date}</span>
        <span>${hours} hours</span>
        <span>${minutes} minutes</span>`;
        document.querySelector(".entries").appendChild(entry);
        closeEntryForm();
    } else {
        alert("Please fill in all fields.");
    }
}

function closeDiary() {
    window.location.href = "Home.html";
}

//Close the form
function closeEntryForm() {
    document.getElementById("entryFormContainer").style.display = "none";
}
