<?php

namespace Source\App;

use League\Plates\Engine;
use Source\App\Api\Faqs;
use Source\Models\Faq\Question;
use Source\Core\Email;
use Source\Core\Connect;
use Source\Models\User;
use Exception;

class Web
{
    private $view;

    public function __construct()
    {
        $this->view = new Engine(__DIR__ . "/../../themes/web","php");
    }
    public function forgotPassword(): void
    {
        echo $this->view->render("forgot-password");
    }

    public function handleForgotPassword(array $data): void
    {
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            // Idealmente, retornar um JSON de erro para a API
            return;
        }

        $user = (new User())->find("email = :e", "e={$data['email']}")->fetch();
        if (!$user) {
            // Mensagem de sucesso para não revelar e-mails cadastrados
            header('Location: ' . url('login?success=reset_sent'));
            exit;
        }

        try {
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

            $stmt = Connect::getInstance()->prepare(
                "INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, ?)"
            );
            $stmt->execute([$data['email'], $token, $expires]);

            $resetLink = url('redefinir-senha/' . $token);
            $body = "<h1>Recuperação de Senha</h1><p>Clique no link para redefinir sua senha: <a href='{$resetLink}'>Redefinir Senha</a></p><p>Este link expira em 1 hora.</p>";

            $email = new Email();
            if ($email->sendEmail($data['email'], 'Recuperação de Senha - Clube de Heróis', $body)) {
                header('Location: ' . url('login?success=reset_sent'));
            } else {
                header('Location: ' . url('esqueci-a-senha?error=send_failed'));
            }
            exit;

        } catch (Exception $e) {
            error_log($e->getMessage());
            header('Location: ' . url('esqueci-a-senha?error=server_error'));
            exit;
        }
    }

    public function resetPassword(array $data): void
    {
        $token = $data['token'] ?? '';
        $stmt = Connect::getInstance()->prepare(
            "SELECT * FROM password_reset_tokens WHERE token = ? AND used = 0 AND expires_at > NOW()"
        );
        $stmt->execute([$token]);
        $tokenData = $stmt->fetch();

        if (!$tokenData) {
            header('Location: ' . url('login?error=invalid_token'));
            exit;
        }

        echo $this->view->render("reset-password", ["token" => $token]);
    }

    // source/App/Web.php

public function handleResetPassword(array $data): void
{
    if (empty($data['token']) || empty($data['password']) || $data['password'] !== $data['password_confirm']) {
        header('Location: ' . url('redefinir-senha/' . $data['token'] . '?status=mismatch'));
        exit;
    }

    $stmt = Connect::getInstance()->prepare(
        "SELECT * FROM password_reset_tokens WHERE token = ? AND used = 0 AND expires_at > NOW()"
    );
    $stmt->execute([$data['token']]);
    $tokenData = $stmt->fetch();

    if (!$tokenData) {
        header('Location: ' . url('login?status=invalid_token'));
        exit;
    }

    $user = (new User())->find("email = :e", "e={$tokenData->email}")->fetch();
    if ($user) {
        $userModel = new User($user->id);

        if($userModel->update(['password' => $data['password']])) {
            $stmt = Connect::getInstance()->prepare(
                "UPDATE password_reset_tokens SET used = 1 WHERE token = ?"
            );
            $stmt->execute([$data['token']]);

            header('Location: ' . url('login?status=password_reset'));
            exit;
        }
    }

    header('Location: ' . url('login?error=user_not_found'));
    exit;
}


    public function home ()
    {
        //echo "<h1>Eu sou a Home...</h1>";
        echo $this->view->render("home",[]);
    }

    public function about ()
    {
        //echo "<h1>Eu sou a Home...</h1>";
        echo $this->view->render("about",[]);
    }

    public function contact ()
    {
        echo $this->view->render("contact",[]);
        //echo "<h1>Olá, eu sou o Contato...</h1>";
    }

    public function login ()
    {
        echo $this->view->render("login",[]);
    }

    public function location ()
    {
        //echo "<h1>Eu sou a Localização</h1>";
        echo $this->view->render("location",[]);
    }

    public function cart(): void
    {
        echo "<h1>Olá, eu sou o carrinho de compras...</h1>";
    }

    public function services(): void
    {
        echo "<h1>Olá, eu sou os serviços</h1>";
    }
    public function register(): void
    {
        echo $this->view->render("cadastro",[]);
    }

    public function faqs(): void
    {
      
        echo $this->view->render("faqs",[
            
        ]);
    }
    public function error (array $data)
    {
        echo $this->view->render("error",[
            "error" => $data["errcode"]
        ]);
    }

}