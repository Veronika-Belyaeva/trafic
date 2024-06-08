<?php
function send_email($to, $subject, $message) {
    $headers = "From: traffic-laws@traffic-laws.ru\r\n";
    $headers .= "Reply-To: traffic-laws@traffic-laws.ru\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    mail($to, $subject, $message, $headers);
}
?>
