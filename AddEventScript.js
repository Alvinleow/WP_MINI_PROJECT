var eventContainer = document.getElementById('eventContainer');

function addEvent() {
    // Prompt the admin for event details
    var eventName = prompt('Enter event name:');
    var eventDescription = prompt('Enter event description:');
    var eventStartTime = prompt('Enter event start time:');

    // Create a new event block element
    var eventBlock = document.createElement('div');
    eventBlock.className = 'event-block';

    // Create the HTML structure for the event block
    eventBlock.innerHTML = `
        <h3>${eventName}</h3>
        <p>${eventDescription}</p>
        <p>Start time: ${eventStartTime}</p>
        <button onclick="removeEvent(this.parentNode)">Remove</button>
    `;

    // Append the event block to the container
    eventContainer.appendChild(eventBlock);

    // Save event data in cookies
    saveEventData(eventName, eventDescription, eventStartTime);
}

function removeEvent(eventBlock) {
    // Remove the specified event block
    eventContainer.removeChild(eventBlock);

    // Update event data in cookies after removing an event
    updateEventDataInCookies();
}

function saveEventData(eventName, eventDescription, eventStartTime) {
    // Create a JSON object to store event data
    var eventData = {
        name: eventName,
        description: eventDescription,
        startTime: eventStartTime
    };

    // Get the existing event data from cookies
    var existingEventData = getEventDataFromCookies();

    // Add the new event data to the existing data array
    existingEventData.push(eventData);

    // Convert the event data to a JSON string
    var eventDataString = JSON.stringify(existingEventData);

    // Store the event data in a cookie
    document.cookie = 'eventData=' + encodeURIComponent(eventDataString);
}

function updateEventDataInCookies() {
    // Retrieve all event blocks
    var eventBlocks = document.getElementsByClassName('event-block');

    // Create an array to store event data
    var eventDataArray = [];

    // Loop through each event block and extract event data
    for (var i = 0; i < eventBlocks.length; i++) {
        var eventBlock = eventBlocks[i];

        // Extract event details from the event block
        var eventName = eventBlock.querySelector('h3').textContent;
        var eventDescription = eventBlock.querySelector('p:nth-of-type(1)').textContent;
        var eventStartTime = eventBlock.querySelector('p:nth-of-type(2)').textContent.split(':')[1].trim();

        // Create an object to store the event data
        var eventData = {
            name: eventName,
            description: eventDescription,
            startTime: eventStartTime
        };

        // Add the event data object to the array
        eventDataArray.push(eventData);
    }

    // Convert the event data array to a JSON string
    var eventDataArrayString = JSON.stringify(eventDataArray);

    // Store the event data in a cookie
    document.cookie = 'eventData=' + encodeURIComponent(eventDataArrayString);
}

function getEventDataFromCookies() {
    var cookieName = 'eventData=';
    var cookies = document.cookie.split(';');

    // Loop through all cookies to find the event data cookie
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];

        // Trim leading whitespace if present
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1);
        }

        // Check if the cookie starts with the event data name
        if (cookie.indexOf(cookieName) === 0) {
            var eventDataString = cookie.substring(cookieName.length);

            // Attempt to parse the event data as an array
            try {
                var eventDataArray = JSON.parse(decodeURIComponent(eventDataString));

                // If the event data is an array, return it
                if (Array.isArray(eventDataArray)) {
                    return eventDataArray;
                }
            } catch (error) {
                // Parsing error occurred, try parsing as a single event data object
                try {
                    var eventDataObject = JSON.parse(decodeURIComponent(eventDataString));

                    // If the event data is an object, convert it to an array and return
                    if (typeof eventDataObject === 'object' && eventDataObject !== null) {
                        return [eventDataObject];
                    }
                } catch (error) {
                    // Invalid event data format, return an empty array
                    return [];
                }
            }
        }
    }

    // No event data cookie found, return an empty array
    return [];
}

// Call the function to retrieve event data from cookies on page load
populateEventBlocks();

function populateEventBlocks() {
    var eventDataArray = getEventDataFromCookies();

    // Loop through the event data array and create event blocks
    eventDataArray.forEach(eventData => {
        // Create a new event block element
        var eventBlock = document.createElement('div');
        eventBlock.className = 'event-block';

        // Create the HTML structure for the event block
        eventBlock.innerHTML = `
            <h3>${eventData.name}</h3>
            <p>${eventData.description}</p>
            <p>Start time: ${eventData.startTime}</p>
            <button onclick="removeEvent(this.parentNode)">Remove</button>
        `;

        // Append the event block to the container
        eventContainer.appendChild(eventBlock);
    });
}
