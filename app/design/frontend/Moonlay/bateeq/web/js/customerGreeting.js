define(["jquery", "Magento_Customer/js/customer-data"], function(
  $,
  customerData
) {
  "use strict";
  return function(config, element) {
    const fullname = customerData.get("customer")().fullname;
    if (typeof fullname === "undefined") {
      customerData.reload("customer");
    }

    const check = setInterval(function() {
      let getFullname = customerData.get("customer")().fullname;
      if (getFullname) {
        const [firstname, lastname] = getFullname.split(" ");
        $(element).text(`${lastname} ${firstname}`);
        clearInterval(check);
      }
    }, 500);
  };
});
