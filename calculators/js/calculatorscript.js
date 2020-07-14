  (function ($, Drupal, drupalSettings) {

    'use strict';
    /* CODE GOES HERE */
    $(document).ready(function(){
                
        $("#calculatorsrdform #edit-rate, #calculatorsrdform #edit-principal, #calculatorsrdform #edit-tenure  ").on("keyup", function(){
            console.log("Sayan");
            var principal = parseFloat($("#edit-principal").val());
            var tenure = parseFloat($("#edit-tenure").val());
            var rate = parseFloat($("#edit-rate").val());

            console.log("p="+principal+"t="+tenure+"r="+rate);

            var n = parseFloat(tenure/3);
            var i = parseFloat(rate/400);
            var amount = principal*(Math.pow((1+i), n)-1) / (1 - Math.pow((1 + i ), (-1/3)) );

            console.log(amount); 
            $(".result").html("Maturity Amount: "+ amount.toFixed(2));
        });  
        
        $("#calculatorsfdform #edit-rate, #calculatorsfdform #edit-principal, #calculatorsfdform #edit-tenure  ").on("keyup", function(){
            console.log("Sayan FD");
            var principal = parseFloat($("#edit-principal").val());
            var tenure = parseFloat($("#edit-tenure").val()/12);
            var rate = parseFloat($("#edit-rate").val());

            console.log("p="+principal+"t="+tenure+"r="+rate);

            var exponent = 4*tenure;
            var mid = 1+(rate/(100*4));
            var afterpow = Math.pow(mid, (exponent));
            var amount = principal * afterpow;

           // var n = parseFloat(tenure/3);
           // var i = parseFloat(rate/400);
           // var amount = principal*(Math.pow((1+i), n)-1) / (1 - Math.pow((1 + i ), (-1/3)) );

            console.log(amount); 
            $(".result").html("Maturity Amount: "+ amount.toFixed(2));
        });
        
    });
  
  })(jQuery, Drupal, drupalSettings);

