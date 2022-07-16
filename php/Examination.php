<?php

ini_set('display_errors', 1);
error_reporting(~0);

class Examination
{
  var $host;
  var $username;
  var $password;
  var $database;
  var $connect;
  var $home_page;
  var $query;
  var $data;
  var $statement;
  var $filedata;

  function __construct()
  {
    $this->host = "localhost";
    $this->username = "root";
    $this->password = "";
    $this->database = "online_examination";
    $this->home_page = "http://localhost/online_examination_system";
    $this->connect = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);

    session_start();
  }

  function execute_query()
  {
    $this->statement = $this->connect->prepare($this->query);

    $this->statement->execute($this->data);
  }

  function query_result()
  {
    $this->execute_query();
    return $this->statement;
  }

  function total_row()
  {
    $this->execute_query();
    return $this->statement->rowCount();
  }


  function redirect($page)
  {
    header("location: $page");
  }

  function admin_session_private()
  {
    if (!isset($_SESSION["admin_id"])) {
      $this->redirect("./admin_login_register.php");
    }
  }

  function admin_session_public()
  {
    if (isset($_SESSION["admin_id"])) {
      $this->redirect("./index.php");
    }
  }

  function is_exam_is_not_started($online_exam_id)
  {
    $current_datetime = date("Y-m-d") . ' ' . date("H:i:s", strtotime(date("h:i:s")));

    $exam_datetime = "";

    $this->query = "
      SELECT online_exam_datetime FROM online_exam_table
      WHERE online_exam_id = '{$online_exam_id}'
    ";

    $result = $this->query_result();

    foreach ($result as $row) {
      $exam_datetime = $row["online_exam_datetime"];
    }

    if ($exam_datetime > $current_datetime) {
      return true;
    }

    return false;
  }
}