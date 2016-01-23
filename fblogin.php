<?php
require_once './subtask/fb-path.inc.php';
$fb = new Facebook\Facebook([
  'app_id' => '1521684931494498',
  'app_secret' => '543706019bf2511bfd88a1977e542661',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email','public_profile']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://worldis4u.xyz/soclog/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>