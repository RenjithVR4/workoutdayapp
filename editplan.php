<?php 
  include 'includes/header.php';
?>

<body class="appbody">
    <!-- Welcome Section -->
    <div class="section-invert py-4">
        <h3 class="section-title text-center m-2" id="sectionTitle">Edit Plan</h3>
        <div class="container py-4">
            <form id="editplanForm" method="post">
                <div class="row justify-content-md-center px-4">
                    <div class="col-sm-12 col-md-10 col-lg-7 mb-4 card">
                        <div class="row m-t-20">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label class="labeltitle" for="planName">Name of the plan</label>
                                    <input type="text" name="planname" class="form-control" id="planName" placeholder="Enter name of the day">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group mb-4">
                                    <label class="labeltitle" for="userName">User(s)</label>
                                    <div class="row users" id="users">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-md-center m-t-30">
                    <div class="col-sm-12 col-md-10 col-lg-8">
                        <div class="mb-5">
                            <button type="button" class="btn btn-primary d-table ml-auto mr-auto" id="addDay"><span> <i class="fa fa-plus mr-1"></i></span>Add Day</button>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-md-center m-t-30">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="row justify-content-md-center" id="excerciseDays">
                        </div>
                    </div>
                </div>
                <div class="row justify-content-md-center m-t-30">
                    <div class="col-sm-12 col-md-10 col-lg-8">
                        <div class="row mb-5">
                            <button type="submit" class="btn btn-success btn-pill btn-lg d-table ml-auto mr-auto" id="savePlan"><span> <i class="fa fa-floppy-o mr-1"></i></span>Save</button>
                            <button type="submit" class="btn btn-danger btn-pill btn-lg d-table ml-auto mr-auto" id="deletePlan"><span> <i class="fa fa-floppy-o mr-1"></i></span>Delete</button>
                        </div>
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="js/editplan.js"></script>
     <script type="text/javascript">
            var id = "<?php echo $_GET["planid"]; ?>";
            editplan.init(id);
    </script>
    <?php 
    include 'includes/footer.php';
    ?>