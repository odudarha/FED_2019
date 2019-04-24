<?php
  session_start();
  include('db.php');
  $query      = "SELECT * FROM INV_TYPE ORDER BY TYPE_DESCRIPTION ASC";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset ="UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FED: Manage Inventory</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>		
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js"></script>
    <!-- begin our custom scripts -->
    <script type="text/javascript" src="string_building.js"></script>
    <script type="text/javascript" src="workhorse.js"></script>
    <!-- end our custom scripts -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="fed.css">
  </head>
  <body>
    <div class="container box">
      <h1 align="center">Manage Inventory</h1>
      <div hidden class="alert alert-success alert-dismissible" id = "insert_succeed_box">
      <!--  regarding * data-dismiss="alert" * DO NOT USE THIS that completely destroys the div  -->
        <a href="#" id="alert-close-01" class="close"  aria-label="close">&times;</a>
        <strong>The following has changed:</strong> 
        <!-- fill in message here -->
        <p id="returned_update"></p>
      </div>
      <div hidden class="alert alert-danger alert-dismissible" id = "delete_succeed_box">
      <!--  regarding * data-dismiss="alert" * DO NOT USE THIS that completely destroys the div  -->
        <a href="#" id="alert-close-02" class="close"  aria-label="close">&times;</a>
        <strong>The following has changed:</strong> 
        <!-- fill in message here -->
        <p id="returned_delete"></p>
      </div>


      <div class="table-responsive">

        <br>
        <div class="horizontal-scroll">
          <div class= "add_button_holder" align="right">
            <button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-info">Add</button>
          </div>
          <table id="user_data" class="table table-striped table-hover">
            <thead>
              <tr>
                <th max-width="5%">Image</th>
                <th max-width="5%">UPC</th>
                <th max-width="15%">Description</th>
                <th max-width = "10%">
                  <select name="category" id="category" class="form-control">
                    <option value = "">Food Type</option>
                    <?php
                        foreach($connection->query($query) as $row){
                        echo '<option value = "'.$row["TYPE_ID"].'">'.$row["TYPE_DESCRIPTION"].'</option>';
                      }
                    ?>
                  </select>
                </th>
                <th max-width="15%">Quantity</th>
                <th max-width="5%" >Edit</th>
                <th max-width="5%">Delete</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>

<div id="userModal" class="modal fade">
  <div class="modal-dialog">
    <form method="post" id="user_form" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Item</h4>
        </div>
        <div class="modal-body">
          <img id="itemimage" name = "itemimage" style ="display: block; margin-left: auto; margin-right: auto; max-width: 300px; max-height: 300px; object-fit: scale-down;">
          <br />
          <label>UPC</label>
          <i class="fas fa-times-circle" style="display:none;"></i>
          <i class="fas fa-check-circle" style="display:none;"></i>
          <input type="text" name = "upc" id ="upc" class="form-control" autocomplete="off"  />
          <span id="valid_upc"></span>
          <br />
          <label>Description</label>
          <input type="text" name="description" id="description" class="form-control" autocomplete="off" />
          <span id="valid_description"></span>
          <br />
          <label>Food Type</label>
          <select name = "foodtype" id="foodtype" class ="form-control">
            <?php
                        foreach($connection->query($query) as $row){
                          echo '<option value = "'.$row["TYPE_ID"].'">'.$row["TYPE_DESCRIPTION"].'</option>';
                        }
            ?>

          </select>
          <br />
          <label>Quantity</label>
          <input type="text" name="quantity" id="quantity" class="form-control" autocomplete="off"/>
          <span id="valid_quantity"></span>
          <br />
          <label>Image Location</label>
          <input type="text" name="image_location" id="image_location" class="form-control" autocomplete="off" />
          <br />
          <span id="additional_info"></span>
          <br />
        </div>
        <div class="modal-footer">
          <input type="hidden" name="user_id" id="user_id" />
          <input type="hidden" name="operation" id="operation" />
          <button type ="button" name ="fetch" id ="fetch" class = "btn btn-success" style ="float: left;">Fetch Data</button>
          <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />

          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript" language="javascript" >
 //declaring this here because I am getting frustrated and might throw my computer out of my window--Babler
  var prior_quant  = '';
  var type_of_insertion = '';
$(document).ready(function(){
    
  function setInputFilter(textbox, inputFilter) {
    ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
      textbox.addEventListener(event, function() {
          if (inputFilter(this.value)) {
              this.oldValue = this.value;
              this.oldSelectionStart = this.selectionStart;
              this.oldSelectionEnd = this.selectionEnd;
          }
          else if (this.hasOwnProperty("oldValue")) {
              this.value = this.oldValue;
              this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
          }
      });
    });
  }
  
  function setPatternFilter(id, pattern) {
    setInputFilter(document.getElementById(id), function(value) { 
      return pattern.test(value); 
    });
  }
  
  $('#user_data').DataTable().destroy();
  load_data();//1
  setPatternFilter("upc", /^-?\d{0,12}$/);
  setPatternFilter("quantity",/^-?\d*$/);
    
  $('#foodtype').append($("<option>Choose Food Type</option>").attr("value","0"));
    var foodtypes = $('#foodtype option');
    foodtypes.sort(function(a,b){
        a = a.value;
        b = b.value;
        return a - b;
    });
    $('#foodtype').html(foodtypes);
    $('#foodtype').val("0");
    
  function resetErrorMessages() {
    $('.fas').css('display','none');
    $('#description').prop('readonly',false);
    $('#quantity').prop('readonly',false);
    $('#image_location').prop('readonly',false);
    $('#foodtype').prop('disabled',false);
    $('#valid_upc').text('');
    $('#action').removeAttr('disabled'); 
    $('#additional_info').text('');
    $('#additional_info').css('display','none');
    $('#foodtype').val(0);
    $('#description').val('');
    $('#quantity').val('');
    $('#image_location').val('');
  }
    
  $('#add_button').click(function() {
    resetErrorMessages();
    $('#fetch').css('display','block');
    $('#upc').val('');
    $('#user_form')[0].reset();
    $('#itemimage').css('display','none');
    $('.modal-title').text("Add Item");
    $('#action').val("Add");
    $('#operation').val("Add");
    type_of_insertion = "SQL_Insert";
  });
    
	
  function load_data (is_category) { //2
    var dataTable = $('#user_data').DataTable({
      "processing":true,
      "serverSide":true,
      "order":[],
      "ajax":{
          url:"fetch.php",
          type:"POST",
          data: {is_category:is_category} //3
      },
      "columnDefs":[
          {
              "targets":[0, 3, 4, 5, 6],
              "orderable":false,
              "orderable":false,
          },
      ],
    });  
  } //4
	
    
  $(document).on('change','#category',function() {
    var category = $(this).val();
    $('#user_data').DataTable().destroy();
    if(category !=''){
      load_data(category);
    }
    else{
      load_data();
    }
  });
  $(document).on('submit', '#user_form', function(event) {
    event.preventDefault();
    var description = $('#description').val();
    var quantity = $('#quantity').val();
    var user_id = $('#upc').val();
    $('#user_id').val(user_id);
    var food_type=$('#foodtype').val();
    var food_type_string = ""; //use this to grab the textual food type.
    if(user_id !='' && description != '' && quantity != '' && food_type != 0) {
      $.ajax({
        url:"insert.php",
        method:'POST',
        data:new FormData(this),
        contentType:false,
        processData:false,
        success:function(data) {
          //begin secondary AJAX call
          //show bootstrap success box and do the prepwork to get the data in it
          $("#insert_succeed_box").show();
          //grab the proper name of the food type
          var properType = getFoodType($('#foodtype').val());
          console.log("new logic");
          console.log(properType);
          console.log("new logic");
          //see notes on string_building.js about this object 
          var str_upd_ins_obj = {
          outer_upc_var: $('#upc').val(),
          outer_descript_var: $('#description').val(),
          outer_quant_var: $('#quantity').val(),
          outer_type_var:  properType,
          outer_image_var: $('#image_location').val(),
          };          
          //console.log(str_upd_ins_obj);
          //continue building the stringified HTML for the bootstrap alert
          var update_temp = alert_type_summon_arguments(type_of_insertion, str_upd_ins_obj);
          //console.log(update_temp);
          $("#returned_update").html(update_temp);
          $('#user_form')[0].reset();
          $('#userModal').modal('hide');
          $('#user_data').DataTable().destroy();
          var category = $('#category').val();
          if(category !=''){
            load_data(category);
          }
          else{
            load_data();
          }
          //bounce back to the top, close the boot strap box, purge the data from it.
          bounce_up_init_vars();
        }
      });
    }
    else
    {
      alert("Please fill in the UPC, Description, Quantity and select the food type");
    }
  });
	
  $(document).on('click','#fetch', function(event){
    event.preventDefault();
    var user_id =$('#upc').val();
    resetErrorMessages();
    $.ajax({
      url: "capture_data.php",
      method: "POST",
      data:{user_id:user_id},
      dataType: "json",
      success: function(data){
        if(data.valid_upc!=1){
          $('.fa-times-circle').css('display','inline-block');
          $('.fa-times-circle').css('color','red');
          $('.fa-times-circle').css('text-shadow','1px 1px 1px #ccc');
          $('.fa-times-circle').css('font-size','1.5em');
          $('#description').prop('readonly',true);
          $('#quantity').prop('readonly',true);
          $('#image_location').prop('readonly',true);
          $('#foodtype').prop('disabled',true);
          $('#valid_upc').text('Incorrect UPC!')
          $('#valid_upc').css('color','red');
          $('#valid_upc').css('font-weight','bold');
          $('#action').attr('disabled', 'disabled');
        }
        else if(data.upc_exists!=0){
          $('.fa-check-circle').css('display','inline-block');
          $('.fa-check-circle').css('color','green');
          $('.fa-check-circle').css('text-shadow','1px 1px 1px #ccc');
          $('.fa-check-circle').css('font-size','1.5em');
          $('#description').prop('readonly',true);
          $('#quantity').prop('readonly',true);
          $('#image_location').prop('readonly',true);
          $('#foodtype').prop('disabled',true);
          $('#valid_upc').text('UPC already exists in database!')
          $('#valid_upc').css('color','red');
          $('#valid_upc').css('font-weight','bold');
          $('#action').attr('disabled', 'disabled');
        }
        else if(data.data_captured ==0){
          $('.fa-check-circle').css('display','inline-block');
          $('.fa-check-circle').css('color','green');
          $('.fa-check-circle').css('text-shadow','1px 1px 1px #ccc');
          $('.fa-check-circle').css('font-size','1.5em');
          $('#additional_info').css('display','block');
          $('#additional_info').text('Data not found in either USDA or upcitemdb Databases');
          $('#additional_info').css('color','red');
          $('#additional_info').css('text-align','center');
          $('#additional_info').css('border','1px solid #ccc');
          $('#additional_info').css('border-radius','2px');
          $('#additional_info').css('font-weight','bold');   
        }
        else if(data.data_captured ==1){
          $('.fa-check-circle').css('display','inline-block');
          $('.fa-check-circle').css('color','green');
          $('.fa-check-circle').css('text-shadow','1px 1px 1px #ccc');
          $('.fa-check-circle').css('font-size','1.5em');
          $('#additional_info').css('display','block');
          $('#additional_info').text('Data found in upcitemdb Database');
          $('#additional_info').css('color','green');
          $('#additional_info').css('text-align','center');
          $('#additional_info').css('border','1px solid #ccc');
          $('#additional_info').css('border-radius','2px');
          $('#additional_info').css('font-weight','bold');
          $('#description').val(data.description);
          $('#quantity').val(data.quantity);
          $('#image_location').val(data.image_location);
        }
        else if(data.data_captured ==2){
          $('.fa-check-circle').css('display','inline-block');
          $('.fa-check-circle').css('color','green');
          $('.fa-check-circle').css('text-shadow','1px 1px 1px #ccc');
          $('.fa-check-circle').css('font-size','1.5em');
          $('#additional_info').text('Data found in USDA Database');
          $('#additional_info').css('color','green');
          $('#additional_info').css('text-align','center');
          $('#additional_info').css('display','block');
          $('#additional_info').css('border','1px solid #ccc');
          $('#additional_info').css('border-radius','2px');
          $('#additional_info').css('font-weight','bold');
          $('#description').val(data.description);
          $('#quantity').val(data.quantity);
          $('#image_location').val(data.image_location);
        }
      }
    })
  });
  
  $(document).on('click', '.update', function() {
    type_of_insertion = "SQL_Update";
    var user_id = $(this).attr("id");
    $('.fas').css('display','none');
    $('#description').prop('readonly',false);
    $('#quantity').prop('readonly',false);
    $('#image_location').prop('readonly',false);
    $('#foodtype').prop('disabled',false);
    $('#valid_upc').text('');
    $('#action').removeAttr('disabled'); 
    $('#additional_info').text('');
    $('#additional_info').css('display','none');
    $('#fetch').css('display','none');
    $.ajax({
      url:"fetch_single.php",
      method:"POST",
      data:{user_id:user_id},
      dataType:"json",
      success:function(data) {
        $('#userModal').modal('show');
        $('#action').val("Edit");
        $('#operation').val("Edit");
        $('#itemimage').css('display','block');
        $('#upc').val(data.upc);
        $('#description').val(data.description);
        $('#quantity').val(data.quantity);
        //console.log('quantity value prior to update: ' + $('#quantity').val());
        prior_quant = $('#quantity').val();
        $('.modal-title').text("Edit Item");
        $('#user_id').val(user_id);
        $('#image_location').val(data.item_image);
        $('#itemimage').attr('src',data.item_image);
        $('#foodtype').val(data.food_id);
        dataTable().ajax.reload();
          var category = $('#category').val();
          if(category !=''){
            load_data(category);
          }
          else{
            load_data();
          }
          
      }
    })
  });
	
  $(document).on('click', '.delete', function(){
    var user_id = $(this).attr("id");
    console.log("This is user_id which is an attribute being grabbed by jQuery, in theory this should be the attribute id: " + user_id);
    var del_quantity;
    var del_description;
    var del_image;
 
    $.ajax({
      url:"fetch_single.php",
      method:"POST",
      data:{user_id:user_id},
      dataType:"json",
      success:function(data) {
        $('#upc').val(data.upc);
        $('#description').val(data.description);
        $('#quantity').val(data.quantity);
        $('#image_location').val(data.item_image);
     
        type_of_insertion = "SQL_Delete";
        //this object helps build the bootstrap alert for deletions
        var inc_del_obj = {
          outer_upc_var: user_id,
          outer_descript_var:    $('#description').val().toString(),
          outer_quant_var:   $('#quantity').val().toString(),
          outer_image_var: $('#image_location').val().toString(),
        };  
      //this stringified html is part of the bootstrap box
      var delete_temp = alert_type_summon_arguments(type_of_insertion, inc_del_obj);
      $("#returned_delete").html(delete_temp);
        //console.log(delete_temp);
      }
    });
    if(confirm("Are you sure you want to delete this?")) {
      $.ajax({
          url:"delete.php",
          method:"POST",
          data:{user_id:user_id},
          success:function(data)
          {
            alert(data);
            $('#user_data').DataTable().destroy();
            var category = $('#category').val();
            if(category !='') {
              load_data(category);
            }
            else {
              load_data();
            }
          }
      });
      $("#delete_succeed_box").show();
      //show ^ then v  purge and close the warning box on click
        bounce_up_init_vars();
    }
    else {
      return false;	
    }
  });
  $('.alert .close').on('click', function(e) {
    $(this).parent().hide();
    //wipe_data("01");
});
});
</script>