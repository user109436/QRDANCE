<?php
include('../../private/config.php');
include('./shared/header.php');
$account_types = [3, 4];
pageRestrict($account_types, "../", true);
?>

<body>
    <?php
    include('./shared/sidebar.php');
    $el = ' <a onclick="addData()" class="text-center addBtnBR " data-toggle="modal" data-target="#modalAdd"><i class="fa-3x text-success fas fa-plus"></i></a>';
    adminOnly($el);
    ?>

    <!-- Start your project here-->
    <!-- Modal Add -->
    <div class="modal fade" id="modalAdd" style="z-index:3000;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Staffs</h4>
                    <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="container-fluid message"></div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="formAdd" enctype="multipart/form-data">

                    <div class="modal-body mx-3">
                        <!-- firstname -->
                        <div class="md-form mb-5">
                            <input type="text" id="defaultForm-fname" class="form-control validate fields input" name="staffInfo[0]">
                            <label class="label" data-error="wrong" data-success="right" for="defaultForm-fname">First Name</label>
                        </div>
                        <!--middle name/intial  -->
                        <div class="md-form mb-4">
                            <input type="text" id="defaultForm-mname" class="form-control validate fields input" name="staffInfo[1]">
                            <label class="label" data-error="wrong" data-success="right" for="defaultForm-mname">Middle Initial</label>
                        </div>
                        <!-- last name -->
                        <div class="md-form mb-4">
                            <input type="text" id="defaultForm-lname" class="form-control validate fields input" name="staffInfo[2]">
                            <label class="label" data-error="wrong" data-success="right" for="defaultForm-lname">Last Name</label>
                        </div>
                        <!-- tags -->
                        <div class="md-form">
                            <textarea id="form6" class="md-textarea form-control fields input" rows="3" placeholder="(ex. Master of Arts)" name="staffInfo[3]"></textarea>
                            <label for="form6">Tags</label>
                        </div>
                        <!-- about -->
                        <div class="md-form">
                            <textarea id="form7" class="md-textarea form-control fields input" rows="3" placeholder="(ex. 10 years of expertise in the field of Computer Science)" name="staffInfo[4]"></textarea>
                            <label for="form7">About</label>
                        </div>
                        <!-- Account Type -->
                        <div class="md-form">
                            <select class="p-2 col-12 fields" name="staffInfo[5]">
                                <option value="2">Guard</option>
                                <option value="3">Professor</option>
                                <option value="4">Administrator</option>
                            </select>
                        </div>
                        <input type="file" class="dropify" name="profilePicture" id="profilePicture" accepts="image/*">
                        <div id="upload" class="container"></div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button id="create" class="btn btn-success p-3" type="submit" onclick="createData()"><i class="fas fa-plus-circle"></i> Create</button>
                        <button id="update" class="btn btn-secondary p-3" type="submit" onclick="updateData()"><i class="fas fa-save"></i> Update</button>
                        <button class="btn btn-danger p-3" id="resetForm" type="reset"><i class="fas fa-eraser"></i> Reset</button>
                        <input type="hidden" name="s" value="-1" id="s">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Delete -->
    <div class="modal fade" id="modalDelete" style="z-index:3000;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Delete Staffs</h4>
                    <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="container-fluid message">
                    <h4 class="text-danger font-weight-bold">Are you sure you want to delete this?</h4>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="formDelete" enctype="multipart/form-data">
                    <div class="modal-footer d-flex justify-content-center">
                        <div class="row col-12">
                            <div id="dataDelete" class="col-12 text-center"></div>
                            <button class="btn btn-success  col-5" type="submit" data-dismiss="modal" onclick="deleteData()"><i class="fas fa-sign-in-alt"></i> Process</button>
                            <button class="btn btn-secondary col-6" data-dismiss="modal"><i class="fas fa-ban"></i> Cancel</button>
                            <input type="hidden" name="x" value="" id="x">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    $el = ' 
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col">
                <a onclick="addData()" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus-circle fa-lg"></i>
                    Staffs</a>
            </div>
            <!-- FIXME:grid && list-->
            <div class="col ">
                <i class="fas fa-th-list fa-lg p-3 float-right text-primary orientation" id="listView"></i>
                <i class="fas fa-th-large fa-lg p-3 float-right text-primary orientation" id="gridView"></i>
                </button>
            </div>
        </div>
    </div>
     ';
    adminOnly($el);
    ?>
    <div class="container message"></div>
    <div class="container-fluid">
        <div class="table-responsive">
            <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
                <thead class="blue white-text">
                    <tr>
                        <?php
                        $el = '<th class="th">Manipulate</th>';
                        adminOnly($el);
                        ?>
                        <th class="th">Profile</th>
                        <th class="th">Name
                        </th>
                        <th class="th">Account Type
                        </th>
                        <th class="th">Position
                        </th>
                        <th class="th">About
                        </th>
                        <th class="th">Last Modified by
                        </th>
                        <th class="th">Last Modified Date
                        </th>

                    </tr>
                </thead>
                <tbody id="result">
                </tbody>
            </table>
        </div>
    </div>
    <div class="container-fluid" id="staffResult">
    </div>

    <!-- End your project here-->

    </main>
    <!-- page-content" -->
    </div>
</body>


<?php

include('./shared/footer.php');
?>