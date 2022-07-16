<?php

include('./Examination.php');
date_default_timezone_set('Asia/Yangon');


$exam = new Examination;

if (isset($_POST["page"])) {
  extract($_POST);

  if ($page == "register") {
    if ($action == "registration") {
      $output;

      $exam->query = "
                SELECT * FROM admin_table
                WHERE admin_email_address = '{$admin_email_address}'
            ";

      $total_row = $exam->total_row();

      if ($total_row != 0) {
        $output = [
          "success" => false,
          "error_status" => "email_duplication"
        ];
        echo json_encode($output);

        return;
      }

      $current_datetime = date("Y-m-d") . ' ' . date("H:i:s", strtotime(date("h:i:s")));

      $admin_vertification_code = md5(rand());

      $exam->data = [
        ":admin_email_address" => $admin_email_address,
        ":admin_password" => password_hash($admin_password, PASSWORD_DEFAULT),
        ":admin_vertification_code" => $admin_vertification_code,
        ":admin_type" => "sub_master",
        ":admin_created_on" => $current_datetime
      ];

      $exam->query = "
                INSERT INTO
                    admin_table(
                        admin_email_address,
                        admin_password,
                        admin_vertification_code,
                        admin_type,
                        admin_created_on
                    )
                VALUES
                    (
                        :admin_email_address,
                        :admin_password,
                        :admin_vertification_code,
                        :admin_type,
                        :admin_created_on
                    )";

      $exam->execute_query();

      $output = [
        "success" => true
      ];

      echo json_encode($output);
    }
  }

  if ($page == "login") {
    if ($action == "login") {
      $output;

      $exam->data = [
        ":admin_email_address" => $admin_email_address,
      ];

      $exam->query = "
            SELECT * FROM admin_table
            WHERE admin_email_address = :admin_email_address
        ";

      $total_row = $exam->total_row();

      if ($total_row > 0) {
        $result = $exam->query_result();
        foreach ($result as $row) {
          if ($row["email_vertified"] == "yes") {
            if (password_verify($admin_password, $row['admin_password'])) {
              $_SESSION['admin_id'] = $row['admin_id'];
              $output = [
                "success" => true,
              ];
            } else {
              $output = [
                "success" => false,
                "error_status" => "unrecognized_email"
              ];
            }
          }
        }
      } else {
        $output = [
          "success" => false,
          "error_status" => "unrecognized_email"
        ];
      }
      echo json_encode($output);
    }
  }

  if ($page = "exam") {
    if ($_POST["action"] == "fetch") {
      $output = [];
      $condition = "";
      $order = "";
      $extra_query = "";

      // search
      if (isset($_POST["search"]["value"])) {
        $searchValue = $_POST["search"]["value"];
        $condition = "
          online_exam_title LIKE '{$searchValue}%'
          OR online_exam_datetime LIKE '{$searchValue}%'
          OR online_exam_duration LIKE '{$searchValue}%'
          OR total_question LIKE '{$searchValue}%'
          OR marks_per_right_answer LIKE '${searchValue}%'
          OR online_exam_status LIKE '{$searchValue}%'
        ";
      }

      // column order
      if (isset($_POST['order'])) {
        $order_column_index = $_POST["order"][0]["column"];
        $order_value = $_POST["order"][0]["dir"];
        $order_column = "";

        switch ($order_column_index) {
          case 0:
            $order_column = "online_exam_title";
            break;
          case 1:
            $order_column = "online_exam_datetime";
            break;
          case 2:
            $order_column = "online_exam_duration";
            break;
          case 3:
            $order_column = "total_question";
            break;
          case 4:
            $order_column = "marks_per_right_answer";
            break;
          case 5:
            $order_column = "online_exam_status";
            break;
          default:
            break;
        }

        $order = "ORDER BY {$order_column} {$order_value}";
      } else {
        $order = "ORDER BY online_exam_id DESC";
      }


      // extra query for row limit
      if ($_POST["length"] != -1) {
        $start = $_POST["start"];
        $count = $_POST["length"];
        $extra_query = "LIMIT {$start}, {$count}";
      }

      $exam->query = "
        SELECT * FROM online_exam_table
        WHERE admin_id = '{$_SESSION["admin_id"]}'
        AND ( $condition )
        {$order}
        {$extra_query}
      ";

      // for exam rows
      $filtered_row = $exam->total_row();

      $result = $exam->query_result();

      $exam->query = "
        SELECT * FROM online_exam_table
        WHERE admin_id = {$_SESSION["admin_id"]}
      ";

      // for user rows
      $total_row = $exam->total_row();

      $data = [];
      foreach ($result as $row) {
        $status = "";
        $edit_button = "";
        $delete_button = "";
        $sub_array = [];

        if ($row["online_exam_status"] == "pending") {
          $status = "pending";
        } else if ($row["online_exam_status"] == "created") {
          $status = "created";
        } else if ($row["online_exam_status"] == "started") {
          $status = "started";
        } else if ($row["online_exam_status"] == "completed") {
          $status = "completed";
        }

        if ($exam->is_exam_is_not_started($row["online_exam_id"])) {
          $edit_button = "
            <button name='edit' id='{$row["online_exam_id"]}' style='padding: 1px;'>
              <i class='bx bx-edit-alt edit'></i>
            </button>
          ";
        }

        $delete_button = "
            <button name='delete' id='{$row["online_exam_id"]}' style='padding: 1px;'>
              <i class='bx bx-trash delete'></i>
            </button>
          ";

        $online_exam_title = $row["online_exam_title"];
        $online_exam_datetime = $row["online_exam_datetime"];
        $online_exam_duration = $row["online_exam_duration"];
        $total_question = $row["total_question"];
        $marks_per_right_answer = $row["marks_per_right_answer"];

        $sub_array[] = html_entity_decode($online_exam_title);
        $sub_array[] = $online_exam_datetime;
        $sub_array[] = "{$online_exam_duration} Minutes";
        $sub_array[] = "{$total_question} Question/s";
        $sub_array[] = "{$marks_per_right_answer} Mark/s";
        $sub_array[] = $status;
        $sub_array[] = $edit_button;
        $sub_array[]  = $delete_button;

        $data[] = $sub_array;
      }

      $output = array(
        "draw" => intval($_POST["draw"]),
        "recordsTotal" => $total_row,
        "recordsFiltered" => $filtered_row,
        "data" => $data
      );

      echo json_encode($output);
    }

    if ($_POST["action"] == "add") {
      $current_datetime = date("Y-m-d") . ' ' . date("H:i:s", strtotime(date("h:i:s")));

      $exam->data = array(
        ":admin_id" => $_SESSION["admin_id"],
        ":online_exam_title" => $online_exam_title,
        ":online_exam_datetime" => $online_exam_datetime,
        ":online_exam_duration" => $online_exam_duration,
        ":total_question" => $total_question,
        ":marks_per_right_answer" => $marks_per_right_answer,
        ":marks_per_wrong_answer" => $marks_per_wrong_answer,
        "online_exam_created_on" => $current_datetime,
        "online_exam_status" => "pending",
        "online_exam_code" => md5(rand())
      );

      $exam->query = "
        INSERT INTO online_exam_table(admin_id, online_exam_title, online_exam_datetime, online_exam_duration, total_question, marks_per_right_answer, marks_per_wrong_answer, online_exam_created_on, online_exam_status, online_exam_code)
        VALUES (
          :admin_id,
          :online_exam_title,
          :online_exam_datetime,
          :online_exam_duration,
          :total_question,
          :marks_per_right_answer,
          :marks_per_wrong_answer,
          :online_exam_created_on,
          :online_exam_status,
          :online_exam_code
        );
      ";

      $exam->execute_query();

      $output = array(
        "success" => true
      );

      echo json_encode($output);
    }

    if ($_POST["action"] == "edit_fetch") {
      $output = array();
      $exam->query = "
        SELECT * FROM online_exam_table
        WHERE online_exam_id = '{$_POST["exam_id"]}'
      ";

      $result = $exam->query_result();

      foreach ($result as $row) {
        $output["online_exam_id"] = $row["online_exam_id"];
        $output["online_exam_title"] = $row["online_exam_title"];
        $output["online_exam_datetime"] = $row["online_exam_datetime"];
        $output["online_exam_duration"] = $row["online_exam_duration"];
        $output["total_question"] = $row["total_question"];
        $output["marks_per_right_answer"] = $row["marks_per_right_answer"];
        $output["success"] = true;
      }

      echo json_encode($output);
    }

    if ($_POST["action"] == "edit") {
      extract($_POST);
      $exam->data = array(
        ":online_exam_title" => $online_exam_title,
        ":online_exam_datetime" => $online_exam_datetime,
        ":online_exam_duration" => $online_exam_duration,
        ":total_question" => $total_question,
        ":marks_per_right_answer" => $marks_per_right_answer,
        ":marks_per_wrong_answer" => $marks_per_wrong_answer,
        ":online_exam_id" => $online_exam_id
      );

      $exam->query = "
        UPDATE
          online_exam_table
        SET 
          online_exam_title = :online_exam_title,
          online_exam_datetime = :online_exam_datetime,
          online_exam_duration = :online_exam_duration,
          total_question = :total_question,
          marks_per_right_answer = :marks_per_right_answer,
          marks_per_wrong_answer = :marks_per_wrong_answer
        WHERE online_exam_id = :online_exam_id
      ";

      $exam->execute_query($exam->data);
      $output = array(
        "success" => true
      );

      echo json_encode($output);
    }

    if ($_POST["action"] == "delete") {
      $exam->data = array(
        ":online_exam_id" => $_POST["exam_id"]
      );

      $exam->query = "
        DELETE FROM online_exam_table WHERE online_exam_id = :online_exam_id
      ";

      $exam->execute_query();

      $output = array(
        "success" => true
      );

      echo json_encode($output);
    }
  }
}