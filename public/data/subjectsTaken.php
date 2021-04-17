  <?php
    include('../../private/config.php');
    $attendance = [];
    $sql = "SELECT subject_id, remarks FROM subject_attendance WHERE student_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $account_id);
    if ($stmt->execute() === TRUE) {
        $remarkResult = $stmt->get_result();
        if ($remarkResult->num_rows > 0) {
            while ($remarks = $remarkResult->fetch_assoc()) {
                $attendance[] = $remarks;
            }
        }
    } else {
        echo message("Error:" . $stmt->error . " sql=" . $sql);
        exit;
    }
    $sql = "SELECT subjects.name_of_subject, enrolled_subjects.subject_id FROM enrolled_subjects INNER JOIN subjects ON enrolled_subjects.subject_id=subjects.id
 WHERE enrolled_subjects.student_id=?";
    $stmtSubs = $conn->prepare($sql);
    $stmtSubs->bind_param("s", $account_id);

    ?>
  <!-- subjects -->
  <div class="container-fluid bg-white p-3">
      <hr>
      <p class="font-weight-bold text-center">SUBJECTS TAKEN</p>
      <hr>
      <div class="row">


          <?php
            $totalAttendance = [0, 0, 0, 0];
            if ($stmtSubs->execute() === TRUE) {
                $subjectResult = $stmtSubs->get_result();

                if ($subjectResult->num_rows > 0) {
                    //display data
                    while ($subjects = $subjectResult->fetch_assoc()) {
                        $attendanceCount = countTotalAttendance($attendance,  $subjects['subject_id']);
                        //overall performance
                        $totalAttendance[0] += $attendanceCount[0];
                        $totalAttendance[1] += $attendanceCount[1];
                        $totalAttendance[2] += $attendanceCount[2];
                        $totalAttendance[3] += $attendanceCount[3];

                        //subject performance
                        $sumAttendanceCount = sumRecords($attendanceCount);
                        $percentage = totalAttendancePercentage($attendanceCount);

                        //get the percentage for each mark

            ?>
                      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                          <!-- Card -->
                          <div class="card">

                              <!-- Card content -->
                              <div class="card-body">

                                  <!-- Title -->
                                  <p class="font-weight-bold"><a><?php echo $subjects['name_of_subject'] ?></a></p>
                                  <hr>
                                  <!-- percentage for each subject -->
                                  <p class="card-text">
                                      <span class="text-success mr-4">P <?php echo $present = $percentage[0] ?>%</span>
                                      <span class="text-danger mr-4">A <?php echo $percentage[1] ?>%</span>
                                      <span class="text-warning mr-4">L <?php echo $percentage[2] ?>%</span>
                                      <span class="text-info mr-4">E <?php echo $percentage[3] ?>%</span>
                                  </p>
                                  <hr>
                                  <!-- Number of Marks -->
                                  <p class="card-text">
                                      <span class="text-success mr-4">P <?php echo $attendanceCount[0] ?></span>
                                      <span class="text-danger mr-4">A <?php echo $attendanceCount[1] ?></span>
                                      <span class="text-warning mr-4">L <?php echo $attendanceCount[2] ?></span>
                                      <span class="text-info mr-4">E <?php echo $attendanceCount[3] ?></span>
                                  </p>
                                  <hr>
                                  <p class="text-uppercase font-weight-bold">Performance:
                                      <?php

                                        displayAttendancePerformance($present);

                                        ?>
                                  </p>

                              </div>

                          </div>
                          <!-- Card -->
                      </div>
          <?php
                    }
                } else {
                    echo "<h4>You don't yet have any enrolled Subjects</h4>";
                }
            } else {
                echo message("Error:" . $stmt->error . " sql=" . $sql);
                exit;
            }
            ?>
      </div>
  </div>
  <!-- overall subject performance -->
  <div class="container-fluid mt-5 mb-5">
      <div class="row text-center p-5 bg-white">
          <?php
            //percentage for each mark(PALE)
            $percentage = totalAttendancePercentage($totalAttendance);
            $present = $percentage[0];
            ?>
          <div class="col-12 ">
              <h4>Subject Overall Performance</h4>
              <hr>
          </div>
          <div class="col-6 col-sm-6 col-md-3 col-lg-3 text-success">
              <h6 class="font-weight-bold">Present <?php echo $present ?>%</h6>
          </div>
          <div class="col-6 col-sm-6 col-md-3 col-lg-3 text-danger">
              <h6 class="font-weight-bold">Absent <?php echo $percentage[1] ?>%</h6>
          </div>
          <div class="col-6 col-sm-6 col-md-3 col-lg-3 text-warning">
              <h6 class="font-weight-bold">Late <?php echo $percentage[2]  ?>%</h6>
          </div>
          <div class="col-6 col-sm-6 col-md-3 col-lg-3 text-info">
              <h6 class="font-weight-bold">Excuse <?php echo $percentage[3] ?>%</h6>
          </div>
          <div class="col-12 text-uppercase">
              <hr>
              <h4 class="font-weight-bold">Performance:
                  <?php
                    displayAttendancePerformance($present);

                    ?>
              </h4>
          </div>
      </div>
  </div>