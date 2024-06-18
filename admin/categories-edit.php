<?php include('includes/header.php')  ?>


<div class="container-fluid px-4">
   <div class="card mt-4 shadow-sm">
    <div class="card-header">
        <h4 class="mb-0">Edit Category
            <a href="categories.php" class="btn btn-primary float-end">Back</a>
        </h4>
    </div>
    <div class="card-body">
        <?php alertMessage() ?>
        <div class="table-responsive">
            <form action="code.php" method="POST">

              <?php
                $paramValue = checkParamId('id');
                if(!is_numeric($paramValue))
                {
                    echo '<h5>'.$paramValue.'</h5>';
                    return false;
                }
                  //echo $paramValue;
                $category = getById('categories', $paramValue);
                if($category['status'] == 200)
                {
                    
                }
              ?>
                <div class="row">
                    <div class="col-md-12 mb-3">
                         <label for="">Name *</label>
                         <input type="text" name="name" required class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                         <label for="">Description </label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Status (Unchecked=Visible, Checked=Hidden)</label>
                        <br>
                        <input type="checkbox" name="status" style="width:30px;height:30px">

                    </div>
                    <div class="col-md-6 mb-3 text-end">
                        <br>
                         <button type="submit" name="updateCategory" class="btn btn-primary">Save</button>
                                      
                        </div>
                                  
                    </div>
                               
                </form>
                        
            </div>


                     
        </div>
                    
    </div>
                  
</div>

<?php include('includes/footer.php')  ?>