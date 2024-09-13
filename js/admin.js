//---------------------------------------------------------Admin Part-----------------------------------------------------------------------------------

//addAdminForm, editAdminForm, resetPasswordForm
$(document).ready(function () {
  function attachFormHandlers() {
    $(document).on("submit", "#addAdminForm", function (event) {
      event.preventDefault();

      var password = $("#password").val();
      var confirmPassword = $("#confirmPassword").val();

      if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return;
      }

      /* var fileInput = $('#image')[0].files[0];
        if (!fileInput) {
            alert("Please select an image.");
            event.preventDefault();
            return;
        }    
    
        var fileType = fileInput.type;
        var validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
        if ($.inArray(fileType, validImageTypes) < 0) {
            alert("Please select a valid image file (JPEG, PNG, GIF).");
            event.preventDefault();
            return;
        }*/

      var formData = new FormData(this);

      $.ajax({
        url: "addAdmin.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          alert(response);
          $("#edit-form-container").empty();
          window.location.href = "admin.php";
          //loadAdminList();
        },
        error: function () {
          alert("Error adding admin.");
        },
      });
    });

    $(document).on("submit", "#editAdminForm", function (event) {
      event.preventDefault();
      var confirmUpdate = confirm("Are you sure you want to update?");
      if (confirmUpdate) {
        var formData = new FormData(this);

        $.ajax({
          url: "updateAdmin.php",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            alert(response);
            $("#edit-form-container").empty();
            window.location.href = "admin.php";
            //loadAdminList();
          },
          error: function () {
            alert("Error updating data.");
          },
        });
      }
    });

    $(document)
      .off("submit", "#resetPasswordForm")
      .on("submit", "#resetPasswordForm", function (event) {
        event.preventDefault();

        var newPassword = $("#newPassword").val();
        var confirmPassword = $("#confirmPassword").val();
        var role = $("#role").val();

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

            if (role === "admin") {
              window.location.href = "admin.php";
            } else {
              window.location.href = "member.php";
            }
          },
          error: function () {
            alert("Error resetting password.");
          },
        });
      });
  }

  // Click add button then jumpt to Add Admin Form
  $(document).on("click", ".add-btn", function () {
    $.ajax({
      url: "../addAdminForm.php",
      type: "GET",
      success: function (response) {
        $("#edit-form-container").html(response);
        attachFormHandlers();
      },
      error: function () {
        alert("Error loading the adding form.");
      },
    });
  });

  // click edit button then jump to Edit Admin Form
  $(document).on("click", ".edit-btn", function () {
    var adminId = $(this).data("id");

    $.ajax({
      url: "../editAdminForm.php",
      type: "GET",
      data: { id: adminId },
      success: function (response) {
        $("#edit-form-container").html(response);
        attachFormHandlers();
      },
      error: function () {
        alert("Error loading the edit form.");
      },
    });
  });

  // click reset button then jump to Reset Password Form
  $(document).on("click", ".reset-btn", function () {
    var adminId = $(this).data("id");
    var username = $(this).data("username");
    var role = $(this).data("role");

    $.ajax({
      url: "../resetPasswordForm.php",
      type: "GET",
      data: { id: adminId, username: username, role: role },
      success: function (response) {
        $("#edit-form-container").html(response);
        attachFormHandlers();
      },
      error: function () {
        alert("Error loading the reset password form.");
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
          window.location.href = "admin.php";
          //loadAdminList();
        },
        error: function () {
          alert("Error deleting data.");
        },
      });
    }
  });

  // Function to reload the admin list
  /*
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
  }*/

  //Image change change change
  $("#image").change(function () {
    var file = this.files[0];
    if (file) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $("#imagePreview").attr("src", e.target.result);
        $("#imagePreview").show();
      };
      reader.readAsDataURL(file);
    }
  });
});

//------------------------------------------------------------------member Part---------------------------------------------------------------------------------

$(document).ready(function () {
  function loadTable() {
    $.ajax({
      url: "memberTable.php",
      type: "GET",
      data: $("#search-form").serialize(),
      success: function (response) {
        $("#member-table").html(response);
      },
    });
  }

  loadTable();

  // Handle form submission
  $("#search-form").on("submit", function (event) {
    event.preventDefault();
    loadTable();
  });

  // Click edit button then jump to Edit Member Form
  $(document).on("click", ".edit-member-btn", function () {
    var userId = $(this).data("id");

    $.ajax({
      url: "../editMemberForm.php",
      type: "GET",
      data: { id: userId },
      success: function (response) {
        $("#edit-form-container").html(response);
        attachMemberHandlers();
      },
      error: function () {
        alert("Error loading the edit form.");
      },
    });
  });

  //click add button then jump to Add Member Form
  $(document).on("click", ".add-member-btn", function () {
    $.ajax({
      url: "../addMemberForm.php",
      type: "GET",
      success: function (response) {
        $("#edit-form-container").html(response);
        attachMemberHandlers();
      },
      error: function () {
        alert("Error loading the adding form.");
      },
    });
  });

  //click delete button then jump to Delete member
  $(document).on("click", ".delete-member-btn", function () {
    var userId = $(this).data("id");
    var confirmDelete = confirm("Are you sure you want to delete this member?");

    if (confirmDelete) {
      $.ajax({
        url: "deleteMember.php",
        type: "POST",
        data: { id: userId },
        success: function (response) {
          alert("Deleted Successfully!");
          window.location.href = "member.php";
        },
        error: function () {
          alert("Error deleting data.");
        },
      });
    }
  });

  //AddMemberForm, EditMemberForm, resetPasswordForm

  function attachMemberHandlers() {
    $(document).on("submit", "#addMemberForm", function (event) {
      var password = $("#password").val();
      var confirmPassword = $("#confirmPassword").val();

      if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return;
      }

      var formData = new FormData(this);

      $.ajax({
        url: "addMember.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          alert(response);
          $("#edit-form-container").empty();
          window.location.href = "member.php";
        },
        error: function () {
          alert("Error adding member.");
        },
      });
    });

    $(document).on("submit", "#editMemberForm", function (event) {
      var formData = new FormData(this);

      event.preventDefault();
      var confirmUpdate = confirm("Are you sure you want to update?");
      if (confirmUpdate) {
        $.ajax({
          url: "updateMember.php",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            alert(response);
            $("#edit-form-container").empty();
            window.location.href = "member.php";
            //loadAdminList();
          },
          error: function () {
            alert("Error updating data.");
          },
        });
      }
    });
  }

  $(document).on(
    "click",
    ".block-member-btn, .unblock-member-btn",
    function () {
      var button = $(this);
      var action = button.hasClass("block-member-btn") ? "block" : "unblock";
      var id = button.data("id");
      var username = button.data("username");
      var role = button.data("role");

      if (
        confirm("Are you sure you want to " + action + " " + username + " ?")
      ) {
        $.ajax({
          url: "blockUser.php",
          method: "GET",
          data: { action: action, id: id, username: username },
          success: function (response) {
            alert(response);
            if (role === "admin") {
              window.location.href = "admin.php";
            } else {
              window.location.href = "member.php";
            }
          },
          error: function () {
            alert("An error occurred.");
          },
        });
      }
    }
  );


//-------------------------------------------------------------Category Part----------------------------------------------------------------------------


function attachCategoryForm() {
  $(document).on("submit", "#addCategoryForm", function (event) {
    event.preventDefault();

    var formData = new FormData(this);

    $.ajax({
      url:"addCategory.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        alert(response);
        $("#edit-form-container").empty();
        window.location.href = "category.php";
      },
      error: function () {
        alert("Error adding category.");
      },
    });
  });

  $(document).on("submit", "#editCategoryForm", function (event) {
    event.preventDefault();
    var confirmUpdate = confirm("Are you sure you want to update?");
    if (confirmUpdate) {
      var formData = new FormData(this);

      $.ajax({
        url: "updateCategory.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          alert(response);
          $("#edit-form-container").empty();
          window.location.href = "category.php";
          //loadAdminList();
        },
        error: function () {
          alert("Error updating data.");
        },
      });
    }
  });

}

// Click add button then jum  to Add Category Form
$(document).on("click", ".add-category-btn", function () {
  $.ajax({
    url: "../addCategoryForm.php",
    type: "GET",
    success: function (response) {
      $("#edit-form-container").html(response);
      attachCategoryForm();
    },
    error: function () {
      alert("Error loading the adding form.");
    },
  });
});

// click edit button then jump to Edit Category Form
$(document).on("click", ".edit-category-btn", function () {
  var Id = $(this).data("id");

  $.ajax({
    url: "../editCategoryForm.php",
    type: "GET",
    data: { id: Id },
    success: function (response) {
      $("#edit-form-container").html(response);
      attachCategoryForm();
    },
    error: function () {
      alert("Error loading the edit form.");
    },
  });
});


$(document).on("click", ".delete-category-btn", function () {
  var Id = $(this).data("id");
  var confirmDelete = confirm("Are you sure you want to delete this category?");

  if (confirmDelete) {
    $.ajax({
      url: "deleteCategory.php",
      type: "POST",
      data: { id: Id },
      success: function () {
        alert("Deleted Successfully!");
        window.location.href = "category.php";
      },
      error: function () {
        alert("Error deleting data.");
      },
    });
  }
});

});
