// Function to check the role from localStorage and load appropriate sidebar and content
function loadContentBasedOnRole() {
    const userData = JSON.parse(localStorage.getItem('userData')); // Get userData from localStorage
    const { role, username } = userData || {}; // Destructure role and username from userData
    const contentFrame = document.getElementById('content-frame');
    const navList = document.getElementById('nav-list');

    let navItems = '';

    if (role === 'therapist') {
        // Therapist-specific links
        navItems = `
            <li><a href="../therapist-module/dashboard/dashboard.html" target="content-frame" data-initial="D">Dashboard</a></li>
            <li><a href="../therapist-module/patient-management/group-management.html" target="content-frame" data-initial="G">Group Management</a></li>
            <li><a href="../therapist-module/patient-management/patient-list.html" target="content-frame" data-initial="P">Patient Management</a></li>
        `;
        // Load therapist dashboard by default
        contentFrame.src = '../therapist-module/dashboard/dashboard.html';
    } else if (role === 'patient') {
        // Patient-specific links
        navItems = `
            <li><a href="../patient-module/dashboard/Dashboard.html" target="content-frame" data-initial="D">Dashboard</a></li>
            <li><a href="../patient-module/dashboard/Patient.html" target="content-frame" data-initial="J">Exercise Tracker</a></li>
            <li><a href="../patient-module/journal-entry/journalentry_page.html" target="content-frame" data-initial="J">Journal Entry</a></li>
            <li><a href="../patient-module/sleep-tracking/Home-Page/Home.html" target="content-frame" data-initial="S">Sleep Tracking</a></li>
            <li><a href="../patient-module/eating-habit/home.html" target="content-frame" data-initial="E">Eating Habit</a></li>
        `;
        // Load patient dashboard or appropriate content
        contentFrame.src = "../patient-module/dashboard/Dashboard.html";
    } else {
        // Default message for unknown roles
        navItems = `<li>No role assigned. Please contact support.</li>`;
        contentFrame.src = '';
    }

    // Insert navigation items into sidebar
    navList.innerHTML = navItems;

    // Set username in the header if available in localStorage
    document.getElementById('username').innerText = username ? username : 'User';
}

// Function to inherit the title of the iframe's content
function updateTitle() {
    const iframe = document.getElementById('content-frame');
    iframe.onload = function () {
        const iframeTitle = iframe.contentWindow.document.title;
        document.title = iframeTitle;
    };
}

// Toggle the sidebar collapse
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
}

// Call the functions on page load
window.onload = function () {
    loadContentBasedOnRole();
    updateTitle();
};