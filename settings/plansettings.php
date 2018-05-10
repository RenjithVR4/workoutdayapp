<?php
/*********************************************************
   Author:      Renjith VR
   Version:     1.0
   Date:        04-May-2018
   FileName:    plansettings.php
   Description : Routine for plan
**********************************************************/

function createPlan($params)
{   
    $mysqlcon = DBConnection();
    $mysqlcon->query("START TRANSACTION");
    
    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d H:i:s');
    $planid = "";

    $values = array();
    $keys = array();

    $planinsertdata = $params;
    unset($planinsertdata['userids']);
    unset($planinsertdata['daynames']);
    unset($planinsertdata['excerciseset']);

    error_log("planData =>". json_encode($planinsertdata));


    foreach($planinsertdata as $key=>$value)
    {
        $keys[] = mysql_fix_string($key,$mysqlcon);
        $values[] = mysql_fix_string($value,$mysqlcon);
    }
    
    $insert_plan_query = "INSERT INTO plan (".implode(",",$keys).",created) VALUES ('".implode("','",$values)."', '$date')";
    unset($keys);
    unset($values);


    error_log($insert_plan_query);

    if(!$insert_plan_result = $mysqlcon->query($insert_plan_query))
    {
            error_log("Insert plan error: ".$mysqlcon->errno. ": ".$mysqlcon->error);
            $mysqlcon->query("ROLLBACK");  
            if($mysqlcon->errno == 1062)
            {
                //planname is unique.
               $error = "Planname already exists";
            }
            else
            {
                $error = "Can't create plan";
            }  
            $mysqlcon->close();
            return array('error'=> $error);
    }
    else
    {
        $planid = $mysqlcon->insert_id;
    }


    $planuseridupdatedata = $params;
    unset($planuseridupdatedata['planname']);
    unset($planuseridupdatedata['daynames']);
    unset($planuseridupdatedata['excerciseset']);

    error_log("planData =>". json_encode($planuseridupdatedata));
    error_log("planid =>". $planid);

    $update_user_query = "";

    foreach($planuseridupdatedata as $plandata)
    {
        foreach($plandata as $planuserid)
        {
            $update_user_query .= "UPDATE user SET ";  

            $update_user_query .= sprintf("planid='%s'",mysql_fix_string($planid,$mysqlcon));

            $update_user_query .= sprintf(", Updated = '$date' WHERE iduser='%s'; ",mysql_fix_string($planuserid,$mysqlcon));
        }
    }

    error_log($update_user_query);


    if(!$update_user_result = $mysqlcon->multi_query($update_user_query))
    {
         error_log("Update userid user error: ".$mysqlcon->errno. ": ".$mysqlcon->error);
         $mysqlcon->query("ROLLBACK");  
         $error = $mysqlcon->error;
         $mysqlcon->close();
        return array('error'=> $error);
    }


    while($mysqlcon->more_results())
    {
        $mysqlcon->next_result();
        $mysqlcon->use_result();
    }

    $daysinsertdata = $params;
    unset($daysinsertdata['planname']);
    unset($daysinsertdata['userids']);
    unset($daysinsertdata['excerciseset']);

    error_log("planData =>". json_encode($daysinsertdata));
    error_log("planid =>". $planid);

    $insert_day_query = "";

    foreach($daysinsertdata as $daydata)
    {
        foreach($daydata as $dayname)
        {
            error_log("days =>". $dayname);
            $insert_day_query .= "INSERT INTO workoutday (workoutdayname, planid, created) VALUES ('$dayname', '$planid', '$date');";
        }
    }

    error_log($insert_day_query);

    if(!$insert_day_result = $mysqlcon->multi_query($insert_day_query))
    {
         error_log("insert day error: ".$mysqlcon->errno. ": ".$mysqlcon->error);
         $mysqlcon->query("ROLLBACK");
         $error = $mysqlcon->error;
         $mysqlcon->close();
        return array('error'=> $error);
    }
    else
    {
        $workoutdayid = $mysqlcon->insert_id;
    }

    while($mysqlcon->more_results())
    {
        $mysqlcon->next_result();
        $mysqlcon->use_result();
    }


    $excerciseinsertdata = $params;
    unset($excerciseinsertdata['planname']);
    unset($excerciseinsertdata['userids']);
    unset($excerciseinsertdata['daynames']);

    error_log("daysData =>". json_encode($excerciseinsertdata));
    error_log("workoutdayid =>". $workoutdayid);

    $insert_excercise_query = "";

    foreach($excerciseinsertdata as $excercisedata)
    {
        foreach($excercisedata as $excercise)
        {
             foreach($excercise as $excercisename)
            {
                $insert_excercise_query .= "INSERT INTO excercise (excercisename, workoutdayid, created) VALUES ('$excercisename', '$workoutdayid', '$date');";
            }
        }
    }

    error_log($insert_day_query);

    if(!$insert_excercise_result = $mysqlcon->multi_query($insert_excercise_query))
    {
         error_log("insert excercise error: ".$mysqlcon->errno. ": ".$mysqlcon->error);
         $mysqlcon->query("ROLLBACK");   
         $error = $mysqlcon->error;
         $mysqlcon->close();
        return array('error'=> $error);
    }
    

    while($mysqlcon->more_results())
    {
        $mysqlcon->next_result();
        $mysqlcon->use_result();
    }

    $mysqlcon->query("COMMIT");
    $mysqlcon->close();

    $useridset = $params['userids'];

    $sendEmail = array();

    foreach($useridset as $user_id){

        $sendEmail = planassigned_sendEmail($user_id);

        error_log(json_encode($sendEmail));

        if(!$sendEmail['success'])
        {
            error_log("email not sent => ". $user_id);
        }
        else
        {
            error_log("email send successfully => ". $user_id);
        }
    }

    
    return array("success" => $planid);
 
}


function planassigned_sendEmail($userid)
{
     $mysqlcon = DBConnection();

     $get_userdata = getUserById($userid);

     $sendmessage_data = array();

     $sendmessage_data['name'] = 'Workoutday App';
     $sendmessage_data['email'] = $get_userdata['email'];
     $sendmessage_data['subject'] = 'Workout Day App Plan Notification';
     $sendmessage_data['message'] = 'A workout plan has assigned for you.Please visit the workout day app now!';
     $sendmessage = sendEmail($sendmessage_data);

     if(!$sendmessage['success'])
     {
        return array('error' => 'Mail not sent');
     }
     else
     {
        return array('success' => 'Mail sent');
     }
}


function listPlan($page=1)
{  
    $mysqlcon = DBConnection();
    
    $get_plans_query = " SELECT idplan, planname FROM plan ORDER BY created ";

    $limit = $page-1;

    $get_plans_query.= " LIMIT ". ($limit*15) . ",15";

    error_log($get_plans_query);

    if(!$result = $mysqlcon->query($get_plans_query))
    {
            error_log("List plan-users error: ".$mysqlcon->errno. ": ".$mysqlcon->error);
            $mysqlcon->close();
            return array('error'=> 'List plan-users query error');
    }

    if($result->num_rows == 0)
    {
            $mysqlcon->close();
            return array('error'=> 'Empty');
    }
    
    $items = array();

    while($row = $result->fetch_assoc()) 
    {
        $items[] = $row;
    }
    
    $result->close();
    $mysqlcon->close();

    return $items;

}


function getPlan($planid)
{  
    $mysqlcon = DBConnection();
    
    // $get_plan_query = " SELECT p.idplan, p.planname, u.firstname, u.lastname, e.excercisename, e.workoutdayid, w.workoutdayname  FROM plan p LEFT JOIN user u ON p.idplan = u.planid LEFT JOIN workoutday w ON p.idplan = w.planid RIGHT JOIN excercise e ON e.workoutdayid = w.idworkoutday ORDER BY p.created ";

    $get_plan_query = "SELECT idplan, planname FROM plan ORDER BY created ";
    error_log($get_plan_query);

    if(!$result = $mysqlcon->query($get_plan_query))
    {
            error_log("List plan-users error: ".$mysqlcon->errno. ": ".$mysqlcon->error);
            $mysqlcon->close();
            return array('error'=> 'List plan-users query error');
    }

    if($result->num_rows == 0)
    {
            $mysqlcon->close();
            return array('error'=> 'Empty');
    }
    
    $items = array();

    while($row = $result->fetch_assoc()) 
    {
        $items[] = $row;
    }
    
    $result->close();
    $mysqlcon->close();

    return $items;

}


function updatePlan($userid, $data)
{
     $mysqlcon = DBConnection();

     date_default_timezone_set('Asia/Kolkata');
     $date = date('Y-m-d H:i:s');

    if(count($data) > 0)
    {
        $keys = array_keys($data);
        $lastkey = $keys[count($keys)-1];

        $query = "UPDATE user SET ";        
        
        foreach($data as $key=>$value)
        {
            $value=strip_tags($value);
            $query.= sprintf("%s='%s'",mysql_fix_string($key,$mysqlcon),mysql_fix_string($value,$mysqlcon));
            if($key !== $lastkey)
            {
                $query .= ',';
            }         
         }
            
        $query .= sprintf(", Updated = '$date' WHERE iduser='%s'",mysql_fix_string($userid,$mysqlcon));

        error_log($query); 

        if(!($stmt=$mysqlcon->prepare($query)))
        {
            error_log("Error updating user :(".$mysqlcon->errno.") : ".$mysqlcon->error);
            $mysqlcon->close();
            return array("error" => "Update user prepare error");
        }
        
        if(!($stmt->execute()))
        {
            error_log("Error updating user :(".$stmt->errno.") : ".$stmt->error);
            $mysqlcon->close();
            return array("error" => "Update user execute error");
        }
        
        if($stmt->affected_rows == 0)
        {
            $stmt->close();
            $mysqlcon->close();
            return array("error" => "Error updating user ");
        }
    }


    return ["success" => $userid];
}


function deletePlan($userid=NULL)
{
    $mysqlcon = DBConnection();
    
    $deleteuserquery=sprintf("DELETE FROM user WHERE iduser='%s'",mysql_fix_string($userid,$mysqlcon));
    
    error_log($deleteuserquery);

    if(!$stmt=$mysqlcon->prepare($deleteuserquery))
    {
        error_log("Delete user prepare failed :".$mysqlcon->errno.": ".$mysqlcon->error);
        return formatError(1009);
    }

    if(!$stmt->execute())
    {
        error_log("Delete user execute failed :".$stmt->errno.": ".$stmt->error);
        return formatError(1009);
    }

    if($stmt->affected_rows===0)
    {
        error_log("Delete user failed :".$stmt->errno.": ".$stmt->error);
        return formatError(2016);
    }

    $stmt->close();
    $mysqlcon->commit();
    $mysqlcon->close();
    
    return array("success"=>$userid);   
}