<?php 
  include 'includes/header.php';
?>

<body class="appbody">
    <!-- Welcome Section -->
    <div class="section-invert">
        <h3 class="section-title text-center m-5" id="sectionTitle">User List</h3>
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="table-responsive-md text-center">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Email</th>
                                <th>status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="content-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- / Contact Section -->
     <script type="text/javascript" src="js/users.js"></script>
    <?php 
    include 'includes/footer.php';
    ?>