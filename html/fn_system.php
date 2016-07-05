<?php  
  
if(empty($_SESSION['user'])){  
      
    header("Location:http://115.28.194.142/fn_wx_login.php");  
}else{  
    print_r($_SESSION['user']);  
}  
  
?> 