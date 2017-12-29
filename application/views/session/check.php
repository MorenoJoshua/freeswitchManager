<?php
session_start();
if (isset($_SESSION['active']) AND $_SESSION['active'] != '') {
} else {
    unset($_SESSION);
    session_destroy();
    header('location: ../');
}