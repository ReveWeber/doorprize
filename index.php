<?php
try { 
    require_once( 'includes/db.php'); 
} catch (Exception $e) { 
    $error=$e->getMessage(); 
}
require_once( 'includes/functions.php' );
if (isset($_POST['email'])) {
    $message = submit_entry($_POST, $db);
}
?><!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <link href="style.css" type="text/css" rel="stylesheet">
        <script src="includes/jquery-1.11.3.min.js"></script>
        <title>Aquilino Arts at the Boston Small Business Expo 2015
        </title>
    </head>

    <body>
        <img src="includes/Raffle_GoldScroll.png" class="top-right">

        <div class="form-area" id="form-area">
            <h2>Enter our device charger drawing!</h2>
            <div id="form-itself">
                <?php if (isset($message)) {?>
                <h2 class="thanks"><?php echo $message; ?></h2>
                <script>
                   setTimeout(function () {
                      $('#form-itself').load('entry-form.html');
                   }, 2500);
                </script>
            <?php } else {
                include('entry-form.html');
            } ?>
            </div>
            <img src="includes/AArts_logo_v3_forWEB.png">
        </div>
        <!--.form-area-->

        <img src="includes/Raffle_GoldScroll.png" class="bottom-left">
    </body>

    </html>
