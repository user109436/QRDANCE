 <script src="./node_modules/mdbootstrap/js/jquery.min.js"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <script src="./node_modules/mdbootstrap/js/mdb.min.js"></script>
 <script src="./node_modules/mdbootstrap/DataTables/datatables.min.js"></script>

 <?php
  if (isset($account_type) && !empty($account_type)) {


  ?>
   <script src="./node_modules/mdbootstrap/js/dataTables.buttons.min.js"></script>
   <script src="./node_modules/mdbootstrap/js/buttons.flash.min.js"></script>
   <script src="./node_modules/mdbootstrap/js/jszip.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
   <script src="./node_modules/mdbootstrap/js/vfs_fonts.js"></script>
   <script src="./node_modules/mdbootstrap/js/buttons.html5.min.js"></script>
   <script src="./node_modules/mdbootstrap/js/buttons.print.min.js"></script>
   <script src="./node_modules/mdbootstrap/js/buttons.colVis.min.js"></script>
 <?php
  }
  ?>
 <script src="./node_modules/aos/dist/aos.js"></script>
 <script src="./node_modules/mdbootstrap/js/client.js"></script>
 <?php
  ob_end_flush();
  ?>