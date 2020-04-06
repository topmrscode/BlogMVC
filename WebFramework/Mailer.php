<?php
//----------------- MESSAGE CHECK YOU EMAIL BOX ----------------------

namespace WebFramework;

use \Mailjet\Resources;
  
class Mailer {

  private static $instance = null;
  public $client;

  /**
   * Private constructor so nobody else can instantiate it.
   */
  private function __construct()
  {
    $this->client = new \Mailjet\Client('3893cf02f559b08a6a0f4510e9d4a8aa','715d34decd71b67e94b3f43d3252a40d',true,['version' => 'v3.1']);

  }

  /**
   * Retrieve the static instance of the ORM.
   *
   * @return ORM - Instance of the ORM
   */
  public static function getInstance()
  {
      if (is_null(self::$instance)) {
          self::$instance = new Mailer();
      }

      return self::$instance;
  }

//------------------------CREATE THE TEMPLATE MAIL AND SEND IT-------------------------//
// exemple de template mailjet php, voir : https://dev.mailjet.com/email/guides/template-api/
  public function sendRegisterMail(string $toEmail, string $toUsername, string $token) {
    $body = [
      'Messages' => [
        [
          'From' => [
            'Email' => "laura123balma@gmail.com",
            'Name' => "Laura"
          ],
          'To' => [
            [
              'Email' => $toEmail,
              'Name' => $toUsername
            ]
          ],
          'Subject' => "Validate your acount",
          'TextPart' => "My confirm email link",
          'HTMLPart' => "<h3>Please confirm your adress mail clicking <a href='http://localhost:8888/user/verify?token=" .$token ."'>here</a>!</h3><br />". "if the link doesn't work use this link" . "http://localhost:8888/user/verify?token=" .$token,
          'CustomID' => "AppGettingStartedTest"
        ]
      ]
    ];
    $response = $this->client->post(Resources::$Email, ['body' => $body]);
    
  }
}