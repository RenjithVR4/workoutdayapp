<?php
/*********************************************************
   Author:      Renjith VR
   Version:     1.0
   Date:        04-May-2018
   FileName:    usersettings.php
   Description : Routine for user
**********************************************************/

function createUser($params)
{   
    $mysqlcon = DBConnection();
    
    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d H:i:s');

    $values = array();
    $keys = array();

    foreach($params as $key=>$value)
    {
        $keys[] = mysql_fix_string($key,$mysqlcon);
        $values[] = mysql_fix_string($value,$mysqlcon);
    }
    
    $get_users_query = "INSERT INTO user (".implode(",",$keys).",created) VALUES ('".implode("','",$values)."', '$date')";
    unset($keys);
    unset($values);

    error_log($get_users_query);

    if(!$result = $mysqlcon->query($get_users_query))
    {
            error_log("Insert user error: ".$mysqlcon->errno. ": ".$mysqlcon->error);

            if($mysqlcon->errno == 1062)
            {
                //Email is unique.
                $error = "Email already exists";
            }
            else
            {
                $error = "Can't create user";
            }
            
            $mysqlcon->close();
            return array('error'=> $error);
    }
    else
    {
        $mysqlcon->close();
        return array('success' => "User created Successfully");
    }

 
}

function getUserById($userId)
{
    $mysqlcon = DBConnection();

    $get_users_query = sprintf("SELECT * FROM user WHERE iduser = '%s'", mysql_fix_string($userId, $mysqlcon));

    error_log($get_users_query);

    if(!$result = $mysqlcon->query($get_users_query))
    {
        error_log("Error get user by userid:". $mysqlcon->errno. ":". $mysqlcon->error);
        $mysqlcon->close();
        return array('error'=> 'Get user query error');
    }

    if($result->num_rows == 0)
    {
        $mysqlcon->close();
        return array('error'=> 'Empty');
    }

    $items = $result->fetch_assoc();

    $result->close();
    $mysqlcon->close();

    return $items;
}

function listUsers($page=1)
{  
    $mysqlcon = DBConnection();
    
    $get_users_query = " SELECT * FROM user";

    $limit = $page-1;

    $get_users_query.= " LIMIT ". ($limit*15) . ",15";

    error_log($get_users_query);

    if(!$result = $mysqlcon->query($get_users_query))
    {
            error_log("List users error: ".$mysqlcon->errno. ": ".$mysqlcon->error);
            $mysqlcon->close();
            return array('error'=> 'List users query error');
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


function getUsers($user=NULL){

    $mysqlcon = DBConnection();

    error_log("user =>" . $user);

    if(strlen($user) > 0)
    {
        $get_users_query = "SELECT * from user WHERE ".sprintf(" firstname LIKE '%%%s%%'", mysql_fix_string($username, $mysqlcon));

        $get_users_query .=  getDBORConnectorPhrase($get_users_query).sprintf(" lastname LIKE '%s'", mysql_fix_string($username, $mysqlcon)); 
    }
    else
    {
        $get_users_query = " SELECT * FROM user";
    }
    
    
    $get_users_query .= " ORDER BY firstname ASC";

    error_log($get_users_query);

    if(!$result = $mysqlcon->query($get_users_query))
    {
            error_log("List users error: ".$mysqlcon->errno. ": ".$mysqlcon->error);
            $mysqlcon->close();
            return array('error'=> 'List users query error');
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

function updateEducation($userid, $data)
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


function getUserByEmail($email)
{
    $mysqlcon = DBConnection();
    $email = "";
    error_log($email);

    $get_user_query = "SELECT DISTINCT(email) FROM user WHERE ".sprintf(" email = '%s'", mysql_fix_string($email, $mysqlcon));

    error_log($get_user_query);

    if(!$result = $mysqlcon->query($get_user_query))
    {
            error_log("get email error: ".$mysqlcon->errno. ": ".$mysqlcon->error);
            $mysqlcon->close();
            return array('error'=> 'get email query error');
    }

    if($result->num_rows == 0)
    {
            error_log("Empty result");
    }

    $row = $result->fetch_assoc();
    $email = $row['email'];

    
    $result->close();
    $mysqlcon->close();

    return $email;

}


function deleteUser($userid=NULL)
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