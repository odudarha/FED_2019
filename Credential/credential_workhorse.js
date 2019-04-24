/* 
    This contains self-contained functions for the log-in protocols for the HCC FED database
    Author: Dave Babler 
    Date: 2019-03-23
*/
DEBUGGING = {
        //Debugging namespace by Dave Babler  
    debugButtonAlert: function (x, y) {
        //create an alert when the button is pressed.
        //this is good for debugging --Dave Babler
        var user = x;
        var pass = y;
        var debugButtonAlertText = '';
        debugButtonAlertText = "First Value passed is: " + x + " & Second value passed is: " + y;
        alert(debugButtonAlertText);
    }

}

CREDENTIAL = {
  
    sendToRolePage: function (role) {
        
        switch (role) {
            case "1":
                window.location.href = "http://dbabler.yaacotu.com/FED_2019/inventoryMain.php";
                break;
            case "2": 
            //sending both volunteers and admins to inventoryMain until we get a proper admin page.
                window.location.href = "http://dbabler.yaacotu.com/FED_2019/inventoryMain.php";
                break;
            case "3":
                window.location.href = "http://dbabler.yaacotu.com/FED_2019/inventoryMain.php";
                break;
            default:
                alert("Significant error in CREDENTIAL.sendToRolePage function contact webmaster.")
                break;
        }      
    }, 
    passMatcher: function(input1, input2) {
        if(input1 === input2) {
            $("#passwordEntryMisMatch").collapse('hide');
            $("#passwordEntryGood").collapse('show');
            return true;
        }else{
            $("#passwordEntryGood").collapse('hide');
            $("#passwordEntryMisMatch").collapse('show');
            return false
        }
    }
}

