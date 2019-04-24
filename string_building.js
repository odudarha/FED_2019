
/*Since javascript is apparently a classless language, (or at least the explanations for 
  how objects and extension of objects in JavaScript are vague, at best) 
  use the object below as a template for passing data into the  alert_type_summon function -- Dave Babler 
var str_build_obj = {
  outer_upc_var: "",
  outer_descript_var: "",
  outer_quant_var: "",
  outer_image_var: "",
  
};
 
var str_upd_ins_obj = {
  outer_upc_var: user_id,
  outer_descript_var: description,
  outer_quant_var: quantity,
  outer_type_var:  food_type,
  outer_image_var: product_image,
  }; */


  function alert_type_summon_arguments(outer_type_of_insertion, inc_str_bld_obj){


    let outputted_string;
    switch(outer_type_of_insertion) {
      case "SQL_Update":    
        outputted_string =  update_confirmation_builder(inc_str_bld_obj.outer_upc_var, inc_str_bld_obj.outer_descript_var, inc_str_bld_obj.outer_quant_var, inc_str_bld_obj.outer_type_var, inc_str_bld_obj.outer_image_var);
        break;
      case "SQL_Insert":
        outputted_string =  insert_confirmation_builder(inc_str_bld_obj.outer_upc_var, inc_str_bld_obj.outer_descript_var, inc_str_bld_obj.outer_quant_var, inc_str_bld_obj.outer_type_var, inc_str_bld_obj.outer_image_var);
        break;
      case "SQL_Delete":
        outputted_string = delete_confirmation_builder(inc_str_bld_obj.outer_upc_var, inc_str_bld_obj.outer_descript_var, inc_str_bld_obj.outer_quant_var, inc_str_bld_obj.outer_image_var);
        break;
      default:
        outputted_string = "<h3> a significant error has occurred in module alert_type_summon";
    }
    return outputted_string;
  }
  
  
function update_confirmation_builder(upc_var, descriptor_var, quant_var, type_var, image_var) {
  let string_out;
  if(descriptor_var){
    string_out = '<div class="table-responsive">';
    string_out += '<table class="table-condensed">';
    string_out += '<tbody>';
    string_out += '<tr>';
    string_out += '<td>UPC updated: </td>';
    string_out += '<td>'+ upc_var + '</td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Description: </td>';
    string_out += '<td>'+ descriptor_var + '</td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<tr>';
    string_out += '<td>Previous Quantity: </td>';
    string_out += '<td>' + prior_quant + '</td>';
    string_out += '</tr>';
    string_out += '<td>Quantity changed to: </td>';
    string_out += '<td>'+ quant_var + '</td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Product Type is of: </td>';
    string_out += '<td>' + type_var + '</td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Looks like: </td>';
    string_out += '<td><div><img src="' + image_var + '" class="img-thumbnail" style="display: block; margin-left: none; margin-right: auto; width: 75px; height: 75px; object-fit: scale-down;"></div></td>';
    string_out += '</tr>';
    string_out += '</tbody>';
    string_out += '</table>';
    string_out += '</div>';
  }
  else{
    console.log("a significant error has occurred in module update_confirmation_builder!");
  }
  return string_out;
}

function insert_confirmation_builder(upc_var, descriptor_var, quant_var, type_var, image_var) {
  let string_out;
  if(descriptor_var){
    string_out = '<div class="table-responsive">';
    string_out += '<table class="table-condensed">';
    string_out += '<tbody>';
    string_out += '<tr>';
    string_out += '<td>UPC <i> Added to the database </i>: </td>';
    string_out += '<td>'+ upc_var + '</td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Description: </td>';
    string_out += '<td>'+ descriptor_var + '</td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Quantity changed to: </td>';
    string_out += '<td>'+ quant_var + '</td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Product Type is of: </td>';
    string_out += '<td>' + type_var + '</td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Looks like: </td>';
    string_out += '<td><div><img src="' + image_var + '" class="img-thumbnail" style="display: block; margin-left: none; margin-right: auto; width: 75px; height: 75px; object-fit: scale-down;"></div></td>';
    string_out += '</tr>';
    string_out += '</tbody>';
    string_out += '</table>';
    string_out += '</div>';
  }
  else{
    console.log("a significant error has occurred in module insert_confirmation_builder!");
  }
  return string_out;
}

function delete_confirmation_builder(upc_var, descriptor_var, quant_var, image_var) {
  let string_out;
  if(descriptor_var){
    string_out = '<div class="table-responsive">';
    string_out += '<table class="table-condensed">';
    string_out += '<tbody>';
    string_out += '<tr>';
    string_out += '<td>UPC: </td>';
    string_out += '<td>'+ upc_var + '<i> has been purged from the database, the only way to undo this, is to copy this data and re-insert </i></td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Description: </td>';
    string_out += '<td>'+ descriptor_var + '</td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Quantity removed: </td>';
    string_out += '<td>'+ quant_var + '</td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Looks like: </td>';
    string_out += '<td><div><img src="' + image_var + '" class="img-thumbnail" style="display: block; margin-left: none; margin-right: auto; width: 75px; height: 75px; object-fit: scale-down;"></div></td>';
    string_out += '</tr>';
    string_out += '</tbody>';
    string_out += '</table>';
    string_out += '</div>';
  }
  else{
    console.log("a significant error has occurred in module delete_confirmation_builder!");
  }
  return string_out;
}

function zero_inventory_checkout_builder(upc_var, descriptor_var, image_var){
  let string_out;
  if(descriptor_var){
    string_out = '<div class="table-responsive">';
    string_out += '<table class="table-condensed">';
    string_out += '<tbody>';
    string_out += '<tr>';
    string_out += '<td>UPC: </td>';
    string_out += '<td>'+ upc_var + '<i> is listed with zero quantity.</i></td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Description: </td>';
    string_out += '<td>'+ descriptor_var + '</td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Looks like: </td>';
    string_out += '<td><div><img src="' + image_var + '" class="img-thumbnail" style="display: block; margin-left: none; margin-right: auto; width: 75px; height: 75px; object-fit: scale-down;"></div></td>';
    string_out += '</tr>';
    string_out += '<tr>';
    string_out += '<td>Action to take: </td>';
    string_out += '<td>Please <b>write down this UPC and description then give it to a FED administrator</b>;<br>keep the product, and close this dialogue to continue with transaction.</td>';
    string_out += '</tr>';
    string_out += '</tbody>';
    string_out += '</table>';
    string_out += '</div>';
  }else{
      console.log("a significant error has occurred in module zero_inventory_checkout_builder");
    }
  return string_out;
}