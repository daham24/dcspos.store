$(document).ready(function () {
  alertify.set("notifier", "position", "top-right");

  // Increment Quantity
  $(document).on("click", ".increment", function () {
    var $quantityInput = $(this).closest(".qtyBox").find(".qty");
    var productId = $(this).closest(".qtyBox").find(".prodId").val();
    var currentValue = parseInt($quantityInput.val());

    if (!isNaN(currentValue)) {
      var qtyVal = currentValue + 1;
      updateQuantity(productId, qtyVal, $quantityInput);
    }
  });

  // Decrement Quantity
  $(document).on("click", ".decrement", function () {
    var $quantityInput = $(this).closest(".qtyBox").find(".qty");
    var productId = $(this).closest(".qtyBox").find(".prodId").val();
    var currentValue = parseInt($quantityInput.val());

    if (!isNaN(currentValue) && currentValue > 1) {
      var qtyVal = currentValue - 1;
      updateQuantity(productId, qtyVal, $quantityInput);
    }
  });

  // Update Quantity Function
  function updateQuantity(prodId, qty, $quantityInput) {
    $.ajax({
      type: "POST",
      url: "orders-code.php",
      data: {
        productIncDec: true,
        product_id: prodId,
        quantity: qty,
      },
      success: function (response) {
        var res = JSON.parse(response);

        if (res.status == 200) {
          // Update only the affected row and grand total
          $quantityInput.val(qty); // Update quantity
          $quantityInput
            .closest("tr")
            .find("td:nth-child(5)") // Update the Total Price column
            .text(res.newTotalPrice);

          // Update Grand Total
          $("#grandTotal").text(res.grandTotal);

          alertify.success(res.message);
        } else {
          alertify.error(res.message);
        }
      },
      error: function () {
        alertify.error("Failed to update quantity. Please try again.");
      },
    });
  }

  // Payment Mode Field Visibility Management
  $(document).on("change", "#payment_mode", function () {
    var paymentMode = $(this).val();
    var referenceField = $("#reference_number_field");
    var instalmentField = $("#instalment_fields");
    var defaultCphoneField = $("#default_cphone_field");
    var instalmentCphoneField = $("#instalment_cphone_field");

    // Hide all special fields first
    referenceField.hide();
    instalmentField.hide();
    instalmentCphoneField.hide();
    defaultCphoneField.show();

    // Clear values
    $("#reference_number").val("");
    $("#down_payment").val("");
    $("#period_months").val("");
    $("#instalment_calculation").hide();

    // Copy phone value to instalment field if it exists
    if ($("#cphone").val()) {
      $("#cphone_instalment").val($("#cphone").val());
    }

    // Show relevant fields based on payment mode
    if (paymentMode === "Online Payment") {
      referenceField.show();
    } else if (paymentMode === "Instalment") {
      instalmentField.show();
      instalmentCphoneField.show();
      defaultCphoneField.hide();

      // Copy phone value from default to instalment field
      if ($("#cphone").val()) {
        $("#cphone_instalment").val($("#cphone").val());
      }
    }
  });

  // Sync phone values between fields
  $(document).on("input", "#cphone", function () {
    $("#cphone_instalment").val($(this).val());
  });

  $(document).on("input", "#cphone_instalment", function () {
    $("#cphone").val($(this).val());
  });

  // Proceed to place order button click
  $(document).on("click", ".proceedToPlace", function () {
    var payment_mode = $("#payment_mode").val();
    var cphone = $("#cphone").val();
    var reference_number = $("#reference_number").val();
    var down_payment = $("#down_payment").val();
    var period_months = $("#period_months").val();

    // Get phone value from appropriate field
    if (payment_mode === "Instalment") {
      cphone = $("#cphone_instalment").val();
    }

    // Validate payment mode
    if (payment_mode == "") {
      Swal.fire({
        title: "Select Payment Mode",
        text: "Please select a payment mode",
        icon: "warning",
        confirmButtonText: "OK",
      });
      return false;
    }

    // Validate phone number
    if (cphone == "" || !$.isNumeric(cphone) || cphone.length < 9) {
      Swal.fire({
        title: "Invalid Phone Number",
        text: "Please enter a valid phone number",
        icon: "warning",
        confirmButtonText: "OK",
      });
      return false;
    }

    // For online payment, reference number is required
    if (
      payment_mode == "Online Payment" &&
      (!reference_number || reference_number.trim() === "")
    ) {
      Swal.fire({
        title: "Reference Number Required",
        text: "Reference number is required for online payments",
        icon: "warning",
        confirmButtonText: "OK",
      });
      return false;
    }

    // For instalment payment, validate down payment and period
    if (payment_mode == "Instalment") {
      if (!down_payment || down_payment <= 0) {
        Swal.fire({
          title: "Down Payment Required",
          text: "Please enter a valid down payment amount",
          icon: "warning",
          confirmButtonText: "OK",
        });
        return false;
      }

      if (!period_months || period_months <= 0) {
        Swal.fire({
          title: "Period Required",
          text: "Please enter a valid period in months",
          icon: "warning",
          confirmButtonText: "OK",
        });
        return false;
      }
    }

    proceedWithOrder(
      payment_mode,
      cphone,
      reference_number,
      down_payment,
      period_months
    );
  });

  function proceedWithOrder(
    payment_mode,
    cphone,
    reference_number,
    down_payment,
    period_months
  ) {
    var data = {
      proceedToPlaceBtn: true,
      cphone: cphone,
      payment_mode: payment_mode,
      reference_number: reference_number,
      down_payment: down_payment,
      period_months: period_months,
    };

    $.ajax({
      type: "POST",
      url: "orders-code.php",
      data: data,
      success: function (response) {
        var res = JSON.parse(response);
        if (res.status == 200) {
          window.location.href = "order-summery.php";
        } else if (res.status == 404) {
          Swal.fire({
            title: "Customer Not Found",
            text: res.message,
            icon: res.status_type,
            showCancelButton: true,
            confirmButtonText: "Add Customer",
            cancelButtonText: "Cancel",
          }).then((result) => {
            if (result.isConfirmed) {
              $("#c_phone").val(cphone);
              $("#addCustomerModal").modal("show");
            }
          });
        } else {
          Swal.fire({
            title: "Error",
            text: res.message,
            icon: res.status_type,
            confirmButtonText: "OK",
          });
        }
      },
      error: function () {
        Swal.fire({
          title: "Error",
          text: "Failed to process request",
          icon: "error",
          confirmButtonText: "OK",
        });
      },
    });
  }

  // Save customer button click
  $(document).on("click", ".saveCustomer", function () {
    var c_name = $("#c_name").val();
    var c_phone = $("#c_phone").val();
    var c_email = $("#c_email").val();

    if (c_name != "" && c_phone != "") {
      if ($.isNumeric(c_phone)) {
        var data = {
          saveCustomerBtn: true,
          name: c_name,
          phone: c_phone,
          email: c_email,
        };

        $.ajax({
          type: "POST",
          url: "orders-code.php",
          data: data,
          success: function (response) {
            var res = JSON.parse(response);
            if (res.status == 200) {
              Swal.fire({
                title: "Success",
                text: res.message,
                icon: "success",
                confirmButtonText: "OK",
              }).then((result) => {
                if (result.isConfirmed) {
                  $("#addCustomerModal").modal("hide");
                  // After saving customer, proceed to order summary
                  window.location.href = "order-summery.php";
                }
              });
            } else if (res.status == 422) {
              Swal.fire({
                title: "Warning",
                text: res.message,
                icon: "warning",
                confirmButtonText: "OK",
              });
            } else {
              Swal.fire({
                title: "Error",
                text: res.message,
                icon: "error",
                confirmButtonText: "OK",
              });
            }
          },
          error: function () {
            Swal.fire({
              title: "Error",
              text: "Failed to save customer",
              icon: "error",
              confirmButtonText: "OK",
            });
          },
        });
      } else {
        Swal.fire({
          title: "Warning",
          text: "Please enter a valid phone number",
          icon: "warning",
          confirmButtonText: "OK",
        });
      }
    } else {
      Swal.fire({
        title: "Warning",
        text: "Please fill all required fields",
        icon: "warning",
        confirmButtonText: "OK",
      });
    }
  });

  // Save Order button click
  $(document).on("click", "#saveOrder", function () {
    console.log("Save Order button clicked");

    Swal.fire({
      title: "Confirm Order",
      text: "Are you sure you want to place this order?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Yes, Place Order",
      cancelButtonText: "Cancel",
    }).then((result) => {
      if (result.isConfirmed) {
        placeOrder();
      }
    });
  });

  function placeOrder() {
    $.ajax({
      type: "POST",
      url: "orders-code.php",
      data: { saveOrder: true },
      success: function (response) {
        console.log("Response received: ", response);
        try {
          var res = JSON.parse(response);
          console.log("Parsed response: ", res);

          if (res.status == 200) {
            // Set a flag to indicate successful order placement
            sessionStorage.setItem("orderPlaced", "true");

            $("#orderPlaceSuccessMessage").text(res.message);
            $("#orderSuccessModal").modal("show");

            // Update the page to show success state
            $("#saveOrder").prop("disabled", true).text("Order Placed");
            $(".btn-info, .btn-warning").prop("disabled", true);
          } else {
            Swal.fire({
              title: "Error",
              text: res.message,
              icon: res.status_type || "error",
              confirmButtonText: "OK",
            });
          }
        } catch (e) {
          console.error("Error parsing response: ", e);
          Swal.fire({
            title: "Error",
            text: "Invalid response from server!",
            icon: "error",
            confirmButtonText: "OK",
          });
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX error: ", error);
        Swal.fire({
          title: "Error",
          text: "Failed to connect to server!",
          icon: "error",
          confirmButtonText: "OK",
        });
      },
    });
  }

  // Handle modal close - redirect to orders page
  $("#orderSuccessModal").on("hidden.bs.modal", function () {
    window.location.href = "orders.php";
  });

  // Check if order was just placed when page loads
  if (sessionStorage.getItem("orderPlaced") === "true") {
    sessionStorage.removeItem("orderPlaced");
    // Disable the save order button if order was already placed
    $("#saveOrder").prop("disabled", true).text("Order Placed");
    $(".btn-info, .btn-warning").prop("disabled", true);
  }

  // Initialize payment mode fields on page load
  $(document).ready(function () {
    // Set initial state
    var initialPaymentMode = $("#payment_mode").val();
    if (initialPaymentMode === "Online Payment") {
      $("#reference_number_field").show();
    } else if (initialPaymentMode === "Instalment") {
      $("#instalment_fields").show();
      $("#instalment_cphone_field").show();
      $("#default_cphone_field").hide();
    }
  });
});

function printMyBillingArea() {
  var divContents = document.getElementById("myBillingArea").innerHTML;
  var a = window.open("", "");
  a.document.write("<html><title>DCS POS System</title>");
  a.document.write("<body style='font-family: Helvetica, Arial, sans-serif;'>");
  a.document.write(divContents);
  a.document.write("</body></html>");
  a.document.close();
  a.print();
}

// PDF download function
function downloadPDF(invoiceNo) {
  // Check if jsPDF is available
  if (typeof jsPDF === "undefined") {
    Swal.fire({
      title: "Error",
      text: "PDF library not loaded. Please try again.",
      icon: "error",
      confirmButtonText: "OK",
    });
    return;
  }

  try {
    const doc = new jsPDF();
    const element = document.getElementById("myBillingArea");

    doc.html(element, {
      callback: function (doc) {
        doc.save((invoiceNo || "invoice") + ".pdf");
      },
      x: 10,
      y: 10,
      width: 190,
      windowWidth: 650,
    });
  } catch (error) {
    console.error("PDF generation error:", error);
    Swal.fire({
      title: "Error",
      text: "Failed to generate PDF. Please try printing instead.",
      icon: "error",
      confirmButtonText: "OK",
    });
  }
}
