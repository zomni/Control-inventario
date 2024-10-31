<?php

/**
 * Class Login
 * Handles the user's login and logout process.
 */
class Login
{
    /**
     * @var object The database connection.
     */
    private $db_connection = null;

    /**
     * @var array Collection of error messages.
     */
    public $errors = array();

    /**
     * @var array Collection of success/neutral messages.
     */
    public $messages = array();

    /**
     * Constructor - automatically starts when an object of this class is created.
     */
    public function __construct()
    {
        // Create/read session, absolutely necessary.
        session_start();

        // Check the possible login actions:
        // If user tried to log out (happens when user clicks logout button).
        if (isset($_GET["logout"])) {
            $this->doLogout();
        }
        // Login via post data (if user just submitted a login form).
        elseif (isset($_POST["login"])) {
            $this->doLoginWithPostData();
        }
    }

    /**
     * Log in with post data.
     */
    private function doLoginWithPostData()
    {
        // Check login form contents.
        if (empty($_POST['user_name'])) {
            $this->errors[] = "El campo de nombre de usuario está vacío.";
        } elseif (empty($_POST['user_password'])) {
            $this->errors[] = "El campo de contraseña está vacío.";
        } else {
            // Create a database connection using the constants from config/db.php.
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // Change character set to utf8 and check it.
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            // If no connection errors (= working database connection).
            if (!$this->db_connection->connect_errno) {
                // Escape the POST data.
                $user_name = $this->db_connection->real_escape_string($_POST['user_name']);

                // Database query, getting all the info of the selected user.
                $sql = "SELECT user_id, user_name, firstname, user_email, user_password_hash
                        FROM users
                        WHERE user_name = ? OR user_email = ?";
                $stmt = $this->db_connection->prepare($sql);
                $stmt->bind_param('ss', $user_name, $user_name);
                $stmt->execute();
                $result_of_login_check = $stmt->get_result();

                // If this user exists.
                if ($result_of_login_check->num_rows === 1) {
                    // Get result row (as an object).
                    $result_row = $result_of_login_check->fetch_object();

                    // Check if the provided password fits the hash of that user's password.
                    if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {
                        // Write user data into PHP SESSION (a file on your server).
                        $_SESSION['user_id'] = $result_row->user_id;
                        $_SESSION['firstname'] = $result_row->firstname;
                        $_SESSION['user_name'] = $result_row->user_name;
                        $_SESSION['user_email'] = $result_row->user_email;
                        $_SESSION['user_login_status'] = 1;

                        // Optionally redirect to a different page after successful login.
                        // header("Location: dashboard.php");
                    } else {
                        $this->errors[] = "Usuario y/o contraseña no coinciden.";
                    }
                } else {
                    $this->errors[] = "Usuario y/o contraseña no coinciden.";
                }
            } else {
                $this->errors[] = "Problema de conexión de base de datos.";
            }
        }
    }

    /**
     * Perform the logout.
     */
    public function doLogout()
    {
        // Delete the session of the user.
        $_SESSION = array();
        session_destroy();
        // Return a feedback message.
        $this->messages[] = "Has sido desconectado.";
    }

    /**
     * Simply return the current state of the user's login.
     * @return boolean User's login status.
     */
    public function isUserLoggedIn()
    {
        return isset($_SESSION['user_login_status']) && $_SESSION['user_login_status'] == 1;
    }
}
