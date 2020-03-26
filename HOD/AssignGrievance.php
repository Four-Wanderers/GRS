<?php
    session_start();
    // $_SESSION["id"] = 103;
    // $_SESSION["dept_id"] = 2;
    // $_SESSION["username"] = "Mansi";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Assign Grievances</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <script src="AssignGrievance.js"></script>
    </head>
    <!-- onload="callback('controller.php?action=staffs&dept_id=<?php echo $_SESSION['dept_id']?>', getStaffs)" -->
    <body onload="setup('<?php echo $_SESSION['dept_id']?>')">
        <div class="container-fluid">
            <br><br>
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <h2>Assign Grievances</h2>
                    <br>
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">Assign Staff</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeReset()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="ticket_id">
                                <div class = "row" style="margin-top:-10px; margin-bottom:10px; margin-left:8px">
                                    <div class = "col-sm-4">Staff ID</div>
                                    <div class = "col-sm-4">Username</div>
                                    <div class = "col-sm-4">Year</div>
                                </div>
                                <div class="btn-group-vertical btn-group-toggle" data-toggle="buttons" id="staffs"></div>
                            </div>
                            <p id="success" class="text-success" style="font-size:small; margin-left:20px" style="display:none;"></p>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-success" onclick="selfAssign(<?php echo $_SESSION['id']?>)">Self Assign</button>
                                <button type="button" class="btn btn-outline-primary" onclick="assignToStaff()">Assign to Staff</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    <br>
                    <div id="grievances" class="table-responsive">
                        <h3 id = "nogrievance" style="display: none;">No Grievances</h3>
                        <table id = "customtable" class="table table-striped table-bordered table-sm">
                            <thead class="thead-dark" align="center">
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Title</th>
                                    <th>Year</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <!-- <tbody>
                                <tr>
                                    <td>RS134170</td>
                                    <td>Bobs And Vagana</td>
                                    <td>2019</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#exampleModalCenter">Assign</button>
                                    </td>
                                </tr>
                            </tbody> -->
                        </table>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </body>
</html>
