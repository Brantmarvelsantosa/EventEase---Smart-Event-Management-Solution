function validateEventForm() {
    let eventName = document.querySelector('input[name="event_name"]').value.trim();
    let category = document.querySelector('input[name="category"]').value.trim();
    let description = document.querySelector('textarea[name="description"]').value.trim();
    let eventDate = document.querySelector('input[name="event_date"]').value.trim();
    let maxAttendees = document.querySelector('input[name="max_attendees"]').value.trim();
    let venueId = document.querySelector('select[name="venue_id"]').value.trim();

    if (!eventName) {
        alert("Event Name is required.");
        return false;
    }
    if (!category) {
        alert("Category is required.");
        return false;
    }
    if (!description) {
        alert("Description is required.");
        return false;
    }
    if (!eventDate) {
        alert("Event Date is required.");
        return false;
    }
    if (!maxAttendees || isNaN(maxAttendees) || parseInt(maxAttendees) <= 0) {
        alert("Max Attendees must be a positive number.");
        return false;
    }
    if (!venueId) {
        alert("Please select a Venue.");
        return false;
    }
    return true;
}

function validateVenueForm() {
    let venueName = document.querySelector('input[name="venue_name"]').value.trim();
    let address = document.querySelector('input[name="address"]').value.trim();
    let city = document.querySelector('input[name="city"]').value.trim();
    let capacity = document.querySelector('input[name="capacity"]').value.trim();

    if (!venueName) {
        alert("Venue Name is required.");
        return false;
    }
    if (!address) {
        alert("Address is required.");
        return false;
    }
    if (!city) {
        alert("City is required.");
        return false;
    }
    if (!capacity || isNaN(capacity) || parseInt(capacity) <= 0) {
        alert("Capacity must be a positive number.");
        return false;
    }
    return true;
}

function validateEditEventForm() {
    let eventName = document.querySelector('input[name="event_name"]').value.trim();
    let category = document.querySelector('input[name="category"]').value.trim();
    let description = document.querySelector('input[name="description"]').value.trim(); // <-- input, not textarea
    let eventDate = document.querySelector('input[name="event_date"]').value.trim();
    let maxAttendees = document.querySelector('input[name="max_attendees"]').value.trim();
    let venueId = document.querySelector('select[name="venue_id"]').value.trim();

    if (!eventName) {
        alert("Event Name is required.");
        return false;
    }
    if (!category) {
        alert("Category is required.");
        return false;
    }
    if (!description) {
        alert("Description is required.");
        return false;
    }
    if (!eventDate) {
        alert("Event Date is required.");
        return false;
    }

    let datePattern = /^\d{4}-\d{2}-\d{2}$/;
    if (!datePattern.test(eventDate)) {
        alert("Event Date must be in YYYY-MM-DD format.");
        return false;
    }

    if (!maxAttendees) {
        alert("Max Attendees is required.");
        return false;
    }
    if (isNaN(maxAttendees) || parseInt(maxAttendees) <= 0) {
        alert("Max Attendees must be a positive number.");
        return false;
    }
    if (!venueId) {
        alert("Please select a Venue.");
        return false;
    }

    return true;
}

function validateVenueForm() {
    let venueName = document.querySelector('input[name="venue_name"]').value.trim();
    let address = document.querySelector('input[name="address"]').value.trim();
    let city = document.querySelector('input[name="city"]').value.trim();
    let capacity = document.querySelector('input[name="capacity"]').value.trim();

    if (!venueName) {
        alert("Venue Name is required.");
        return false;
    }
    if (venueName.length < 3) {
        alert("Venue Name must be at least 3 characters long.");
        return false;
    }

    if (!address) {
        alert("Address is required.");
        return false;
    }

    if (!city) {
        alert("City is required.");
        return false;
    }

    if (!capacity) {
        alert("Capacity is required.");
        return false;
    }
    if (isNaN(capacity) || parseInt(capacity) <= 0) {
        alert("Capacity must be a positive number.");
        return false;
    }

    return true;
}

function validateEditVenueForm() {
    const name = document.querySelector('[name="venue_name"]').value.trim();
    const address = document.querySelector('[name="address"]').value.trim();
    const city = document.querySelector('[name="city"]').value.trim();
    const capacity = document.querySelector('[name="capacity"]').value.trim();

    if (name === "") {
        alert("Venue Name cannot be empty.");
        return false;
    }

    if (address === "") {
        alert("Address cannot be empty.");
        return false;
    }

    if (city === "") {
        alert("City cannot be empty.");
        return false;
    }

    if (capacity === "" || isNaN(capacity) || parseInt(capacity) <= 0) {
        alert("Capacity must be a valid positive number.");
        return false;
    }

    return true;
}

