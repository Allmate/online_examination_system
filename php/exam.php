<?php

// if does not login => go login page.
include('header.php');

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Online_Examination_System</title>
  <link rel="shortcut icon" href="#" type="image/x-icon" />

  <!-- Boxicons CDN Link -->
  <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />

  <!-- ionicons CDN link -->
  <script type="module" src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule="" src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.js"></script>

  <!-- jquery 3.6.0 -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <!-- jquery-ui 1.12.1 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js">
  </script>
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">

  <!-- data tables -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css" />
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js">
  </script>

  <!-- bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>

  <!-- custom style sheet -->
  <link rel="stylesheet" href="../assets/css/admin_dashboard.css" />

  <!-- custom script -->
  <script src="../assets/js/exam.js" defer></script>
</head>

<body>
  <div class="sidebar">
    <div class="logo-details">
      <i class="bx bxl-c-plus-plus icon"></i>
      <div class="logo_name">Adminstratior</div>
      <i class="bx bx-menu" id="btn"></i>
    </div>
    <ul class="nav-list">
      <li>
        <i class="bx bx-search"></i>
        <input type="text" placeholder="Search..." />
        <span class="tooltip">Search</span>
      </li>
      <li>
        <a href="#">
          <i class="bx bx-grid-alt"></i>
          <span class="links_name">Dashboard</span>
        </a>
        <span class="tooltip">Dashboard</span>
      </li>
      <li>
        <a href="#">
          <i class="bx bx-user"></i>
          <span class="links_name">User</span>
        </a>
        <span class="tooltip">User</span>
      </li>
      <li>
        <a href="#">
          <i class="bx bx-chat"></i>
          <span class="links_name">Messages</span>
        </a>
        <span class="tooltip">Messages</span>
      </li>
      <li>
        <a href="#">
          <i class="bx bx-pie-chart-alt-2"></i>
          <span class="links_name">Analytics</span>
        </a>
        <span class="tooltip">Analytics</span>
      </li>
      <li>
        <a href="#">
          <i class="bx bx-folder"></i>
          <span class="links_name">File Manager</span>
        </a>
        <span class="tooltip">Files</span>
      </li>
      <li>
        <a href="#">
          <i class="bx bx-cart-alt"></i>
          <span class="links_name">Order</span>
        </a>
        <span class="tooltip">Order</span>
      </li>
      <li>
        <a href="#">
          <i class="bx bx-heart"></i>
          <span class="links_name">Saved</span>
        </a>
        <span class="tooltip">Saved</span>
      </li>
      <li>
        <a href="#">
          <i class="bx bx-cog"></i>
          <span class="links_name">Setting</span>
        </a>
        <span class="tooltip">Setting</span>
      </li>
      <li class="profile">
        <div class="profile-details">
          <img src="../assets/images/profile.jpg" alt="profileImg" />
          <div class="name_job">
            <div class="name">Prem Shahi</div>
            <div class="job">Web designer</div>
          </div>
        </div>
        <i class="bx bx-log-out" id="log_out"></i>
      </li>
    </ul>
  </div>
  <section class="home-section">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-9">
            <h4 class="panel-title mt-2">Online Exam List</h4>
          </div>
          <div class="col-md-3" align="right">
            <button type="button" class="button" id="add_button" data-bs-toggle="modal" data-bs-target="#examModal">
              <span class="button__text">Add Item</span>
              <span class="button__icon">
                <ion-icon name="add-outline"></ion-icon>
              </span>
            </button>
          </div>
        </div>
      </div>
      <div class="card-body p-4">
        <span id="message_operation"></span>
        <div class="table-responsive">
          <table id="exam_data_table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Exam Title</th>
                <th>Date & Time</th>
                <th>Duration</th>
                <th>Total Question</th>
                <th>Right Answer Mark</th>
                <th>Status</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" id="examModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="examModalLabel">Add Exam Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="#" method="POST" id="exam_form">
            <div class="form-group mb-3">
              <div class="row">
                <label class="col-md-4 text-right">Exam Title <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input type="text" name="online_exam_title" id="online_exam_title" class="form-control" />
                </div>
              </div>
            </div>
            <div class="form-group mb-3">
              <div class="row">
                <label class="col-md-4 text-right">Exam Date & Time <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input type="text" name="online_exam_datetime" id="online_exam_datetime" class="form-control"
                    readonly />
                </div>
              </div>
            </div>
            <div class="form-group mb-3">
              <div class="row">
                <label class="col-md-4 text-right">Exam Duration <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <select name="online_exam_duration" id="online_exam_duration" class="form-control">
                    <option value="">Select Duration</option>
                    <option value="5">5 Minute</option>
                    <option value="10">10 Minutes</option>
                    <option value="25">25 Minutes</option>
                    <option value="30">30 Minutes</option>
                    <option value="60">1 Hour</option>
                    <option value="120">2 Hour</option>
                    <option value="180">3 Hour</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-3">
              <div class="row">
                <label class="col-md-4 text-right">Total Question <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <select name="total_question" id="total_question" class="form-control">
                    <option value="">Select Total Question</option>
                    <option value="5">5 Question</option>
                    <option value="10">10 Question</option>
                    <option value="15">15 Question</option>
                    <option value="25">25 Question</option>
                    <option value="50">50 Question</option>
                    <option value="100">100 Question</option>
                    <option value="200">200 Question</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-3">
              <div class="row">
                <label class="col-md-4 text-right">Marks for Right Answer
                  <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <select name="marks_per_right_answer" id="marks_per_right_answer" class="form-control">
                    <option value="">Select Marks for Right Answer</option>
                    <option value="1">+1 Mark</option>
                    <option value="2">+2 Mark</option>
                    <option value="3">+3 Mark</option>
                    <option value="4">+4 Mark</option>
                    <option value="5">+5 Mark</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-3">
              <div class="row">
                <label class="col-md-4 text-right">Marks for Wrong Answer
                  <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <select name="marks_per_wrong_answer" id="marks_per_wrong_answer" class="form-control">
                    <option value="">Select Mark for Wrong Answer</option>
                    <option value="1">-1 Mark</option>
                    <option value="1.25">-1.25 Mark</option>
                    <option value="1.50">-1.50 Mark</option>
                    <option value="2">-2 Mark</option>
                  </select>
                </div>
              </div>
            </div>
            <input type="hidden" name="online_exam_id" id="online_exam_id" />
            <input type="hidden" name="page" value="exam" />
            <input type="hidden" name="action" id="action" value="add" />
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
          </button>
          <input type="submit" id="button_action" form="exam_form" class="btn btn-primary" value="Add">
        </div>
      </div>
    </div>
  </div>

</body>

</html>