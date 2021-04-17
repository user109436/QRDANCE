<?php
include('../../private/config.php');
include('./shared/header.php');
$account_types = [4];
pageRestrict($account_types, "../", true);
?>

<body>
    <?php
    include('./shared/sidebar.php');
    ?>
    <a onclick="addData('sections')" class="text-center addBtnBR" data-toggle="modal" data-target="#modalAdd"><i class="fa-3x text-success fas fa-plus"></i></a>

    <!-- Start your project here-->
    <!-- Modal Add -->
    <div class="modal fade" id="modalAdd" style="z-index:3000;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Section</h4>
                    <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="container-fluid message"></div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="formAdd" enctype="multipart/form-data">

                    <div class="modal-body mx-3">
                        <!-- Section -->
                        <div class="md-form mb-5">
                            <input type="text" id="Section" class="form-control validate fields input" name="section[0]">
                            <label class="label" data-error="wrong" data-success="right" for="Section">Section</label>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button id="create" class="btn btn-success p-3" type="submit" onclick="createData('sections')"><i class="fas fa-plus-circle"></i> Create</button>
                        <button id="update" class="btn btn-secondary p-3" type="submit" onclick="updateData('sections')"><i class="fas fa-save"></i> Update</button>
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
                    <h4 class="modal-title w-100 font-weight-bold">Delete Section</h4>
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
                            <button class="btn btn-success col-5" type="submit" data-dismiss="modal" onclick="deleteData('sections')"><i class="fas fa-sign-in-alt"></i> Process</button>
                            <button class="btn btn-secondary col-6" data-dismiss="modal"><i class="fas fa-ban"></i> Cancel</button>
                            <input type="hidden" name="x" value="" id="x">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col">
                <a onclick="addData('sections')" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus-circle fa-lg"></i>
                    Section</a>
            </div>
        </div>
    </div>
    <div class="container message"></div>
    <div class="container-fluid">
        <div class="table-responsive">
            <table class="table table-striped DataFromDB" cellspacing="0" width="100%">
                <thead class="blue white-text">
                    <tr>
                        <th class="th">Manipulate</th>
                        <th class="th">Section</th>
                        <th class="th-sm">Last Modified by
                        </th>
                        <th class="th-sm">Last Modified Date
                        </th>

                    </tr>
                </thead>
                <tbody id="sectionResult">
                </tbody>
            </table>
        </div>
    </div>
    <!-- End your project here-->

    </main>
    <!-- page-content" -->
    </div>
</body>


<?php

include('./shared/footer.php');
?>