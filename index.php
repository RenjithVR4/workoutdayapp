<?php 
  include 'includes/header.php';
?>

<body class="appbody">
    <!-- Welcome Section -->
    <div class="section-invert py-4">
        <h3 class="section-title text-center m-2" id="sectionTitle">Plan List</h3>
        <div class="container py-4">
            <div class="row justify-content-md-center px-4 plan" id="plans">
                
            </div>
            
            <div class="row justify-content-md-center px-4">
                <div class="col-sm-12 col-md-10 col-lg-7 p-4 mb-4">
                    <div class="text-center">
                        <a href="addplan.php" class="btn btn-primary "><span><i class="fa fa-plus mr-1"></i></span>Add Plan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <script type="text/javascript" src="js/index.js"></script>
    <?php 
    include 'includes/footer.php';
    ?>