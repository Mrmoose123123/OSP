document.addEventListener('DOMContentLoaded', function() {
    async function fetchData() {
        try {
            const response = await fetch('api.php');
            const data = await response.json();

            function populateTable(tableId, dataArray) {
                const tableBody = document.getElementById(tableId).querySelector('tbody');
                tableBody.innerHTML = '';

                dataArray.forEach(item => {
                    const row = tableBody.insertRow();
                    for (const key in item) {
                        let cellValue = item[key];
                        if (cellValue === null || cellValue === undefined) {
                            cellValue = '';
                        }
                        const cell = row.insertCell();
                        cell.textContent = cellValue;
                    }
                });
            }

            populateTable('usersTable', data.users);
            populateTable('bookingsTable', data.bookings);
            populateTable('bookingSlotsTable', data.booking_slots);
            populateTable('bookedTicketsTable', data.booked_tickets);
            populateTable('ticketsTable', data.tickets);

        } catch (error) {
            console.error('Error fetching data:', error);
            document.body.innerHTML = '<p>Error loading data.  Check the console for details.</p>';
        }
    }
    fetchData();

    // Your provided JavaScript (for date/class selection) starts here:

    // Get references to HTML elements using their IDs.
    const dataSelect = document.getElementById('date'); // Dropdown for selecting a date.
    const classSelect = document.getElementById('class'); // Dropdown for selecting a class.
    const dataHolder = document.getElementById('data-holder'); // Hidden element storing initial data.

    // Check if elements exist before proceeding (handle potential null values)
    if (dataSelect && classSelect && dataHolder) {

        // Retrieve data from the data-holder element's 'data-dates' and 'data-defaultClasses' attributes, 
        // parsing them from JSON strings into JavaScript objects.
        const dates = JSON.parse(dataHolder.dataset.dates); // Array of date objects.
        const defaultClasses = JSON.parse(dataHolder.dataset.defaultClasses); // Array of class objects for initial display.

        // Populate the date selection dropdown.
        dates.forEach(function(date) {
            const option = document.createElement('option'); // Create a new option element for each date.
            option.value = date.DayOfWeek; // Set the option's value to the day of the week.
            option.textContent = `${date.date} - ${date.DayOfWeek}`; // Set the display text for the option (e.g., "2023-10-26 - Thursday").
            dataSelect.appendChild(option); // Add the option to the date selection dropdown.
        });


        // Function to update the class options based on the provided class data.
        function updateClassOptions(classes) {
            classSelect.innerHTML = ""; // Clear any existing options in the class selection dropdown.
            if (classes.length > 0) { // If there are classes available.
                classes.forEach(function(classItem) {
                    const option = document.createElement('option'); // Create a new option element for each class.
                    option.value = classItem.class_id; // Set the option's value to the class ID.
                    const time = classItem.class_time.substring(0, 5); // Extract the time from the class time string
                    option.textContent = `${classItem.class_name} - ${classItem.instructor_name} - ${time}`; // Set the display text for the option including class name, instructor, and time.
                    classSelect.appendChild(option); // Add the option to the class selection dropdown.

                });
            } else { // If no classes are available
                const option = document.createElement('option'); // Create a new option element to display 'No classes available'
                option.textContent = 'No classes available'; // Set the option's display text to 'No classes available'
                classSelect.appendChild(option); // Add the option to the class selection dropdown.
            }
        }

        // Initially populate the class dropdown with the default classes.
        updateClassOptions(defaultClasses);

        // Function to clear session data and remove success messages (if any).
        function clearSessionData() {
            sessionStorage.removeItem('booking_success_message'); // Remove the success message from session storage.
            const booking_success_message = document.querySelector('.success-message'); // Select the HTML element with class 'success-message'
            if(booking_success_message){ // Check if the success message exists
                booking_success_message.remove(); // Remove the success message from the page.
            }
        }

        // Event listener for when the selected date in the dropdown changes.
        dataSelect.addEventListener('change', function() {
            const selectDay = this.value; // Get the selected day of the week.
            clearSessionData(); // Clear session data and success messages.
            fetchClasses(selectDay); // Fetch classes based on the selected day.
            classSelect.value = ""; // Reset the class dropdown to its default (empty) value.
        });


        // Function to fetch updated class data based on the selected day.
        function fetchClasses(selectDay){
            fetch('../PHP/get_updated_classes.php', { // Fetch data from the PHP endpoint.
                method: 'POST' , // Use the POST method
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded', // Set the content type for sending form data.
                },
                body: 'dayOfWeek=' + encodeURIComponent(selectDay), // Send the selected day in the request body.
            })
                .then(response => response.json()) // Parse the JSON response.
                .then(data => {
                    updateClassOptions(data); // Update the class dropdown with the new class data.
                });
        }
    } else {
        console.error("Required elements (dataSelect, classSelect, dataHolder) not found in the DOM.");
    }
});