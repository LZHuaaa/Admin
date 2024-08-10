$(document).ready(function () {
  // Function to attach event handlers
  function attachFormHandlers() {
    // Remove existing handlers to prevent multiple bindings
    $(document).off("submit", "#addAdminForm").on("submit", "#addAdminForm", function (event) {
      event.preventDefault();

      var password = $("#password").val();
      var confirmPassword = $("#confirmPassword").val();

      if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return;
      }

      var formData = $(this).serialize();

      $.ajax({
        url: "addAdmin.php",
        type: "POST",
        data: formData,
        success: function (response) {
          alert(response);
          $("#edit-form-container").empty();
          loadAdminList();
        },
        error: function () {
          alert("Error adding admin.");
        },
      });
    });

    $(document).off("submit", "#editAdminForm").on("submit", "#editAdminForm", function (event) {
      event.preventDefault();
      var confirmUpdate = confirm("Are you sure you want to update?");
      if (confirmUpdate) {
        var formData = $(this).serialize();

        $.ajax({
          url: "updateAdmin.php",
          type: "POST",
          data: formData,
          success: function (response) {
            if (response.trim() === 'success') {
              alert("Updated Successfully!");
            } else {
              alert("Error updating data.");
            }
            $("#edit-form-container").empty();
            loadAdminList();
          },
          error: function () {
            alert("Error updating data.");
          },
        });
      }
    });

    $(document).off("submit", "#resetPasswordForm").on("submit", "#resetPasswordForm", function (event) {
      event.preventDefault();

      var newPassword = $("#newPassword").val();
      var confirmPassword = $("#confirmPassword").val();

      if (newPassword !== confirmPassword) {
        alert("Passwords do not match.");
        return;
      }

      var formData = $(this).serialize();

      $.ajax({
        url: "resetPassword.php",
        type: "POST",
        data: formData,
        success: function (response) {
          alert(response);
          $("#edit-form-container").empty();
          loadAdminList();
        },
        error: function () {
          alert("Error resetting password.");
        },
      });
    });
  }

  // Add Admin Form
  $(document).on("click", ".add-btn", function () {
    $.ajax({
      url: "../addAdminForm.php",
      type: "GET",
      success: function (response) {
        $("#edit-form-container").html(response);
        attachFormHandlers(); // Attach handlers after loading the form
      },
      error: function () {
        alert("Error loading the adding form.");
      },
    });
  });

  // Edit Admin Form
  $(document).on("click", ".edit-btn", function () {
    var adminId = $(this).data("id");

    $.ajax({
      url: "../editAdmin.php",
      type: "GET",
      data: { id: adminId },
      success: function (response) {
        $("#edit-form-container").html(response);
        attachFormHandlers(); // Attach handlers after loading the form
      },
      error: function () {
        alert("Error loading the edit form.");
      },
    });
  });

  // Close Button for Forms
  $(document).on("click", "#closeBtn", function () {
    $("#edit-form-container").empty();
  });

  // Delete Admin
  $(document).on("click", ".delete-btn", function () {
    var adminId = $(this).data("id");
    var confirmDelete = confirm("Are you sure you want to delete this admin?");

    if (confirmDelete) {
      $.ajax({
        url: "deleteAdmin.php",
        type: "POST",
        data: { id: adminId },
        success: function (response) {
          alert("Deleted Successfully!");
          loadAdminList();
        },
        error: function () {
          alert("Error deleting data.");
        },
      });
    }
  });

  // Reset Password Form
  $(document).on("click", ".reset-btn", function () {
    var adminId = $(this).data("id");
    var username = $(this).data("username");

    $.ajax({
      url: "../resetPasswordForm.php",
      type: "GET",
      data: { id: adminId, username: username },
      success: function (response) {
        $("#edit-form-container").html(response);
        attachFormHandlers(); // Attach handlers after loading the form
      },
      error: function () {
        alert("Error loading the reset password form.");
      },
    });
  });

  // Function to reload the admin list
  function loadAdminList() {
    $.ajax({
      url: "admin.php",
      type: "GET",
      success: function (response) {
        var newTable = $(response).find("table").html();
        $("table").html(newTable);
      },
      error: function () {
        alert("Error loading the admin list");
      },
    });
  }
});
