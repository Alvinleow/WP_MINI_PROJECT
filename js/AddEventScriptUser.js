// Retrieve event data from cookies
function getEventDataFromCookies() {
    var cookieName = 'eventData=';
    var cookies = document.cookie.split(';');
  
    for (var i = 0; i < cookies.length; i++) {
      var cookie = cookies[i];
  
      while (cookie.charAt(0) === ' ') {
        cookie = cookie.substring(1);
      }
  
      if (cookie.indexOf(cookieName) === 0) {
        var eventDataString = cookie.substring(cookieName.length);
        try {
          var eventDataArray = JSON.parse(decodeURIComponent(eventDataString));
          if (Array.isArray(eventDataArray)) {
            return eventDataArray;
          }
        } catch (error) {
          return [];
        }
      }
    }
  
    return [];
  }
  
  // Populate event blocks on the page
  function populateEventBlocks() {
    var eventDataArray = getEventDataFromCookies();
    var eventContainer = document.getElementById('eventContainer');
    eventContainer.innerHTML = '';
  
    if (eventDataArray.length === 0) {
      eventContainer.innerHTML = '<p>No upcoming events.</p>';
      return;
    }
  
    eventDataArray.forEach(eventData => {
      var eventBlock = createEventBlock(eventData);
      eventContainer.appendChild(eventBlock);
    });
  }
  
  // Create a new event block element
  function createEventBlock(eventData) {
    var eventBlock = document.createElement('div');
    eventBlock.className = 'event-block';
  
    var eventHtml = `
      <h3>${eventData.name}</h3>
      <p>${eventData.description}</p>
      <p>Date: ${eventData.date}</p>
      <p>Start time: ${eventData.startTime}</p>
      <p>End time: ${eventData.endTime}</p>
      <button class="participate" type="button">Participate</button>
    `;
  
    eventBlock.innerHTML = eventHtml;
    return eventBlock;
  }
  
  // Call the function to populate event blocks on page load
  document.addEventListener('DOMContentLoaded', populateEventBlocks);

  document.addEventListener("DOMContentLoaded", function() {
    getIsLoggedIn();
  
    // Add event listener to Participate buttons
    document.addEventListener("click", function(event) {
      if (event.target.classList.contains("participate")) {
        var eventName = event.target.parentNode.querySelector("h3").textContent;
        console.log("Participate button clicked for event:", eventName);
  
        // Display event name in alert message
        alert("Participate Successfully.");
        // Send an AJAX request to insert the user's full name into the corresponding event table
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "http://localhost/utm/WP_MINI_PROJECT/php/insertParticipant.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
          }
        };
        xhr.send("eventName=" + encodeURIComponent(eventName));
      }
    });
  
    // Call the function to populate event blocks on page load
    populateEventBlocks();
  });
  
  
  