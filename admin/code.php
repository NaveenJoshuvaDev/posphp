<?php

include('../config/function.php');

if(isset($_POST['saveAdmin']))
{
     $name = validate($_POST['name']);
     $email = validate($_POST['email']);
     $password = validate($_POST['password']);
     $phone = validate($_POST['phone']);
     $is_ban = validate($_POST['is_ban']) == true ? 1:0;


    if($name != '' && $email != '' && $password != ''){

        $emailCheck = mysqli_query($conn,   "SELECT * FROM admins WHERE email='$email'");
        if($emailCheck){
            if(mysqli_num_rows($emailCheck) > 0)
            {
                redirect('admins-create.php', 'Email Already filled by another user.');
            }
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
              'name' => $name,
              'email' => $email,
              'password' => $bcrypt_password,
              'phone' => $phone,
              'is_ban' => $is_ban,
        ];
        $result = insert('admins', $data);
        if($result){
            redirect('admins.php', 'Admin Created Successfully!.');
        }else 
        {
            redirect('admins-create.php', 'Something went wrong');
        }
    }
    else
    {
        redirect('admins-create.php', 'Please fill required fields.');

    }


  


}
if(isset($_POST['updateAdmin'])){
    $adminId = validate($_POST['adminId']);
    $adminData = getById('admins', $adminId);
      if($adminData['status'] !=200){
        redirect('admins-edit.php?id='.$adminId, 'Please fill required fields are not valid.');
      }

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = validate($_POST['is_ban']) == true ? 1:0;
     
    //Email update previous done or what
    $EmailCheckQuery = "SELECT * FROM admins WHERE email='$email' AND id!='$adminId'";
    $checkResult = mysqli_query($conn, $EmailCheckQuery);
    
    if($checkResult){
        if(mysqli_num_rows($checkResult) > 0 )
        {
            redirect('admins-edit.php?id='.$adminId, 'Email Already used by another user.');
        }
    }






    if($password != ''){
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    }
    else
    {
        $hashedPassword = $adminData['data']['password'];
    }

    if($name != '' && $email != '')
    {
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'phone' => $phone,
            'is_ban' => $is_ban,
      ];
      $result = update('admins', $adminId, $data);
      if($result){
          redirect('admins-edit.php?id='.$adminId, 'Admin Updated Successfully!.');
      }else
      {
        redirect('admins-edit.php?id='.$adminId, 'Admin Created Successfully!.');
      }

    }
    else
{
    redirect('admins-create.php', 'Please fill required fields.');

}
}

if(isset($_POST['saveCategory']))
{
  $name = validate($_POST['name']);
  $description = validate($_POST['description']);
  $status = isset($_POST['status']) == true ? 1:0;
  $data = [
    'name' => $name,
    'description' => $description,
    'status' => $status,
];
$result = insert('categories', $data);
if($result){
  redirect('categories.php', 'Category Created Successfully!.');
}else
{
  redirect('categories-create.php', 'Something went wrong');
}
}

if(isset($_POST['updateCategory']))
{
  $categoryId = validate($_POST['$categoryId']);
  $name = validate($_POST['name']);
  $description = validate($_POST['description']);
  $status = isset($_POST['status']) == true ? 1:0;
  $data = [
    'name' => $name,
    'description' => $description,
    'status' => $status,
];
$result = update('categories', $categoryId, $data);
if($result){
  redirect('categories-edit.php?id='.$categoryId, 'Category Updated Successfully!.');
}else
{
  redirect('categories-edit.php?id='.$categoryId, 'Something went wrong');
}
}

if(isset($_POST['saveProduct']))
{ 
    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) == true ? 1:0;
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0){
       $path = "../assets/uploads/products";
       $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
       $filename = time().'.'.$image_ext;

       move_uploaded_file($_FILES['image']['tmp_name'], $path."/".$filename);
       $finalImage = "assets/uploads/products/".$filename;
    }
    else
    {
        $finalImage = '';

    }
    $data = [
        'category_id'=> $category_id,
      'name' => $name,
      'description' => $description,
      'price' => $price,
      'quantity' => $quantity,
      'image' => $finalImage,
      'status' => $status,
  ];
  $result = insert('products', $data);
  if($result){
    redirect('products.php', 'Category Created Successfully!.');
  }else
  {
    redirect('products-create.php', 'Something went wrong');
  }
}
?>