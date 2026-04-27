// ====================================
// TorqueTrail - JavaScript Form Validation
// ====================================

// ---- Helper: Show error message ----
function showError(fieldId, message) {
  var el = document.getElementById(fieldId + "Error");
  if (el) {
    el.textContent = message;
  }
  var input = document.getElementById(fieldId);
  if (input) {
    input.style.borderColor = "red";
  }
}

// ---- Helper: Clear error message ----
function clearError(fieldId) {
  var el = document.getElementById(fieldId + "Error");
  if (el) {
    el.textContent = "";
  }
  var input = document.getElementById(fieldId);
  if (input) {
    input.style.borderColor = "#ddd";
  }
}

// ====================================
// Booking Form Validation
// ====================================
function validateForm() {
  var isValid = true;

  // Clear all previous errors
  var fields = ["name", "email", "phone", "car", "pickup", "return"];
  fields.forEach(function(f) { clearError(f); });

  // 1. Validate Name
  var name = document.getElementById("name").value.trim();
  if (name === "") {
    showError("name", "Name is required.");
    isValid = false;
  } else if (name.length < 3) {
    showError("name", "Name must be at least 3 characters.");
    isValid = false;
  }

  // 2. Validate Email
  var email = document.getElementById("email").value.trim();
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email === "") {
    showError("email", "Email is required.");
    isValid = false;
  } else if (!emailRegex.test(email)) {
    showError("email", "Enter a valid email address.");
    isValid = false;
  }

  // 3. Validate Phone
  var phone = document.getElementById("phone").value.trim();
  var phoneRegex = /^[6-9]\d{9}$/;
  if (phone === "") {
    showError("phone", "Phone number is required.");
    isValid = false;
  } else if (!phoneRegex.test(phone)) {
    showError("phone", "Enter a valid 10-digit Indian mobile number.");
    isValid = false;
  }

  // 4. Validate Car Selection
  var car = document.getElementById("car").value;
  if (car === "" || car === null) {
    showError("car", "Please select a car.");
    isValid = false;
  }

  // 5. Validate Pickup Date
  var pickup = document.getElementById("pickup").value;
  if (pickup === "") {
    showError("pickup", "Pickup date is required.");
    isValid = false;
  } else {
    var today = new Date();
    today.setHours(0, 0, 0, 0);
    var pickupDate = new Date(pickup);
    if (pickupDate < today) {
      showError("pickup", "Pickup date cannot be in the past.");
      isValid = false;
    }
  }

  // 6. Validate Return Date
  var returnDate = document.getElementById("return").value;
  if (returnDate === "") {
    showError("return", "Return date is required.");
    isValid = false;
  } else if (pickup !== "" && returnDate <= pickup) {
    showError("return", "Return date must be after pickup date.");
    isValid = false;
  }

  return isValid;
}

// ====================================
// Contact Form Validation
// ====================================
function validateContactForm() {
  var isValid = true;

  // Clear previous errors
  ["cname", "cemail", "message"].forEach(function(f) { clearError(f); });

  // 1. Validate Name
  var cname = document.getElementById("cname").value.trim();
  if (cname === "") {
    showError("cname", "Name is required.");
    isValid = false;
  }

  // 2. Validate Email
  var cemail = document.getElementById("cemail").value.trim();
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (cemail === "") {
    showError("cemail", "Email is required.");
    isValid = false;
  } else if (!emailRegex.test(cemail)) {
    showError("cemail", "Enter a valid email address.");
    isValid = false;
  }

  // 3. Validate Message
  var message = document.getElementById("message").value.trim();
  if (message === "") {
    showError("message", "Message cannot be empty.");
    isValid = false;
  } else if (message.length < 10) {
    showError("message", "Message must be at least 10 characters.");
    isValid = false;
  }

  return isValid;
}

// ====================================
// Live validation on input (optional)
// ====================================
document.addEventListener("DOMContentLoaded", function () {

  // Live phone validation
  var phoneInput = document.getElementById("phone");
  if (phoneInput) {
    phoneInput.addEventListener("input", function () {
      // Allow only digits
      this.value = this.value.replace(/[^0-9]/g, "");
    });
  }

});
