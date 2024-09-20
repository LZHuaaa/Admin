

//-----------------------------------------------------Batch delete Part-------------------------
$("#batch-delete-btn").click(function (event) {
  event.preventDefault();

  var term = $(this).data("term");
  var selectedIds = $('input[name="' + term + 'ID[]"]:checked')
    .map(function () {
      return $(this).val();
    })
    .get();

  if (selectedIds.length === 0) {
    alert("Please select at least one item to delete.");
    return;
  }

  var confirmDelete = confirm("Are you sure you want to delete them?");

  if (confirmDelete) {
    $.ajax({
      url: "batch_delete.php",
      type: "POST",
      data: {
        term: term,
        ids: selectedIds,
      },
      success: function (response) {
        alert(response);
        location.reload();
      },
      error: function (xhr, status, error) {
        alert("An error occurred: " + error);
      },
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const selectAllCheckbox = document.getElementById("select-all");
  const checkboxes = document.querySelectorAll('input[name="userID[]"]');

  selectAllCheckbox.addEventListener("change", function () {
    checkboxes.forEach((checkbox) => {
      checkbox.checked = selectAllCheckbox.checked;
    });
  });

  // Ensure form is submitted only if at least one checkbox is selected
  const batchDeleteBtn = document.getElementById("batch-delete-btn");
  const form = document.getElementById("f");

  form.addEventListener("submit", function (e) {
    const checkedBoxes = Array.from(checkboxes).some(
      (checkbox) => checkbox.checked
    );
    if (!checkedBoxes) {
      e.preventDefault();
      alert("Please select at least one record to delete.");
    }
  });
});


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
          
        },
        error: function () {
          alert("Error adding admin.");
        },
      });
    });

    $(document).on("submit", "#editAdminForm", function (event) {
      event.preventDefault();

      var newPassword = $("#newPassword").val();
      var confirmPassword = $("#confirmPassword").val();

      if (newPassword !== confirmPassword) {
        alert("Passwords do not match.");
        return;
      }
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
            location.reload();
          },
          error: function () {
            alert("Error resetting password.");
          },
        });
      });
  }

  // Click add button then jump to Add Admin Form
  $(document).on("click", ".add-btn", function () {
    $.ajax({
      url: "addAdminForm.php",
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
      url: "editAdminForm.php",
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
      url: "resetPasswordForm.php",
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

  //select-all
  $("#select-all").click(function (event) {
    if (this.checked) {
      $('input[type="checkbox"]').each(function () {
        this.checked = true;
      });
    } else {
      $('input[type="checkbox"]').each(function () {
        this.checked = false;
      });
    }
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
        
        },
        error: function () {
          alert("Error deleting data.");
        },
      });
    }
  });


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
      url: "editMemberForm.php",
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
      url: "addMemberForm.php",
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
          alert(response);
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

      var newPassword = $("#newPassword").val();
      var confirmPassword = $("#confirmPassword").val();

      if (newPassword !== confirmPassword) {
        alert("Passwords do not match.");
        return;
      }

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
            location.reload();
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
        url: "addCategory.php",
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

  // Click add button then jump  to Add Category Form
  $(document).on("click", ".add-category-btn", function () {
    $.ajax({
      url: "addCategoryForm.php",
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
      url: "editCategoryForm.php",
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
    var confirmDelete = confirm(
      "Are you sure you want to delete this category?"
    );

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

  /*
  $("#batch-delete-category-btn").click(function (event) {
    event.preventDefault();
    ay;

    // Collect selected IDs
    var selectedIds = [];
    $('input[name="categoryID[]"]:checked').each(function () {
      selectedIds.push(this.value);
    });

    if (selectedIds.length === 0) {
      alert("Please select at least one item to delete.");
      return;
    }

    if (confirm("Are you sure you want to delete the selected items?")) {
      $.ajax({
        url: "batch_delete_category.php",
        type: "POST",
        data: { ids: selectedIds },
        success: function (response) {
          alert(response);
          location.reload();
          // window.location.href = "admin.php";
        },
        error: function () {
          alert("An error occurred while processing your request.");
        },
      });
    }
  });*/

});
//------------------------------------------------------Product part----------------------------------------------------------------------------------------------------

function showSizes() {
  var sizeType = document.getElementById("sizeType").value;
  var standardSizes = document.getElementById("standardSizes");
  var shoeSizes = document.getElementById("shoeSizes");

  if (sizeType === "standard") {
    standardSizes.style.display = "block";
    shoeSizes.style.display = "none";
  } else if (sizeType === "shoe") {
    standardSizes.style.display = "none";
    shoeSizes.style.display = "block";
  } else {
    standardSizes.style.display = "none";
    shoeSizes.style.display = "none";
  }
}

$('input[name="productImages[]"]').on("change", function () {
  $("#imagePreviewContainer").html("");
  var files = this.files;
  if (files) {
    $.each(files, function (index, file) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $("#imagePreviewContainer").append(
          '<img src="' +
            e.target.result +
            '" alt="Product Image" style="width:100px;height:100px;margin-right:10px;">'
        );
      };
      reader.readAsDataURL(file);
    });
  }
});

$("#productVideo").on("change", function () {
  var file = this.files[0];
  var videoContainer = $("#videoPreviewContainer");
  videoContainer.html("");

  if (file && file.type.match("video.*")) {
    var reader = new FileReader();

    reader.onload = function (e) {
      var video = $("<video controls>").attr("src", e.target.result).css({
        width: "300px",
      });
      videoContainer.append(video);
    };

    reader.readAsDataURL(file);
  }
});

$(document).on("click", ".add-product-btn", function () {
  $.ajax({
    url: "addProductForm.php",
    type: "GET",
    success: function (response) {
      $("#edit-form-container").html(response);
      attachProductHandlers();
    },
    error: function () {
      alert("Error loading the adding form.");
    },
  });
});

$(document).on("click", ".edit-product-btn", function () {
  var productId = $(this).data("id");

  $.ajax({
    url: "editProductForm.php",
    type: "GET",
    data: { id: productId },
    success: function (response) {
      $("#edit-form-container").html(response);
      attachProductHandlers();
    },
    error: function () {
      alert("Error loading the edit form.");
    },
  });
});

$(document).on("click", ".delete-product-btn", function () {
  var productId = $(this).data("id");
  var confirmDelete = confirm("Are you sure you want to delete this product?");

  if (confirmDelete) {
    $.ajax({
      url: "deleteProduct.php",
      type: "POST",
      data: { id: productId },
      success: function (response) {
        alert(response);
        window.location.href = "product.php";
      },
      error: function () {
        alert("Error deleting data.");
      },
    });
  }
});

$(document).ready(function () {
  $("#editProductForm").on("submit", function (event) {
    event.preventDefault();

    var formData = new FormData(this);

    $.ajax({
      url: "updateProduct.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        alert(response);
        $("#imagePreviewContainer").html("");
        location.reload();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert("Error occurred: " + textStatus + ", " + errorThrown);
      },
    });
  });

  $("#closeBtn").on("click", function () {
    window.close(); 
  });
});

/*$(document).ready(function () {
  $(".product-row").hover(
    function () {
      var photoPath = $(this).data("photo");
      if (photoPath) {
        var img = $("<img>", {
          src: "images/" + photoPath,
          class: "hover-image",
          css: {
            width: "100px",
            height: "100px",
            marginLeft: "10px",
          },
        });
        $(this).find(".action-column").append(img);
      }
    },
    function () {
      $(this).find(".hover-image").remove();
    }
  );
}); */

function attachProductHandlers() {
  $("#addProductForm").on("submit", function (event) {
    event.preventDefault();

    var formData = new FormData(this);

    $.ajax({
      url: "addProduct.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        alert(response);
        $("#addProductForm")[0].reset();
        $("#imagePreviewContainer").html("");
        location.reload();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        
        alert("Error occurred: " + textStatus);
      },
    });
  });
}
//----------------------------------------------------------------Order Part----------------------------------------------------------------------------

$(document).ready(function () {
  $("#select-all-order").click(function () {
    $('input[name="orderID[]"]').prop("checked", this.checked);
  });

  $("#batch-update-status").click(function () {
    $("#batch-update-section").toggle(); 
  });

  $("#batch-update-submit").click(function (e) {
    e.preventDefault();

    if ($('input[name="orderID[]"]:checked').length === 0) {
      alert("Please select at least one order to update.");
      return;
    }

    let status = $("#new-status").val();
    if (!status) {
      alert("Please select a new status.");
      return;
    }

    $("<input>")
      .attr({
        type: "hidden",
        name: "newStatus",
        value: status,
      })
      .appendTo("#batchUpdateForm");

    $("#batchUpdateForm").attr("action", "batchUpdateOrders.php").submit();
  });
});

//-------------------------------------------------------------Promotion Part---------------------------------------------------------------------------

$(document).ready(function () {
  $("#select-all-promotion").click(function () {
    $('input[name="promotionID[]"]').prop("checked", this.checked);
  });

  $("#batch-update-promotion-status").click(function () {
    $("#batch-update-promotion-section").toggle();
  });

  $("#batch-update-promotion-submit").click(function (e) {
    e.preventDefault();

    if ($('input[name="promotionID[]"]:checked').length === 0) {
      alert("Please select at least one promotion to update.");
      return;
    }

    let status = $("#new-status").val();
    if (!status) {
      alert("Please select a new status.");
      return;
    }

    $("<input>")
      .attr({
        type: "hidden",
        name: "newStatus",
        value: status,
      })
      .appendTo("#batchUpdatePromotionForm");

    $("#batchUpdatePromotionForm")
      .attr("action", "batchUpdatePromotions.php")
      .submit();
  });
});

$(document).on("click", ".add-promotion-btn", function () {
  $.ajax({
    url: "addPromotionForm.php",
    type: "GET",
    success: function (response) {
      $("#edit-form-container").html(response);
      attachPromotionHandlers();
    },
    error: function () {
      alert("Error loading the adding form.");
    },
  });
});

$(document).on("click", ".edit-promotion-btn", function () {
  var promotionId = $(this).data("id");

  $.ajax({
    url: "editPromotionForm.php",
    type: "GET",
    data: { id: promotionId },
    success: function (response) {
      $("#edit-form-container").html(response);
      //attachPromotionHandlers();
    },
    error: function () {
      alert("Error loading the edit form.");
    },
  });
});

$(document).on("click", ".delete-promotion-btn", function () {
  var promotionId = $(this).data("id");
  var confirmDelete = confirm(
    "Are you sure you want to delete this promotion?"
  );

  if (confirmDelete) {
    $.ajax({
      url: "deletePromotion.php",
      type: "POST",
      data: { id: promotionId },
      success: function (response) {
        alert(response);
        location.reload();
      },
      error: function () {
        alert("Error deleting data.");
      },
    });
  }
});



function attachPromotionHandlers() {
  $(document).on("submit", "#editPromotionForm", function (event) {
    console.log("Form submitted");
    event.preventDefault();
    var startDate = new Date($("#startDate").val());
    var endDate = new Date($("#endDate").val());
  
    if (endDate < startDate) {
      alert("Error: End date cannot be earlier than start date.");
      return false;
    }
  
    var formData = new FormData(this);
    var confirmUpdate = confirm("Are you sure you want to update?");
    if (confirmUpdate) {
      $.ajax({
        url: "updatePromotion.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          alert(response);
          $("#edit-form-container").empty();
          location.reload();
        },
        error: function () {
          alert("Error updating data.");
        },
      });
    }
  });

  $("#addPromotionForm").on("submit", function (event) {
    event.preventDefault();

    var startDate = new Date($("#startDate").val());
    var endDate = new Date($("#endDate").val());

    if (endDate < startDate) {
      alert("Error: End date cannot be earlier than start date.");
      return false;
    }

    var formData = new FormData(this);

    $.ajax({
      url: "addPromotion.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        alert(response);
        $("#addPromotionForm")[0].reset();
        $("#imagePreviewContainer").html("");
        location.reload();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert("Error occurred: " + textStatus);
      },
    });
  });

  
}
