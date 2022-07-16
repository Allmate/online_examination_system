<?php

include("./Examination.php");

$exam = new Examination();

// if does not login => go login page.
$exam->admin_session_private();
