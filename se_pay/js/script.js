(function ($, Drupal, drupalSettings) {
  "use strict";
  console.log("library loaded: Payment");
  Drupal.behaviors.paypalpayment = {
    attach: function (context) {

      $("#paypal-btn").click(function (e) {
        console.log("clicked");
        e.preventDefault();
        var formData = {
          pReferenceNumber: document.getElementById("pReferenceNumber").value,
          pCustomerName: document.getElementById("pCustomerName").value,
          pCustomerNumber: document.getElementById("pCustomerNumber").value,
          pEmailAddress: document.getElementById("pEmailAddress").value,
          pAmount: document.getElementById("pAmount").value,
          IsAjax: true,
        };
        console.log(JSON.stringify(formData));
        $.ajax({
          url: "/payment/paypal",
          method: "POST",
          contentType: "application/json; charset=utf-8",
          data: JSON.stringify(formData),
          beforeSend: function beforeSend() {}.bind(this),
          success: function success(data, status, jqXHR) {
            // for some reason responses from this API
            // dont get parsed as JSON (probs headers)
            console.log(data);
            var obj = JSON.parse(data);
            alert(obj);
            window.location = obj;
            return;

            // if (obj.redirect) {
            //   window.location = obj.redirect;
            //   return;
            // }

            /*  if (obj.error) {
              this.displayErrors([
                {
                  errDivId: "paypal-error",
                  errMsg: obj.error,
                },
              ]);
            } else if (obj.errors != null && obj.errors != undefined) {
              var errors = []; // Iterate the C# ModelState object

              Object.keys(obj.errors).forEach(function (x) {
                obj.errors[x].Errors.forEach(function (z) {
                  errors.push({
                    inputDivErrId: "p".concat(x, "-err"),
                    inputDivId: "p".concat(x),
                    inputErrMsg: z.ErrorMessage,
                  });
                });
              });
              this.displayErrors(errors);
            }*/
          }.bind(this),
          error: function error(data, status, jqXHR) {
            /* this.displayErrors([
              {
                errDivId: "paypal-error",
                errMsg: "Please enter your details correctly",
              },
            ]);*/
          }.bind(this),
          complete: function complete() {
            //this.hideLoader(this.loaderId);
          }.bind(this),
        });
      });
    },
  };
})(jQuery, Drupal, drupalSettings);
