<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    $to = "socidepnwp@gmail.com";
    $subject = "වෙබ් අඩවියෙන් නව ප්‍රතිචාරයක්";
    $body = "නම: $name\nඊමේල්: $email\nපණිවිඩය:\n$message";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo "<h2 class='text-center py-5'>ඔබගේ ප්‍රතිචාරය සාර්ථකව එවා ඇත. ස්තූතියි!</h2>";
    } else {
        echo "<h2 class='text-center py-5'>දෝෂයක් ඇති විය. නැවත උත්සාහ කරන්න.</h2>";
    }
}
?>
<a href="index.php" class="btn btn-primary d-block mx-auto w-25">නැවත මුල් පිටුවට</a>