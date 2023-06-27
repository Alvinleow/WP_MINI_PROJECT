var eventContainer = document.getElementById('eventContainer');

function addEvent() {
  // Prompt the admin for event details
  var eventName = prompt('Enter event name:');
  var eventDescription = prompt('Enter event description:');
  var eventDate = '';
  var eventStartTime = '';
  var eventEndTime = '';

  // Keep prompting for event date until a valid format is entered
  while (!isValidDateFormat(eventDate)) {
    eventDate = prompt('Enter event date (YYYY-MM-DD):');

    if (!isValidDateFormat(eventDate)) {
      alert('Invalid date format! Please enter the date in YYYY-MM-DD format.');
    }
  }

  // Keep prompting for event start time until a valid format is entered
  while (!isValidTimeFormat(eventStartTime)) {
    eventStartTime = prompt('Enter event start time (format: HH:MM, 24-hour):');

    if (!isValidTimeFormat(eventStartTime)) {
      alert('Invalid time format! Please enter the time in HH:MM format (24-hour).');
    }
  }

  // Keep prompting for event end time until a valid format is entered
  while (!isValidTimeFormat(eventEndTime)) {
    eventEndTime = prompt('Enter event end time (format: HH:MM, 24-hour):');

    if (!isValidTimeFormat(eventEndTime)) {
      alert('Invalid time format! Please enter the time in HH:MM format (24-hour).');
    }
  }

  // Create a new event block element
  var eventBlock = document.createElement('div');
  eventBlock.className = 'event-block new-event';

  // Create the HTML structure for the event block
  eventBlock.innerHTML = `
      <h3>${eventName}</h3>
      <p>${eventDescription}</p>
      <p>Date: ${eventDate}</p>
      <p>Start time: ${eventStartTime}</p>
      <p>End time: ${eventEndTime}</p>
      <button onclick="removeEvent(this.parentNode)">Remove</button>
  `;

  // Append the event block to the container
  eventContainer.appendChild(eventBlock);

  // Save event data in cookies
  saveEventData(eventName, eventDescription, eventDate, eventStartTime, eventEndTime);
}

function removeEvent(eventBlock) {
    // Remove the specified event block
    eventBlock.remove();
  
    // Update event data in cookies after removing an event
    updateEventDataInCookies();
  }
  
function saveEventData(eventName, eventDescription, eventDate, eventStartTime, eventEndTime) {
    // Create a JSON object to store event data
    var eventData = {
        name: eventName,
        description: eventDescription,
        date: eventDate,
        startTime: eventStartTime,
        endTime: eventEndTime
    };

    // Get the existing event data from cookies
    var existingEventData = getEventDataFromCookies();

    // Add the new event data to the existing data array
    existingEventData.push(eventData);

    // Convert the event data to a JSON string
    var eventDataString = JSON.stringify(existingEventData);

    // Store the event data in a cookie
    document.cookie = 'eventData=' + encodeURIComponent(eventDataString);

    // Send an AJAX request to save the event name in the database
    var xhr1 = new XMLHttpRequest();
xhr1.open("POST", "http://localhost/utm/WP_MINI_PROJECT/php/saveEventname.php", true);
xhr1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xhr1.onreadystatechange = function() {
  if (xhr1.readyState === 4 && xhr1.status === 200) {
    console.log(xhr1.responseText);

    // Once the first request is completed, make the second request
    var xhr2 = new XMLHttpRequest();
    xhr2.open("POST", "http://localhost/utm/WP_MINI_PROJECT/php/createEventTables.php", true);
    xhr2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr2.onreadystatechange = function() {
      if (xhr2.readyState === 4 && xhr2.status === 200) {
        console.log(xhr2.responseText);
      }
    };
    xhr2.send("eventName=" + encodeURIComponent(eventName)); // Replace with appropriate data
  }
};
xhr1.send("eventName=" + encodeURIComponent(eventName));

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
      var eventDate = eventBlock.querySelector('p:nth-of-type(2)').textContent.split(':')[1].trim();
      var eventStartTime = eventBlock.querySelector('p:nth-of-type(3)').textContent.split(':')[1].trim();
      var eventEndTime = eventBlock.querySelector('p:nth-of-type(4)').textContent.split(':')[1].trim();
  
      // Create an object to store the event data
      var eventData = {
        name: eventName,
        description: eventDescription,
        date: eventDate,
        startTime: eventStartTime,
        endTime: eventEndTime
      };
  
      // Add the event data object to the array
      eventDataArray.push(eventData);
    }
  
    // Convert the event data array to a JSON string
    var eventDataArrayString = JSON.stringify(eventDataArray);
  
    // Store the event data in a cookie
    document.cookie = 'eventData=' + encodeURIComponent(eventDataArrayString);
  
    // Check if there are no more event blocks, clear the cookie
    if (eventDataArray.length === 0) {
      document.cookie = 'eventData=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }
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
  
    // Clear the eventContainer before populating it
    eventContainer.innerHTML = '';
  
    // Loop through the event data array and create event blocks
    eventDataArray.forEach(eventData => {
      // Create a new event block element
      var eventBlock = document.createElement('div');
      eventBlock.className = 'event-block new-event';
  
      // Create the HTML structure for the event block
      eventBlock.innerHTML = `
  <h3>${eventData.name}</h3>
  <p>${eventData.description}</p>
  <p>Date: ${eventData.date}</p>
  <p>Start time: ${eventData.startTime}</p>
  <p>End time: ${eventData.endTime}</p>
  <div class="align-center">
    <button onclick="removeEvent(this.parentNode.parentNode)">Remove</button>
  </div>
`;

  
      // Append the event block to the container
      eventContainer.appendChild(eventBlock);
    });
  }
  

function isValidTimeFormat(timeString) {
    // Regular expression to validate time format: HH:MM (24-hour)
    var timeFormatRegex = /^([01]\d|2[0-3]):[0-5]\d$/;

    return timeFormatRegex.test(timeString);
}

function isValidDateFormat(dateString) {
    // Split the date string into year, month, and day components
    var dateComponents = dateString.split('-');
    var year = parseInt(dateComponents[0], 10);
    var month = parseInt(dateComponents[1], 10);
    var day = parseInt(dateComponents[2], 10);
  
    // Validate year, month, and day values
    if (
      Number.isNaN(year) ||
      Number.isNaN(month) ||
      Number.isNaN(day) ||
      year < 0 ||
      month < 1 || month > 12 ||
      day < 1 || day > 31
    ) {
      return false;
    }
  
    // Create a Date object and check if the values match
    var date = new Date(year, month - 1, day);
    return (
      date.getFullYear() === year &&
      date.getMonth() === month - 1 &&
      date.getDate() === day
    );
  }
  
