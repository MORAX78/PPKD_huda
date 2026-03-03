<?php
function checkLogin()
{
    if (!isset($_SESSION['USERNAME'])) {
        header("location:index.php?");
        die();
    }
}
function statusLogin()
{
    if (isset($_GET['status']) && $_GET['status'] == 'failed') {
        echo '<div class="alert alert-danger" role="alert">
            Email dan Password Salah!
            </div>
            <script>
            setTimeout(function() {
            window.location.href = "index.php";
            }, 5000);
            </script>';
    }
}
