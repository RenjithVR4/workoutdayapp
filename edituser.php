<?php 
  include 'includes/header.php';
?>

<body class="appbody">
    <!-- Welcome Section -->
    <div class="section-invert py-4">
        <h3 class="section-title text-center m-2" id="sectionTitle">Edit User</h3>
        <div class="container py-4">
            <div class="row justify-content-md-center px-4">
                <div class="col-md-6 mb-2  card">
                    <form id="edituserForm" class="m-20 justify-content-md-center" method="post">
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
                                <div class="mb-3 pull-left">
                                    <button type="submit" class="btn btn-success d-table ml-auto mr-auto" id="updateUser"><span> <i class="fa fa-pencil-square-o mr-1"></i></span>Update</button>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="mb-3 pull-right">
                                    <button type="button" class="btn btn-danger d-table ml-auto mr-auto" id="deleteUser" data-toggle="modal" data-target="#deleteuserModal"><span> <i class="fa fa-trash-o mr-1"></i></span>Delete</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal Body -->
            <div class="modal fade" id="deleteuserModal" tabindex="-1" role="dialog" aria-labelledby="deleteuserModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteuserModalLabel">Confirmation Message</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <strong>Do yu really want to delete the user?</strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Nope</button>
                            <button type="button" class="btn btn-success" id="confirmDelete" data-dismiss="modal">Yep</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script type="text/javascript" src="js/edituser.js"></script>
    <script type="text/javascript">
            var id = "<?php echo $_GET["userid"]; ?>";
            edituser.init(id);
    </script>
    <?php 
    include 'includes/footer.php';
    ?>