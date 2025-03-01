<?php

session_start();
session_destroy();
echo "<script>alert('Logout Successful'); window.location.href='../index.php';</script>";
exit;

