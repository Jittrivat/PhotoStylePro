<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials:true");
header("Access-Control-Allow-Methods:PUT,GET,POST,DELETE,OPTIONS");
header("Access-Control-Allow-Headers:Origin,Content-Type,Authorization,Accept,X-Requested-With,x-xsrf-token");
header("Content-Type:application/json; charset=utf-8");

include "config.php";

$postjson = json_decode(file_get_contents('php://input'),true);

//เพิ่มช่างภาพ
if($postjson['aksi']=="prosess_photo"){

    $cekemail = mysqli_fetch_array(mysqli_query($mysqli,"SELECT * FROM tb_photograv WHERE pg_name='$postjson[pg_name]'
        "));
    if ($cekemail['pg_name']==$postjson['pg_name']){
        $result = json_encode(array('success'=>false, 'msg'=>'Photoname is already'));
    }else{

        $insert = mysqli_query($mysqli,"INSERT INTO tb_photograv SET
            pg_name          = '$postjson[pg_name]',
            pg_data          = '$postjson[pg_data]',
            pg_add           = '$postjson[pg_add]',
            pg_show          = '$postjson[pg_show]',
            pg_tel           = '$postjson[pg_tel]',
            id_user          = '$postjson[id_user]'
        ");
        if($insert){
            $result =json_encode(array('success'=>true,'msg'=>'Successfuly'));
        }else{
            $result =json_encode(array('success'=>false,'msg'=>'UserID Error'));
        }
    }
    echo $result;
}
//เพิ่มรูปภาพ
elseif($postjson['aksi']=="photo_photo"){

// $target_path = "http://localhost/ProjactPhoto/Projact/images/";
 
// $target_path = $target_path . basename( $_FILES['$postjson[photo_file]']['$postjson[photo_name]']);
 
// if(move_uploaded_file($_FILES['photo_file']['photo_name'], $target_path)) {
//     header('Content-type: application/json');
//     $data = ['success' => true, 'message' => 'Upload and move success'];
//     echo json_encode( $data );
// } else{
//     header('Content-type: application/json');
//     $data = ['success' => false, 'message' => 'There was an error uploading the file, please try again!'];
//     echo json_encode( $data );
// }

    
// $target_path = "/images";
 
// $target_path = $target_path . basename( $_FILES['$postjson[photo_file]']['$postjson[photo_file]']);
 
// move_uploaded_file($_FILES['$postjson[photo_file]']['$postjson[photo_name]'], $target_path)
 
// }
// if(move_uploaded_file($_FILES['file']['$postjson[photo_name]'], $target_path)) {
//     header('Content-type: application/json');
//     $data = ['success' => true, 'message' => 'Upload and move success'];
//     echo json_encode( $data );
// } else{
//     header('Content-type: application/json');
//     $data = ['success' => false, 'message' => 'There was an error uploading the file, please try again!'];
//     echo json_encode( $data );
// }
    // $ext = pathinfo($postjson[photo_file]);
    // $new_image_name = 'img_'.uniqid().".".$postjson[photo_file];
    // $image_path = "http://localhost/ProjactPhoto/images/";
    // $upload_path = $image_path.$postjson[photo_file];
    // $success = move_uploaded_file($new_image_name,$upload_path);

    // if ($success == false) {
    //     echo "ไม่สามารภอัพโหลดได้";
    //     exit();
    // }

    // $pro_image = $new_image_name;

    $cekemail = mysqli_fetch_array(mysqli_query($mysqli,"SELECT * FROM tb_photo WHERE photo_name='$postjson[photo_name]'"));
    if ($cekemail['photo_name']==$postjson['photo_name']){
        $result = json_encode(array('success'=>false, 'msg'=>'Photoname is already'));
    }else{
        $insert = mysqli_query($mysqli,"INSERT INTO tb_photo SET
            photo_name          = '$postjson[photo_name]',
            photo_information   = '$postjson[photo_information]',
            photo_file          = '$postjson[photo_name]',
            id_user             = '$postjson[id_user]'
        ");
        if($insert){
            $result =json_encode(array('success'=>true,'msg'=>'Successfuly'));
        }else{
            $result =json_encode(array('success'=>false,'msg'=>'UserID Error'));
        }
    }
    echo $result;
}
//โหลดรูปหน้าphoto
elseif($postjson['aksi']=="load_photo_photo"){
   
    $data = array();
    $query =mysqli_query($mysqli,"SELECT * FROM tb_photo ORDER BY id_user  DESC LIMIT  $postjson[start],$postjson[limit]");

    while ($rows = mysqli_fetch_array($query)){
   
    $data[] = array(
        'id_photo'            => $rows['id_photo'],
        'photo_file'          => $rows['photo_file'],
        'photo_name'          => $rows['photo_name'],
        'photo_information'   => $rows['photo_information'], 
        'id_user'             => $rows['id_user'], 

    );
}
    if($query){
     
        $result =json_encode(array('success'=>true,'result'=>$data));
    }else{
       
        $result =json_encode(array('success'=>false));
    }
    
    echo $result;
}
//โหลดรูปหน้าช่างภาพ
elseif($postjson['aksi']=="load_photo_grav"){
   
    $data = array();
    $query =mysqli_query($mysqli,"SELECT * FROM tb_photograv ORDER BY id_user  DESC LIMIT  $postjson[start],$postjson[limit]");

    while ($rows = mysqli_fetch_array($query)){
   
    $data[] = array(
        'id_pg'            => $rows['id_pg'],
        'pg_name'          => $rows['pg_name'],
        'pg_show'          => $rows['pg_show'],
        'pg_data'          => $rows['pg_data'], 
        'pg_add'           => $rows['pg_add'],
        'pg_tel'           => $rows['pg_tel'], 
        'id_user'          => $rows['id_user']

    );
}
    if($query){
        $result =json_encode(array('success'=>true,'result'=>$data));
    }else{
        $result =json_encode(array('success'=>false));
    }
    
    echo $result;
}
//ลบข้อมูลรูปภาพ
elseif($postjson['aksi']=="del_photo"){
       $query =mysqli_query($mysqli,"DELETE FROM tb_photo WHERE id_photo='$postjson[idp]'");
 
    if($query){
        $result =json_encode(array('success'=>true,));
    }else{
        $result =json_encode(array('success'=>false));
    }
    
    echo $result;
}
//ลบข้อมูลช่างภาพ
elseif($postjson['aksi']=="del_photograv"){
       $query =mysqli_query($mysqli,"DELETE FROM tb_photograv WHERE id_pg='$postjson[idg]'");
 
    if($query){
        $result =json_encode(array('success'=>true,));
    }else{
        $result =json_encode(array('success'=>false));
    }
    
    echo $result;
}

// //โหลดข้อมูลผู้ใช้   ยังไม่ได้
// elseif($postjson['aksi']=="photo_load_id"){
   
//     $data = array();
//     $query =mysqli_query($mysqli,"  SELECT * FROM tb_photo INNER JOIN tb_photograv
//                                     ON tb_photo.id_user = tb_photograv.id_user
//                                     WHERE tb_photo.id_user ='$postjson[id]'
//                                     DESC LIMIT  $postjson[start],$postjson[limit]");

//     // $query =mysqli_query($mysqli,"SELECT * FROM tb_users INNER JOIN tb_phtos 
//     // WHERE tb_users.id_user = '$postjson[id]' 
//     // AND tb_phtos.id_user = '$postjson[id]'  
//     // DESC LIMIT  $postjson[start],$postjson[limit]");


//     //INNER JOIN tb_phtos
//     //WHERE tb_users.id_user = $postjson[id] AND tb_phtos.id_user = $postjson[id] 
//     while ($rows = mysqli_fetch_array($query)){
   
//     $data[] = array(
//         'id_user'                => $rows['id_user'],
//         'id_photo'               => $rows['id_photo'],
//         'photo_name'             => $rows['photo_name'],
//         'photo_file'             => $rows['photo_file'],
//         'photo_information'      => $rows['photo_information'],  
//         'id_pg'                  => $rows['id_pg'], 
//         'pg_name'                => $rows['pg_name'], 
//         'pg_show'                => $rows['pg_show'], 
//         'pg_data'                => $rows['pg_data'], 
//         'pg_add'                 => $rows['pg_add'], 
//         'pg_tel'                 => $rows['pg_tel'] 

//     );
// }
//     if($query){
//         $result =json_encode(array('success'=>true,'result'=>$data));
//     }else{
//         $result =json_encode(array('success'=>false));
//     }
    
//     echo $result;
// }

// //เพิ่มข้อมูลผู้ใช้และแก้ไข
// elseif($postjson['aksi']=="prosess_crud"){

//     $pass_user = md5($postjson['pass_user']);
//     $cakpass = mysqli_fetch_array(mysqli_query($mysqli,"SELECT pass_user FROM tb_users WHERE id_user='$postjson[id]'"));
//     if ($postjson['pass_user']=="") {
//         $pass_user=$cakpass['pass_user'];
//     }else{
//         $pass_user=md5($cakpass['pass_user']);
//     }

//     if ($postjson['action']=="Create") {
    
//         $cekemail = mysqli_fetch_array(mysqli_query($mysqli,"SELECT email_address FROM tb_users WHERE email_address='$postjson[email_address]'"));
//         if ($cekemail['email_address']==$postjson['email_address']){
//             $result = json_encode(array('success'=>false, 'msg'=>'Email is already'));
//         }else{
            
//             $insert = mysqli_query($mysqli,"INSERT INTO tb_users SET
//                 your_name       = '$postjson[your_name]',
//                 email_address   = '$postjson[email_address]',
//                 pass_user       = '$pass_user',
//                 created_at      = '$today',
//                 status_us       = 'User'
//             ");
//             if($insert){
//                 $result =json_encode(array('success'=>true,'msg'=>'Successfuly'));
//             }else{
//                 $result =json_encode(array('success'=>false,'msg'=>'Prosess error'));
//              }
//          }
//     }else{
//         $updt = mysqli_query($mysqli,"UPDATE tb_users SET
//                 your_name       = '$postjson[your_name]',
//                 pass_user       = '$pass_user' WHERE id_user='$postjson[id]'
//             ");
//             if($updt){
//                 $result =json_encode(array('success'=>true,'msg'=>'Successfuly'));
//             }else{
//                 $result =json_encode(array('success'=>false,'msg'=>'Prosess error'));
//              }
//     }
//     echo $result;
// }
// //โหลดข้อมูลผู้ใช้
// elseif($postjson['aksi']=="loda_single_data"){
    
//     $query =mysqli_query($mysqli,"SELECT * FROM tb_users WHERE id_user='$postjson[id]'");
//     while ($rows = mysqli_fetch_array($query)){
   
//     $data = array(
//         'your_name'       => $rows['your_name'],
//         'email_address'   => $rows['email_address']        
//     );
// }
//     if($query){
//         $result =json_encode(array('success'=>true,'result'=>$data));
//     }else{
//         $result =json_encode(array('success'=>false));
//     }
    
//     echo $result;
// }


// //ล็อคอิน
// elseif($postjson['aksi']=="prosess_login"){
//     $password_user = md5($postjson['password_user']);
//     $logindata = mysqli_fetch_array(mysqli_query(
//         $mysqli,"SELECT * FROM tb_users WHERE email_address='$postjson[email_address]' AND password_user='$password_user'"));
    
//     $data = array(
//         'id_user'         => $logindata['id_user'],
//         'your_name'       => $logindata['your_name'],
//         'email_address'   => $logindata['email_address'],
//         'status_us'       => $logindata['status_us']
        
//     );
//     if($logindata){
//         $result =json_encode(array('success'=>true,'result'=>$data ));
//     }else{
//         $result =json_encode(array('success'=>false));
//     }
    
//     echo $result;
// }
// //โหลดข้อมูลผู้ใช้
// elseif($postjson['aksi']=="load_users"){
//     $data = array();
//     $query =mysqli_query($mysqli,"SELECT * FROM tb_users ORDER BY id_user DESC LIMIT $postjson[start],$postjson[limit]");
//     while ($rows = mysqli_fetch_array($query)){
   
//     $data[] = array(
//         'id_user'         => $rows['id_user'],
//         'your_name'       => $rows['your_name'],
//         'email_address'   => $rows['email_address'] 

//     );
// }
//     if($query){
//         $result =json_encode(array('success'=>true,'result'=>$data));
//     }else{
//         $result =json_encode(array('success'=>false));
//     }
    
//     echo $result;
// }
// //ลบข้อมูลผู้ใช้
// elseif($postjson['aksi']=="del_users"){
//        $query =mysqli_query($mysqli,"DELETE FROM tb_users WHERE  id_user='$postjson[id]'");
 
//     if($query){
//         $result =json_encode(array('success'=>true,));
//     }else{
//         $result =json_encode(array('success'=>false));
//     }
    
//     echo $result;
// }
// //เพิ่มข้อมูลผู้ใช้และแก้ไข
// elseif($postjson['aksi']=="prosess_crud"){

//     $password_user = md5($postjson['password_user']);
//     $cakpass = mysqli_fetch_array(mysqli_query($mysqli,"SELECT password_user FROM tb_users WHERE id_user='$postjson[id]'"));
//     if ($postjson['password_user']=="") {
//         $password_user=$cakpass['password_user'];
//     }else{
//         $password_user=md5($cakpass['password_user']);
//     }

//     if ($postjson['action']=="Create") {
    
//         $cekemail = mysqli_fetch_array(mysqli_query($mysqli,"SELECT email_address FROM tb_users WHERE email_address='$postjson[email_address]'"));
//         if ($cekemail['email_address']==$postjson['email_address']){
//             $result = json_encode(array('success'=>false, 'msg'=>'Email is already'));
//         }else{
            
//             $insert = mysqli_query($mysqli,"INSERT INTO tb_users SET
//                 your_name       = '$postjson[your_name]',
//                 email_address   = '$postjson[email_address]',
//                 password_user   = '$password_user',
//                 created_at      = '$today',
//                 status_us       = 'User'
//             ");
//             if($insert){
//                 $result =json_encode(array('success'=>true,'msg'=>'successfuly'));
//             }else{
//                 $result =json_encode(array('success'=>false,'msg'=>'Prosess error'));
//              }
//          }
//     }else{
//         $updt = mysqli_query($mysqli,"UPDATE tb_users SET
//                 your_name       = '$postjson[your_name]',
//                 password_user   = '$password_user' WHERE id_user='$postjson[id]'
//             ");
//             if($updt){
//                 $result =json_encode(array('success'=>true,'msg'=>'successfuly'));
//             }else{
//                 $result =json_encode(array('success'=>false,'msg'=>'Prosess error'));
//              }
//     }
//     echo $result;
// }
// //โหลดข้อมูลผู้ใช้
// elseif($postjson['aksi']=="loda_single_data"){
    
//     $query =mysqli_query($mysqli,"SELECT * FROM tb_users WHERE id_user='$postjson[id]'");
//     while ($rows = mysqli_fetch_array($query)){
   
//     $data = array(
//         'your_name'       => $rows['your_name'],
//         'email_address'   => $rows['email_address']        
//     );
// }
//     if($query){
//         $result =json_encode(array('success'=>true,'result'=>$data));
//     }else{
//         $result =json_encode(array('success'=>false));
//     }
    
//     echo $result;
// }

?>