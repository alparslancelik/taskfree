<?php
use \Slim\Middleware\HttpBasicAuthentication\PdoAuthenticator;
use \Slim\Middleware\HttpBasicAuthentication\AuthenticatorInterface;

class AdminAuthenticator implements AuthenticatorInterface {
    public function __invoke(array $arguments) {
        return (bool)rand(0,1);
    }
}

class UserAuthenticator implements AuthenticatorInterface {
    public function __invoke(array $arguments) {
      if (isset($_SESSION['user_id'])) {
        return true;
      } else {
        return false;
      }
    }
}

function configureAuthentication($app) {
    $app->add(new \Slim\Middleware\HttpBasicAuthentication([
        "path" => ["/api/admin"],
        "authenticator" => new AdminAuthenticator(),
        "error" => function ($request, $response, $arguments) {
            return $response
                ->withRedirect("/signin")
                ->withoutHeader("WWW-Authenticate");
        }
    ]));
    $app->add(new \Slim\Middleware\HttpBasicAuthentication([
        "path" => ["/api/v1"],
        "passthrough" => [ 
            "/api/v1/status",
            "/api/v1/category",
            "/api/v1/task",
            "/api/v1/auth",
            "/api/v1/user",
            "/api/v1/search",
            "/api/v1/step"
        ],
        "authenticator" => new UserAuthenticator(),
        "error" => function ($request, $response, $arguments) {
            return $response
                ->withRedirect("/signin")
                ->withoutHeader("WWW-Authenticate");
        }
    ]));
}

?>
