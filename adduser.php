<?php 
  include 'includes/header.php';
?>

<body class="appbody">
    <!-- Welcome Section -->
    <div class="section-invert py-4">
        <h3 class="section-title text-center m-2" id="sectionTitle">Add User</h3>
        <div class="container py-4">
            <div class="row justify-content-md-center px-4">
                <div class="col-md-6 mb-2  card">
                    <form id="adduserForm" class="m-20 justify-content-md-center" method="post">
                        <div class="row m-t-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="labeltitle" for="firstName">First Name</label>
                                    <input type="text" name="firstname" class="form-control" id="firstName" placeholder="Enter firstname of the user">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="labeltitle" for="lastName">Last Name</label>
                                    <input type="text" name="lastname" class="form-control" id="lastName" placeholder="Enter lastname of the user">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="labeltitle" for="email">Email</label>
                                    <input type="text" name="email" class="form-control" id="email" placeholder="Enter the email">
                                </div>
                            </div>
                        </div>
                        <div class="row  m-t-20">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success d-table ml-auto mr-auto" id="createUser"><span> <i class="fa fa-plus mr-1"></i></span>Create</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/adduser.js"></script>
    <?php 
    include 'includes/footer.php';
    ?>