<?php
require_once( 'includes/db.php' );
require_once( 'includes/functions.php' );
if (isset($_POST['draw'])) {
    $message = draw_entry($db);
}
if (isset($_POST['list_all'])) {
    try {
        $sql = 'SELECT Name, Email, Organization, CellPhone
                FROM entries';
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $stmt->bindColumn('Name', $name);
        $stmt->bindColumn('Email', $email);
        $stmt->bindColumn('Organization', $organization);
        $stmt->bindColumn('CellPhone', $cellphone);
        $errorInfo = $stmt->errorInfo();
        if (isset($errorInfo[2])) {
            $error = $errorInfo[2];
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
    $list = array();
    $list[] = ['Name', 'Email', 'Organization', 'Cell Phone'];
    while ($stmt->fetch(PDO::FETCH_BOUND)) {
        $list[] = [$name, $email, $organization, $cellphone];
    }
    $fp = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="ExpoEntries.csv"');
    foreach ($list as $ferow) {
        fputcsv($fp, $ferow);
    }
	fclose($csvoutput);
	exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link href="style.css" type="text/css" rel="stylesheet">
    <title>Aquilino Arts at the Boston Small Business Expo 2015
        </title>
    </head>
    <body>
        <img src="includes/Raffle_GoldScroll.png" class="top-right">
        
        <div class="form-area" id="form-area">
        <?php if (isset($message)) { echo $message; }
        ?>
    
    <form class="draw-button" method="post">
<input class="button" type="submit" name="draw" value="Draw Winners" />
</form>
    
    <form class="list-button" method="post">
<input class="button" type="submit" name="list_all" value="List All Entrants" />
</form>
        
        </div><!--.form-area-->
    
        <img src="includes/Raffle_GoldScroll.png" class="bottom-left">
    </body>
</html>
