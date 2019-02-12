<?php
include('db.php');
include('function.php');
if(isset($_POST["user_id"])){
    $output = array();
    $output["upc_exists"] = does_upc_exist($_POST["user_id"]);
    $output["valid_upc"] = is_upc_valid($_POST["user_id"]);
    $output["data_captured"]=0;
    if ($output["upc_exists"]!=1 && $output["valid_upc"]!= 0){
        
        $endpoint = 'https://api.upcitemdb.com/prod/trial/lookup';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        $web_address = $endpoint."?upc={$_POST["user_id"]}";
        curl_setopt($ch, CURLOPT_URL,$web_address);
        $response = curl_exec($ch);
        $json_values = json_decode($response);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($httpcode == 200 && $json_values -> total > 0){
            //$output["jsontotal"] = $json_values -> total;
            $output["description"] = $json_values -> items[0] -> title;
            $output["image_location"] = $json_values -> items[0] ->images[0];
            $output["quantity"]= 1;
            $output["data_captured"]=1;
        }
        else{
            // adapted from code by Jason S. to obtain info from UDSA API
            // variables used to assemble URL / CURLOPT_POSTFIELDS
            $FORMAT = 'json';
            $SORT = 'n';
            $MAX = '25';
            $OFFSET = '0';
            $upc = $_POST["user_id"];
            $api_key = 'W4I1rAWCzMPRT0xxsrY91geSECAWG42S575mNtVn';
            $url = 'https://api.nal.usda.gov/ndb/search/?';
            $USDA_data = array(
                    'format'    =>  $FORMAT,
                    'q'         =>  $upc,
                    'sort'      =>  $SORT,
                    'max'       =>  $MAX,
                    'offset'    =>  $OFFSET,
                    'api_key'   =>  $api_key
            );
            $post_data = http_build_query($USDA_data);
            $submit_url = $url . $post_data;
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, $submit_url );
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
            $json_USDA = curl_exec($ch1);
            curl_close ($ch1);
            $obj = json_decode($json_USDA,true);
            $output["jsontotal"] = $obj["list"]["total"];
            if ($obj ["list"]["total"] >0){
                $desc = $obj["list"]["item"][0]["name"];
                $output["description"] = substr($desc, 0, strlen($desc)-19);
                $output["quantity"] = 1;
                $output["image_location"]='';
                $output["data_captured"]=2;
            }
        }
        curl_close($ch);
    }
    echo json_encode($output);
}

?>