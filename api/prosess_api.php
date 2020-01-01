<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials:true");
header("Access-Control-Allow-Methods:PUT,GET,POST,DELETE,OPTIONS");
header("Access-Control-Allow-Headers:Origin,Content-Type,Authorization,Accept,X-Requested-With,x-xsrf-token");
header("Content-Type:application/json; charset=utf-8");

include "config.php";

$postjson = json_decode(file_get_contents('php://input'),true);
$today = date('Y-m-d H:i:s');

//สมัครสมาชิก
if($postjson['aksi']=="prosess_register"){

    $cekemail = mysqli_fetch_array(mysqli_query($mysqli," SELECT * FROM tb_users 
         WHERE email_address='$postjson[email_address]'"));
    if ($cekemail['email_address']==$postjson['email_address']){
        $result = json_encode(array('success'=>false, 'msg'=>'Email is already'));
    }else{
        $pass_user = md5($postjson['pass_user']);
        $insert = mysqli_query($mysqli,"INSERT INTO tb_users SET
            your_name       = '$postjson[your_name]',
            email_address   = '$postjson[email_address]',
            pass_user       = '$pass_user',
            created_at      = '$today',
            status_us       = 'User'
        ");
        if($insert){
            $result =json_encode(array('success'=>true,'msg'=>'Register successfuly'));
        }else{
            $result =json_encode(array('success'=>false,'msg'=>'Register error'));
        }
    }
    echo $result;
}

//ล็อคอิน
elseif($postjson['aksi']=="prosess_login"){
    $pass_user = md5($postjson['pass_user']);
    $logindata = mysqli_fetch_array(mysqli_query(
        $mysqli,"SELECT * FROM tb_users  WHERE  email_address='$postjson[email_address]' AND pass_user='$pass_user'"));
    
    $data = array(
        'id_user'         => $logindata['id_user'],
        'your_name'       => $logindata['your_name'],
        'email_address'   => $logindata['email_address'],
        'status_us'       => $logindata['status_us']
        
    );
    if($logindata){
        $result =json_encode(array('success'=>true,'result'=>$data ));
    }else{
        $result =json_encode(array('success'=>false));
    }
    
    echo $result;
}
//โหลดข้อมูลผู้ใช้
elseif($postjson['aksi']=="load_users"){
   
    $data = array();
    $query =mysqli_query($mysqli,"SELECT * FROM tb_users ORDER BY id_user  DESC LIMIT  $postjson[start],$postjson[limit]");

    // $query =mysqli_query($mysqli,"SELECT * FROM tb_users INNER JOIN tb_phtos 
    // WHERE tb_users.id_user = '$postjson[id]' 
    // AND tb_phtos.id_user = '$postjson[id]'  
    // DESC LIMIT  $postjson[start],$postjson[limit]");


    //INNER JOIN tb_phtos
    //WHERE tb_users.id_user = $postjson[id] AND tb_phtos.id_user = $postjson[id] 
    while ($rows = mysqli_fetch_array($query)){
   
    $data[] = array(
        'id_user'            => $rows['id_user'],
        'your_name'          => $rows['your_name'],
        'email_address'      => $rows['email_address'],
        'status_us'          => $rows['status_us'], 
        //'id_phto'            => $rows['id_phto'], 
        //'phto_file'          => $rows['phto_file'], 
        //'phto_name'          => $rows['phto_name'], 
        //'phto_information'   => $rows['phto_information'], 
        //'photo_show'         => $rows['photo_show'], 
        //'phto_datainfo'      => $rows['phto_datainfo'] 

    );
}
    if($query){
        $result =json_encode(array('success'=>true,'result'=>$data));
    }else{
        $result =json_encode(array('success'=>false));
    }
    
    echo $result;
}
//ลบข้อมูลผู้ใช้
elseif($postjson['aksi']=="del_users"){
       $query =mysqli_query($mysqli,"DELETE FROM tb_users WHERE  id_user='$postjson[id]'");
 
    if($query){
        $result =json_encode(array('success'=>true,));
    }else{
        $result =json_encode(array('success'=>false));
    }
    
    echo $result;
}
//เพิ่มข้อมูลผู้ใช้และแก้ไข
elseif($postjson['aksi']=="prosess_crud"){

    $pass_user = md5($postjson['pass_user']);
    $cakpass = mysqli_fetch_array(mysqli_query($mysqli,"SELECT pass_user FROM tb_users WHERE id_user='$postjson[id]'"));
    if ($postjson['pass_user']=="") {
        $pass_user=$cakpass['pass_user'];
    }else{
        $pass_user=md5($cakpass['pass_user']);
    }

    if ($postjson['action']=="Create") {
    
        $cekemail = mysqli_fetch_array(mysqli_query($mysqli,"SELECT email_address FROM tb_users WHERE email_address='$postjson[email_address]'"));
        if ($cekemail['email_address']==$postjson['email_address']){
            $result = json_encode(array('success'=>false, 'msg'=>'Email is already'));
        }else{
            
            $insert = mysqli_query($mysqli,"INSERT INTO tb_users SET
                your_name       = '$postjson[your_name]',
                email_address   = '$postjson[email_address]',
                pass_user       = '$pass_user',
                created_at      = '$today',
                status_us       = 'User'
            ");
            if($insert){
                $result =json_encode(array('success'=>true,'msg'=>'Successfuly'));
            }else{
                $result =json_encode(array('success'=>false,'msg'=>'Prosess error'));
             }
         }
    }else{
        $updt = mysqli_query($mysqli,"UPDATE tb_users SET
                your_name       = '$postjson[your_name]',
                pass_user       = '$pass_user' WHERE id_user='$postjson[id]'
            ");
            if($updt){
                $result =json_encode(array('success'=>true,'msg'=>'Successfuly'));
            }else{
                $result =json_encode(array('success'=>false,'msg'=>'Prosess error'));
             }
    }
    echo $result;
}
//โหลดข้อมูลผู้ใช้
elseif($postjson['aksi']=="loda_single_data"){
    
    $query =mysqli_query($mysqli,"SELECT * FROM tb_users WHERE id_user='$postjson[id]'");
    while ($rows = mysqli_fetch_array($query)){
   
    $data = array(
        'your_name'       => $rows['your_name'],
        'email_address'   => $rows['email_address']        
    );
}
    if($query){
        $result =json_encode(array('success'=>true,'result'=>$data));
    }else{
        $result =json_encode(array('success'=>false));
    }
    
    echo $result;
}
// elseif($postjson['aksi']=="load_users_photo"){
   
//     $data = array();
//     $query =mysqli_query($mysqli,"SELECT * FROM tb_phtos ORDER BY id_phto  DESC LIMIT  $postjson[start],$postjson[limit]");

//     // $query =mysqli_query($mysqli,"SELECT * FROM tb_users INNER JOIN tb_phtos 
//     // WHERE tb_users.id_user = '$postjson[id]' 
//     // AND tb_phtos.id_user = '$postjson[id]'  
//     // DESC LIMIT  $postjson[start],$postjson[limit]");


//     //INNER JOIN tb_phtos
//     //WHERE tb_users.id_user = $postjson[id] AND tb_phtos.id_user = $postjson[id] 
//     while ($rows = mysqli_fetch_array($query)){
   
//     $data[] = array(
//         'id_user'            => $rows['id_user'],
//         'id_phto'            => $rows['id_phto'], 
//         'photo_file'          => $rows['photo_file'], 
//         'photo_name'          => $rows['photo_name'], 
//         'photo_information'   => $rows['photo_information']

//     );
// }
//     if($query){
//         $result =json_encode(array('success'=>true,'result'=>$data));
//     }else{
//         $result =json_encode(array('success'=>false));
//     }
    
//     echo $result;
// }
// //โหลดข้อมูลรูปภาพ
// elseif($postjson['aksi']=="loda_data_photo"){
    
//     $query =mysqli_query($mysqli,"SELECT * FROM tb_phtos DESC LIMIT  $postjson[start],$postjson[limit] ");
//     while ($rows = mysqli_fetch_array($query)){
   
//     $data[] = array(
//         'id_phto'                => $rows['id_phto'],
//         'photo_file'             => $rows['photo_file'],
//         'photo_name'             => $rows['photo_name'],
//         'photo_information'      => $rows['photo_information'],
//         'id_user'                => $rows['id_user']         
//     );
// }
//     if($query){
//         $result =json_encode(array('success'=>true,'result'=>$data));
//     }else{
//         $result =json_encode(array('success'=>false));
//     }
    
//     echo $result;
// }
// //เพิ่มช่างภาพ
// if($postjson['aksi']=="prosess_photo"){

//     $cekemail = mysqli_fetch_array(mysqli_query($mysqli,"SELECT pg_name FROM tb_photos_gra WHERE pg_name='$postjson[pg_name]'"));
//     if ($cekemail['pg_name']==$postjson['pg_name']){
//         $result = json_encode(array('success'=>false, 'msg'=>'Photoname is already'));
//     }else{
//         $insert = mysqli_query($mysqli,"INSERT INTO tb_photos_gra SET
//             pg_name          = '$postjson[pg_name]',
//             pg_data          = '$postjson[pg_data]',
//             pg_add           = '$postjson[pg_add]',
//             pg_show          = '$postjson[pg_show]'
//         ");
//         if($insert){
//             $result =json_encode(array('success'=>true,'msg'=>'successfuly'));
//         }else{
//             $result =json_encode(array('success'=>false,'msg'=>'error'));
//         }
//     }
//     echo $result;
// }
// //เพิ่มรูปภาพ
// if($postjson['aksi']=="photo_photo"){

//     $cekemail = mysqli_fetch_array(mysqli_query($mysqli,"SELECT photo_name FROM tb_phtos WHERE photo_name='$postjson[photo_name]'"));
//     if ($cekemail['photo_name']==$postjson['photo_name']){
//         $result = json_encode(array('success'=>false, 'msg'=>'Photoname is already'));
//     }else{
//         $insert = mysqli_query($mysqli,"INSERT INTO tb_phtos SET
//             photo_name          = '$postjson[photo_name]',
//             photo_information   = '$postjson[photo_information]',
//             photo_file          = '$postjson[photo_file]'
//         ");
//         if($insert){
//             $result =json_encode(array('success'=>true,'msg'=>'successfuly'));
//         }else{
//             $result =json_encode(array('success'=>false,'msg'=>'error'));
//         }
//     }
//     echo $result;
// }
?>