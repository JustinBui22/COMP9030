// Function to check the role from localStorage and load appropriate sidebar and content
function loadContentBasedOnRole() {
	const { role } = JSON.parse(localStorage.getItem('userData')); // Get userData from localStorage
	const contentFrame = document.getElementById('content-frame');
	const navList = document.getElementById('nav-list');

	let navItems = '';

	if (role === 'therapist') {
		// Therapist-specific links
		navItems = `
            <li><a href="../therapist-module/dashboard/dashboard.html" target="content-frame">Dashboard</a></li>
            <li><a href="group-management.html" target="content-frame">Group Management</a></li>
            <li><a href="patient-management.html" target="content-frame">Patient Management</a></li>
        `;
		// Load therapist dashboard by default
		contentFrame.src = '../therapist-module/dashboard/dashboard.html';
	} else if (role === 'patient') {
		// Patient-specific links
		navItems = `
            <li><a href="journal-entry.html" target="content-frame">Journal Entry</a></li>
            <li><a href="eating-habit.html" target="content-frame">Eating Habit</a></li>
        `;
		// Load patient dashboard or appropriate content
		contentFrame.src = 'journal-entry.html';
	} else {
		// // Default message for unknown roles
		// navItems = `<li>No role assigned. Please contact support.</li>`;
		// contentFrame.src = '';

		// Therapist-specific links
		navItems = `
            <li><a href="../therapist-module/dashboard/dashboard.html" target="content-frame">Dashboard</a></li>
            <li><a href="group-management.html" target="content-frame">Group Management</a></li>
            <li><a href="patient-management.html" target="content-frame">Patient Management</a></li>
        `;
		// Load therapist dashboard by default
		contentFrame.src = '../therapist-module/dashboard/dashboard.html';
	}

	// Insert navigation items into sidebar
	navList.innerHTML = navItems;

	// Optionally, set username in the header if stored
	const username = localStorage.getItem('username');
	document.getElementById('username').innerText = username
		? username
		: 'User';
}

// Call the function on page load
window.onload = loadContentBasedOnRole;
