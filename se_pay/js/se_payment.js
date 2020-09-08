/**
 * @file
 * Bambora Payment Pages theme behaviors.
 */

/* global jQuery, Drupal, drupalSettings, customcheckout */

(function ($, Drupal, drupalSettings) {
  "use strict";
  console.log("Clicked 22");
  Drupal.behaviors.sePayment = {
    attach: function (context) {
      this.initBamboraCheckout(context);
    },

    initBamboraCheckout: function (context) {
      if (typeof customcheckout === "undefined") {
        return;
      }

      var customCheckout = customcheckout();

      var isCardNumberComplete = false;
      var isCVVComplete = false;
      var isExpiryComplete = false;

      var customCheckoutController = {
        init: function () {
          this.createInputs();
          this.addListeners();
        },

        createInputs: function () {
          var options = {};
          options.placeholder = "Card number";
          customCheckout.create("card-number", options).mount("#card-number");

          options.placeholder = "CVV";
          customCheckout.create("cvv", options).mount("#card-cvv");

          options.placeholder = "MM / YY";
          customCheckout.create("expiry", options).mount("#card-expiry");
        },

        addListeners: function () {
          var self = this;
          // listen for submit button
          if (document.getElementById("checkout-form") !== null) {
            document
              .getElementById("checkout-form")
              .addEventListener("submit", self.onSubmit.bind(self));
          }

          customCheckout.on("empty", function (event) {
            if (event.empty) {
              if (event.field === "card-number") {
                isCardNumberComplete = false;
              } else if (event.field === "cvv") {
                isCVVComplete = false;
              } else if (event.field === "expiry") {
                isExpiryComplete = false;
              }
              self.setPayButton(false);
            }
          });

          customCheckout.on("complete", function (event) {
            if (event.field === "card-number") {
              isCardNumberComplete = true;
              self.hideErrorForId("card-number");
            } else if (event.field === "cvv") {
              isCVVComplete = true;
              self.hideErrorForId("card-cvv");
            } else if (event.field === "expiry") {
              isExpiryComplete = true;
              self.hideErrorForId("card-expiry");
            }

            self.setPayButton(
              isCardNumberComplete && isCVVComplete && isExpiryComplete
            );
          });

          customCheckout.on("error", function (event) {
            if (event.field === "card-number") {
              isCardNumberComplete = false;
              self.showErrorForId("card-number", event.message);
            } else if (event.field === "cvv") {
              isCVVComplete = false;
              self.showErrorForId("card-cvv", event.message);
            } else if (event.field === "expiry") {
              isExpiryComplete = false;
              self.showErrorForId("card-expiry", event.message);
            }

            self.setPayButton(false);
          });
        },

        onSubmit: function (event) {
          var self = this;
          event.preventDefault();
          self.setPayButton(false);
          self.toggleProcessingScreen();

          var callback = function (result) {
            if (result.error) {
              self.processTokenError(result.error);
            } else {
              self.processTokenSuccess(result.token);
            }
          };

          // @todo: Clarify what this token is.
          customCheckout.createOneTimeToken(
            "b21b0c3b-634d-4a13-a2c2-45b459f48adb",
            callback
          );
        },

        hideErrorForId: function (id) {
          var element = document.getElementById(id);
          if (element !== null) {
            var errorElement = document.getElementById(id + "-error");
            if (errorElement !== null) {
              errorElement.innerHTML = "";
            }
            var bootStrapParent = document.getElementById(id + "-bootstrap");
            if (bootStrapParent !== null) {
              bootStrapParent.classList.remove("has-error");
              bootStrapParent.classList.add("has-success");
            }
          }
        },

        showErrorForId: function (id, message) {
          var element = document.getElementById(id);
          if (element !== null) {
            var errorElement = document.getElementById(id + "-error");
            if (errorElement !== null) {
              errorElement.innerHTML = message;
            }

            var bootStrapParent = document.getElementById(id + "-bootstrap");
            if (bootStrapParent !== null) {
              bootStrapParent.classList.add("has-error");
              bootStrapParent.classList.remove("has-success");
            }
          }
        },

        setPayButton: function (enabled) {
          var payButton = document.getElementById("edit-submit");
          payButton.disabled = !enabled;
        },

        toggleProcessingScreen: function () {
          var processingScreen = document.getElementById("processing-screen");
          if (processingScreen) {
            processingScreen.classList.toggle("visible");
          }
        },

        showErrorFeedback: function (message) {
          var xMark = "\u2718";
          this.feedback = document.getElementById("feedback");
          this.feedback.innerHTML = xMark + " " + message;
          this.feedback.classList.add("error");
        },

        processTokenError: function (error) {
          error = JSON.stringify(error, 2);
          this.showErrorFeedback(
            "Error creating token: </br>" + JSON.stringify(error, null, 4)
          );
          this.setPayButton(true);
          this.toggleProcessingScreen();
        },

        processTokenSuccess: function (token) {
          this.setPayButton(true);
          this.toggleProcessingScreen();

          var formData = {
            reference_number: document.getElementById("reference_number").value,
            customer_name: document.getElementById("customer_name").value,
            token: token,
            payment_amount: document.getElementById("payment_amount").value,
          };

          // Calling Ajax
          $.ajax({
            url: "/payment/creditcard",
            method: "POST",
            contentType: "application/json; charset=utf-8",
            data: formData,
            success: function success(data, status, jqXHR) {
              console.log(JSON.parse(data));
              return;
            }.bind(this),
            error: function error(data, status, jqXHR) {}.bind(this),
            complete: function complete() {
              //this.hideLoader(this.loaderId);
            }.bind(this),
          });
        },
      };

      customCheckoutController.init();
    },
  };
})(jQuery, Drupal, drupalSettings);
