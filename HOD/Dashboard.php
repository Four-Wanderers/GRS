<?php
session_start();
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="DashboardStyle.css">
    
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    
    <script>
        
         function myFunction(id)
         {
             var iframeid= document.getElementById("iframeWindow");
            switch(id)
            {
                case 'managestaff':
                {
                    iframeid.src="ManagingStaff.html";
                    break;
                }
                case 'customview':
                {
                    iframeid.src="customView.html";
                    break;
                }
                case 'home':
                {
                    iframeid.src="HomePage.html";
                    break;
                }
                case 'assignhodgrievances':
                {
                    iframeid.src="AssignGrievance.php";
                    break;
                }
            }   
         }
        </script>
        

</head>

<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Dashboard</h3>
            </div>

            <ul class="list-unstyled components">
                
                <li>
                    <a href="#"  onclick="myFunction('managestaff')">Manage Staff</a>
                </li>
                    <li>
                    <a href="#" onclick="myFunction('customview')">Custom View</a>
                </li>
                <li>
                    <a href="#" onclick="myFunction('assignhodgrievances')">Assign Grievances</a>
                </li>
            </ul>

            
        </nav>

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light fixed-top " style="background-color:#000033">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Toggle Sidebar</span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- <a class="navbar-brand" href="#" style="color: antiquewhite">Logo</a> -->
                        <ul class="nav navbar-nav ml-auto" >
                            <li class="nav-item active">
                                <a class="nav-link" href="#" value="home" id="navBar1" onclick="myFunction('home')"><i class="fa fa-home" style="margin-right:5px"></i>Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"id="navBar1"><i class="fa fa-bell" style="margin-right:5px"></i>Notifications</a>
                            </li>
                         
                            
                            <li class="nav-item dropdown" >
                                <a class="nav-link dropdown-toggle" href="#" id="bla" data-toggle="dropdown">
                                    <i class="fa fa-user" style="margin-right:10px"></i>
                                    <?php echo $_SESSION["username"]?>
                                </a>
                                <div class="dropdown-menu" id="bla2" >
                                  <a class="dropdown-item"  href="#">About</a>
                                  <a class="dropdown-item" href="#">Change Password</a>
                                  <a class="dropdown-item" href="#">Logout</a>
                                </div>
                              </li>

                        </ul>
                    </div>
                </div>
            </nav>
             <br><br>
            
           <div class="container-fluid" id="view">
               <br>
            <iframe src="HomePage.html" style="border: none;height:100vh"id="iframeWindow" width="100%"  scrolling=no ></iframe>
           
           </div>
           
        </div><!--content end-->
    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>


    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar, #content').toggleClass('active');
            });
        });
    </script>
</body>

</html>