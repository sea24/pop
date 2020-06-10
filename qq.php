<?php
/**
 * Created by PhpStorm.
 * User: yanghailong
 * Date: 2020/4/2
 * Time: 2:06 PM
 */
include "vendor/autoload.php";
echo "<pre>";

use PhpImap\Mailbox;
use PhpImap\Exceptions\ConnectionException;


$mailbox = new Mailbox(
    '{imap.qq.com:993/imap/ssl}', // IMAP server and mailbox folder
    '3010017695@qq.com', // Username for the before configured mailbox
    'hgrwtimexgmsdfcf', // Password for the before configured username
    __DIR__, // Directory, where attachments will be saved (optional)
    'UTF-8' // Server encoding (optional)
);

$mailbox->setServerEncoding('UTF-8');

try {
    $mail_ids = $mailbox->searchMailbox('ALL');
} catch (ConnectionException $ex) {
    die("IMAP connection failed: " . $ex->getMessage());
} catch (Exception $ex) {
    die("An error occured: " . $ex->getMessage());
}

echo "<h1>展示最近时间的邮件，内容太多已屏蔽</h1>";
$i = 0;
foreach ($mail_ids as $mail_id) {
    try {

        $email = @$mailbox->getMail(
            $mail_id, // ID of the email, you want to get
            false // Do NOT mark emails as seen (optional)
        );
        $i = $i + 1;
        echo sprintf("<h2>第%s封</h2>", $i);
        //echo "from-name: " . (isset($email->fromName)) ? $email->fromName : $email->fromAddress . "\n";
        if (isset($email->fromAddress)) {
            $shou = array_keys($email->to);
            echo '发送人：' . $email->fromAddress . "\r";
            echo '接收人：' . $shou[0] . "\r";
            echo '邮件标题：' . $email->senderName . "\r";
        }


    } catch (\Exception $e) {
        continue;
    }


    /*print_r($email->headers->fromaddress);
    exit;
    echo "from-email: " . $email->fromAddress . "\n";
    echo "to: " . $email->to . "\n";
    echo "subject: " . $email->subject . "\n";
    echo "message_id: " . $email->messageId . "\n";

    echo "mail has attachments? ";
    if ($email->hasAttachments()) {
        echo "Yes\n";
    } else {
        echo "No\n";
    }

    if (!empty($email->getAttachments())) {
        echo count($email->getAttachments()) . " attachements\n";
    }
    if ($email->textHtml) {
        echo "Message HTML:\n" . $email->textHtml;
    } else {
        echo "Message Plain:\n" . $email->textPlain;
    }

    if (!empty($email->autoSubmitted)) {
        // Mark email as "read" / "seen"
        $mailbox->markMailAsRead($mail_id);
        echo "+------ IGNORING: Auto-Reply ------+\n";
    }

    if (!empty($email_content->precedence)) {
        // Mark email as "read" / "seen"
        $mailbox->markMailAsRead($mail_id);
        echo "+------ IGNORING: Non-Delivery Report/Receipt ------+\n";
    }*/
}

$mailbox->disconnect();
exit;