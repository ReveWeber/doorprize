<?php
function submit_entry($entry, &$db) {
    $name = $entry['entry-name'];
    if ($name == '' || $entry['email'] == '') {
        return "Both name and email are required to enter the drawing.";
    }
    $email = filter_var($entry['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        // we stripped away *all* content by filtering or email is invalid
        return "Name and email must both have valid content to enter the drawing.";
    }
    if ($entry['org'] == '') {
        $organization = 'not given';
    } else {
        $organization = $entry['org'];
    }
    if ($entry['cell'] == '') {
        $cellphone = 'not given';
    } else {
        $cellphone = filter_var($entry['cell'], FILTER_SANITIZE_NUMBER_INT);
        $cellphone = str_replace('-', '', $cellphone);
        $cellphone = str_replace('+', '', $cellphone);
        $cellphone = substr_replace($cellphone, '-', -7, 0);
        $cellphone = substr_replace($cellphone, '-', -4, 0);
        if (strlen($cellphone) > 12) {
            $cellphone = substr_replace($cellphone, ' ', -12, 0);
        }
    }
    try {
        $sql = "SELECT EntryID FROM entries WHERE Email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $stmt->bindColumn('EntryID', $entryid);
        $errorInfo = $stmt->errorInfo();
        if (isset($errorInfo[2])) {
            $error = $errorInfo[2];
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
    if ($entryid) {
        return "I'm sorry, it appears we already have an entry for that email address.";
    } else {
        try {
            $sql = "INSERT INTO entries 
                    (Name, Organization, Email, CellPhone)
                    VALUES (:name, :organization, :email, :cellphone)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':organization', $organization);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':cellphone', $cellphone);
            $stmt->execute();
            $errorInfo = $stmt->errorInfo();
            if (isset($errorInfo[2])) {
                $error = $errorInfo[2];
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        $entryid = $db->lastInsertId();
        if (!$entryid) {
            return "We're sorry, your entry failed to record. Please try again.";
        } else {
            return "Thank you, you have been entered into our drawing. Good luck!";
        }
    }
}

function draw_entry(&$db) {
    // draw 3 entries.
    try {
        $sql = 'SELECT count(*) as entrycount FROM entries';
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $errorInfo = $stmt->errorInfo();
        if (isset($errorInfo[2])) {
            $error = $errorInfo[2];
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
    $count = $stmt->fetchColumn();
    $entryid1 = mt_rand(1, $count);
    $entryid2 = mt_rand(1, $count);
    while ($entryid2 == $entryid1) {
        $entryid2 = mt_rand(1, $count);
    }
    $entryid3 = mt_rand(1, $count);
    while ($entryid3 == $entryid1 || $entryid3 == $entryid2) {
        $entryid3 = mt_rand(1, $count);
    }
    try {
        $sql = 'SELECT Name, Organization, Email, CellPhone 
                FROM entries
                WHERE EntryID IN (:entryid1, :entryid2, :entryid3)';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':entryid1', $entryid1);
        $stmt->bindValue(':entryid2', $entryid2);
        $stmt->bindValue(':entryid3', $entryid3);
        $stmt->execute();
        $stmt->bindColumn('Name', $name);
        $stmt->bindColumn('Organization', $organization);
        $stmt->bindColumn('Email', $email);
        $stmt->bindColumn('CellPhone', $cellphone);
        $errorInfo = $stmt->errorInfo();
        if (isset($errorInfo[2])) {
            $error = $errorInfo[2];
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
    $stmt->fetch(PDO::FETCH_BOUND);
    if ($name) {
        $message = '<div class="winner-list"> Winners, solar charger first:';
        do {
        $message .= '<div class="winner">';
        $message .= 'Name: ' . htmlentities  ( $name, ENT_QUOTES ) . '<br>';
        $message .= 'Email: ' . htmlentities( $email, ENT_QUOTES ) . '<br>';
        $message .= 'Organization: ' . htmlentities( $organization, ENT_QUOTES ) . '<br>';
        $message .= 'Cell Phone: ' . htmlentities( $cellphone, ENT_QUOTES ) . '<br>';
        $message .= '</div>';
        } while($stmt->fetch(PDO::FETCH_BOUND));
        $message .= '</div>';
    } else {
        $message = "Error choosing winner. Please try again.";
    }
    return $message;
}
