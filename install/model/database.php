<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ModelDatabase extends Model
{

    public function generatePrefix()
    {
        // Create the random prefix.
        $prefix = '';
        $chars = range('a', 'z');
        $numbers = range(0, 9);

        // We want the fist character to be a random letter.
        shuffle($chars);
        $prefix .= $chars[0];

        // Next we combine the numbers and characters to get the other characters.
        $symbols = array_merge($numbers, $chars);
        shuffle($symbols);

        for ($i = 0, $j = 2; $i < $j; $i++) {
            $prefix .= $symbols[$i];
        }

        // Append the underscore.
        $prefix .= '_';

        return $prefix;
    }
    
    public function saveConfig($data)
    {
        $this->session->data['db_hostname'] = $data['db_hostname'];
        $this->session->data['db_username'] = $data['db_username'];
        $this->session->data['db_password'] = $data['db_password'];
        $this->session->data['db_database'] = $data['db_database'];
        $this->session->data['db_driver'] = $data['db_driver'];

        $content  = '<?php' . "\n";
        $content .= '/**' . "\n";
        $content .= ' * @package     Arastta eCommerce' . "\n";
        $content .= ' * @copyright   2015-2018 Arastta Association. All rights reserved.' . "\n";
        $content .= ' * @copyright   See CREDITS.txt for credits and other copyright notices.' . "\n";
        $content .= ' * @license     GNU GPL version 3; see LICENSE.txt' . "\n";
        $content .= ' * @link        https://arastta.org' . "\n";
        $content .= ' */' . "\n";
        $content .= "\n";
        $content .= '// DB' . "\n";
        $content .= 'define(\'DB_DRIVER\', \'' . addslashes($data['db_driver']) . '\');' . "\n";
        $content .= 'define(\'DB_HOSTNAME\', \'' . addslashes($data['db_hostname']) . '\');' . "\n";
        $content .= 'define(\'DB_USERNAME\', \'' . addslashes($data['db_username']) . '\');' . "\n";
        $content .= 'define(\'DB_PASSWORD\', \'' . addslashes($data['db_password']) . '\');' . "\n";
        $content .= 'define(\'DB_DATABASE\', \'' . addslashes($data['db_database']) . '\');' . "\n";
        $content .= 'define(\'DB_PREFIX\', \'' . addslashes($data['db_prefix']) . '\');' . "\n";
        
        try {
            $this->filesystem->dumpFile(DIR_ROOT . 'config.php', $content);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function validateConnection($data)
    {
        error_reporting(0);

        if ($data['db_driver'] == 'pdo') {
            $ret = $this->validatePdo($data);
        } else {
            $ret = $this->validateMysqli($data);
        }

        error_reporting(E_ALL);

        return $ret;
    }

    private function validatePdo($data)
    {
        try {
            $pdo = new \PDO("mysql:host=" . $data['db_hostname'] . ";port=3306;dbname=" . $data['db_database'], $data['db_username'], $data['db_password']);

            //$status = $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
            $status = ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') ? true : false;

            unset($pdo);
        } catch (\PDOException $e) {
            $status = false;
        }

        return $status;
    }

    private function validateMysqli($data)
    {
        $conn = new \MySQLi($data['db_hostname'], $data['db_username'], $data['db_password']);

        if ($conn->connect_error) {
            $conn->close();

            return false;
        } else {
            // Try to create database, if doesn't exist
            $sql = "CREATE DATABASE IF NOT EXISTS ".$data['db_database'];
            if ($conn->query($sql) === false) {
                // Couldn't create it, just check if exists
                $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$data['db_database']."'";
                if (!$conn->query($sql)->num_rows) {
                    $conn->close();

                    return false;
                }
            }

            $conn->close();

            return true;
        }
    }
}
