<?php

/**
 * Class Login
 * Handles the user's login and logout process.
 */
class Login
{
    private $db_connection = null;
    public $errors = array();
    public $messages = array();

    public function __construct()
    {
        session_start();
        if (isset($_GET["logout"])) {
            $this->doLogout();
        } elseif (isset($_POST["login"])) {
            $this->doLoginWithPostData();
        }
    }

    private function doLoginWithPostData()
    {
        if (empty($_POST['user_name'])) {
            $this->errors[] = "El campo de nombre de usuario está vacío.";
        } elseif (empty($_POST['user_password'])) {
            $this->errors[] = "El campo de contraseña está vacío.";
        } else {
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            if (!$this->db_connection->connect_errno) {
                $user_name = $this->db_connection->real_escape_string($_POST['user_name']);
                $sql = "SELECT user_id, user_name, firstname, user_email, user_password_hash, failed_attempts, is_locked
                        FROM users
                        WHERE user_name = ? OR user_email = ?";
                $stmt = $this->db_connection->prepare($sql);
                $stmt->bind_param('ss', $user_name, $user_name);
                $stmt->execute();
                $result_of_login_check = $stmt->get_result();

                if ($result_of_login_check->num_rows === 1) {
                    $result_row = $result_of_login_check->fetch_object();

                    // Check if the account is locked
                    if ($result_row->is_locked) {
                        $this->errors[] = "Tu cuenta está bloqueada debido a múltiples intentos fallidos.";
                        return;
                    }

                    if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {
                        // Reset failed attempts on successful login
                        $this->resetFailedAttempts($result_row->user_id);
                        
                        // Write user data into PHP SESSION
                        $_SESSION['user_id'] = $result_row->user_id;
                        $_SESSION['firstname'] = $result_row->firstname;
                        $_SESSION['user_name'] = $result_row->user_name;
                        $_SESSION['user_email'] = $result_row->user_email;
                        $_SESSION['user_login_status'] = 1;
                    } else {
                        $this->errors[] = "Usuario y/o contraseña no coinciden.";
                        $this->incrementFailedAttempts($result_row->user_id, $result_row->failed_attempts);
                    }
                } else {
                    $this->errors[] = "Usuario y/o contraseña no coinciden.";
                }
            } else {
                $this->errors[] = "Problema de conexión de base de datos.";
            }
        }
    }

    private function incrementFailedAttempts($user_id, $current_attempts)
    {
        $new_attempts = $current_attempts + 1;
        if ($new_attempts >= 7) {
            // Bloquear la cuenta si se exceden los intentos
            $sql = "UPDATE users SET failed_attempts = ?, is_locked = 1 WHERE user_id = ?";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bind_param('ii', $new_attempts, $user_id);
        } else {
            $sql = "UPDATE users SET failed_attempts = ? WHERE user_id = ?";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bind_param('ii', $new_attempts, $user_id);
        }
        $stmt->execute();
    }

    private function resetFailedAttempts($user_id)
    {
        $sql = "UPDATE users SET failed_attempts = 0, is_locked = 0 WHERE user_id = ?";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
    }

    public function doLogout()
    {
        $_SESSION = array();
        session_destroy();
        $this->messages[] = "Has sido desconectado.";
    }

    public function isUserLoggedIn()
    {
        return isset($_SESSION['user_login_status']) && $_SESSION['user_login_status'] == 1;
    }
}

