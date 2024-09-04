const datePicker = document.getElementById('datePicker');
        const selectedDate = document.getElementById('selectedDate');
        const journalText = document.getElementById('journalText');
        const moodTracker = document.getElementById('moodTracker');
        const moodNotes = document.getElementById('moodNotes');

        // Load saved data
        window.onload = function() {
            datePicker.value = localStorage.getItem('date') || new Date().toISOString().split('T')[0];
            selectedDate.textContent = "Today : " + datePicker.value;

            journalText.value = localStorage.getItem('journal') || '';
            moodNotes.value = localStorage.getItem('moodNotes') || '';
            const savedMood = localStorage.getItem('mood');
            if (savedMood) {
                document.querySelector(`img[data-mood="${savedMood}"]`).classList.add('selected');
            }
        };

        // Save date
        datePicker.addEventListener('change', function() {
            localStorage.setItem('date', datePicker.value);
            selectedDate.textContent = "Today : " + datePicker.value;
        });

        // Save journal entry
        journalText.addEventListener('input', function() {
            localStorage.setItem('journal', journalText.value);
        });

        // Save mood
        moodTracker.addEventListener('click', function(e) {
            if (e.target.tagName === 'IMG') {
                document.querySelectorAll('.mood-tracker img').forEach(img => img.classList.remove('selected'));
                e.target.classList.add('selected');
                localStorage.setItem('mood', e.target.dataset.mood);
            }
        });

        // Save mood notes
        moodNotes.addEventListener('input', function() {
            localStorage.setItem('moodNotes', moodNotes.value);
        });