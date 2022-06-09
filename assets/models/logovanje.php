<?php

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

require_once "../../vendor/autoload.php";
$mailer = new Mailer(Transport::fromDsn("mailgun+smtp://USERNAME:PASSWORD@default"));

$email1 = (new Email())
    ->from("snezanajezdic68@gmail.com")
    ->replyTo("snezanajezdic68@gmail.com")
    ->to("nemanja.jezdic@yahoo.com")
    ->subject("test")
    ->text("test");
$mailer->send($email1);
exit();
session_start();
header("Content-type: application/json");
$response = ["success" => false, "message" => "Server internal error", "data" => []];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "../config/connection.php";


    try {
        $email = $_POST['email'];
        $lozinka = $_POST['lozinka'];

        $regEmail = "/^[a-z]((\.|-)?[a-z0-9]){2,}@[a-z]((\.|-)?[a-z0-9]+){2,}\.[a-z]{2,6}$/";
        $regPassword = "/^[a-z A-Z 0-9]{2,}$/";

        $greske = [];

        if (!preg_match($regEmail, $email)) {
            $greske["email"] = "Email nije validan";
        }
        if (!preg_match($regPassword, $lozinka)) {
            $greske["lozinka"] = "Lozinka nije validna";
        }

        $sifralozinka = md5($lozinka);

        if (count($greske)) {
            $response["message"] = "Validation error";
            $response["data"] = $greske;
            echo json_encode($response);
            http_response_code(400);
        } else {
            $upit1 = "SELECT * FROM users WHERE email=:email AND password=:lozinka";
            $prepare = $conn->prepare($upit1);
            $prepare->bindParam(":email", $email);
            $prepare->bindParam(":lozinka", $sifralozinka);
            $prepare->execute();

            $getuser = $prepare->fetch();

            if ($getuser == false) {
                $response["message"] = "Please enter valid user and password";
                echo json_encode($response);
                http_response_code(400);
                exit();
            }

            $_SESSION["user"] = $getuser;


            $response["success"] = true;
            $response["message"] = "You successfully loged in";
            echo json_encode($response);
            http_response_code(200);
        }
    } catch (PDOException $exception) {
        echo json_encode($response);
        http_response_code(500);
    }
} else {
    echo json_encode($response);
    http_response_code(404);
}
