//file contains some of the workhorse functions, functions that may get called several times a script, 
function wipe_data(alert_id_num){
    //takes the last two digits of an alert's id concatenates and wipes the text
    //Dave Babler
    var event_origin = "alert-close-" + alert_id_num;
    var target_alert;
    var target_box;
    switch (alert_id_num) {
      case "01":
        target_alert = "returned_update";
        target_box = "insert_succeed_box";
        break;
      case "02":
        target_alert = "returned_delete";
        target_box = "delete_succeed_box";
    }
    $(event_origin).click(function(){
     $(target_alert).empty();
     // $(target_box).hide(); currently not needed, but ... leaving it in because
        
    });   
   }
  
  function bounce_up_init_vars(){
    window.scrollTo(0,0);
    console.log(type_of_insertion);
    //reset the variable type
    type_of_insertion = "";
  }
  function getFoodType(numericTypeID){
    let newData;
    $.ajax({
    url:"pdo_select_type.php",
    async: false, 
    method:'POST',
    dataType:'json',
    data: {typeid_in: numericTypeID,
    },

    success: function(result) {
            newData = result;
            console.log(result);
            $("#ajaxresponse").html(result);
    }

    });
  return newData;
  }
  //oberon picture for product testing http://i63.tinypic.com/2wex3z5.jpg oberon upc 740522110657
 
 
  THROTTLE = {

    debounce: function(func, wait, immediate){
      var timeout;
      return function() {
        var context = this, args = arguments;
        var later = function() {
          timeout = null;
          if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    }
    
  }, 
 
    entryTimerStart: function(passedID){
      var timer = null;
      $('#' + passedID).on("input", function(){
       var outputVal = null;
        clearTimeout(timer);
        timer = setTimeout(THROTTLE.promisedAjax,  3000);
        return outputVal;
        
      });
    }, 
    delayedValue: function(){
      var dataToPassEventually = $('#userEntry').val();
     return dataToPassEventually;
    },

    promisedAjax: function () {
      return new Promise((resolve, reject) => {
          setTimeout(() => {
           
          entry = THROTTLE.delayedValue();
          const error = false;
          if(!error){
              resolve(AJAX_TO_DATABASE.ajaxSearch(entry));
          }else{
              reject ('Bad stuff happened and it sucks!');
          }
          }, 1000);
      });
    }

} //end THROTTLE namespace 


AJAX_TO_DATABASE = {

  ajaxSearch: function(searchData){
    if(searchData === '' || searchData == '' || searchData == null || searchData === undefined) {
      //prevent an emptied field from selecting random data.
        console.log("No data currently selected and/or empty value set");
    }else{
      let queryType = new Array("SEARCH");//I assume passing it as an array is what will let AJAX take it.
      $.ajax({
        type: "POST", 
        url: 'checkoutDBLogic.php', 
        data:{'searchData': searchData, 
              'queryType': queryType}, 
        dataType: "json",
        success: function(searchResponse){
          console.log(searchResponse);
          if(searchResponse.cartQuantity < 1){
            AJAX_TO_DATATABLES.zeroInventory(searchResponse);
          }else{
            AJAX_TO_DATATABLES.createRow(searchResponse);
          }
        }
      })
    }
  }, 

  ajaxCheckout: function(cartData){
    console.log("inside ajaxCheckout");
    let queryType = new Array("CHECKOUT"); //my assumptions in AJAX_TO_DATABASE.ajaxSearch were correct
    $.ajax({
      type: "POST", 
      url: 'checkoutDBLogic.php', 
      data:{'cartData': cartData, 
            'queryType': queryType}, 
      dataType: "text",
      success: function(cartResponse){
        console.log("cartResponse should be exactly below this line");
        console.log(cartResponse);
        console.log("the only thing above this should be a response from AJAX");
      }
    })
  }

} //end AJAX_TO_DATABASE namespace


AJAX_TO_DATATABLES = {
  createImg: function(dbImg){
    let imgOut ='';
    let prefixChunk = '<img src="';
    let middleChunk = dbImg;
    let suffixChunk = '" class="img-thumbnail" style ="display: block; margin-left: auto; margin-right: auto; width: 100px; height: 100px; object-fit: scale-down;" />';
    return imgOut = prefixChunk + middleChunk + suffixChunk;
  },

  createButton: function(dbUPC){
    let buttonOut = '';
    let prefixChunk = '<button type="button" class="btn btn-danger delete" id="';
    let middleChunk = dbUPC;
    let suffixChunk = '">Delete from cart</button>';
    return buttonOut = prefixChunk+middleChunk+suffixChunk;
  }, 


  createRow: function(dbResponse){
    let imageProper = AJAX_TO_DATATABLES.createImg(dbResponse.cartImage);
    let deleteProper = AJAX_TO_DATATABLES.createButton(dbResponse.cartUPC);
    table.row.add({
      "Image": imageProper,
      "UPC": dbResponse.cartUPC, 
      "Description" : dbResponse.cartDescription,
      "Delete": deleteProper,
      "TypeID": dbResponse.cartType_ID
    }).draw();
  }, 

  zeroInventory: function(dbResponse){
    //no need for image proper, the string builder will handle that for us. 
    //no need for delete button.
    let bootstrap_warning_string = zero_inventory_checkout_builder(dbResponse.cartUPC, dbResponse.cartDescription, dbResponse.cartImage);
    $("#checkout_zero_inventory_alert").show();
    $("#checkout_zero_inventory").html(bootstrap_warning_string);    
  }


}//end AJAX_TO_DATATABLES namespace

  


  
