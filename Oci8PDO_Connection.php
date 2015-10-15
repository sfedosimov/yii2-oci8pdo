<?php
    /**
     * PDO Userspace Driver for Oracle (oci8)
     *
     * @category   Database
     * @package    Pdo
     * @subpackage Oci8
     * @author     Ben Ramsey <ramsey@php.net>
     * @author     Sergey Fedosimov <sfedosimov@gmail.com>
     * @copyright  Copyright (c) 2015 Ben Ramsey (http://benramsey.com/) & Sergey Fedosimov
     * @license    http://open.benramsey.com/license/mit  MIT License
     */

    namespace sfedosimov\oci8pdo;

    use Yii;
    use yii\db\Connection;
    use PDOException;

    class Oci8PDO_Connection extends Connection
    {

        public $pdoClass = 'sfedosimov\oci8pdo\Oci8PDO';

        /**
         * Creates the PDO instance.
         * When some functionalities are missing in the pdo driver, we may use
         * an adapter class to provides them.
         * @return \PDO the pdo instance
         */
        protected function createPdoInstance()
        {
            if (!empty($this->charset)) {
                Yii::trace('Error: Oci8PDO_Connection::$charset has been set to `' . $this->charset . '` in your config. The property is only used for MySQL and PostgreSQL databases. If you want to set the charset in Oracle to UTF8, add the following to the end of your Oci8PDO_Connection::$dsn: ;charset=AL32UTF8;',
                    'vendor\sfedosimov\yii2-oci8pdo\Oci8PDO_Connection');
            }

            try {
                Yii::trace('Opening Oracle connection', 'vendor\sfedosimov\yii2-oci8pdo\Oci8PDO_Connection');
                $pdoClass = parent::createPdoInstance();
            } catch(PDOException $e) {
                throw $e;
            }
            return $pdoClass;
        }

        /**
         * Closes the currently active Oracle DB connection.
         * It does nothing if the connection is already closed.
         */
        public function close()
        {
            Yii::trace('Closing Oracle connection', 'vendor\sfedosimov\yii2-oci8pdo\Oci8PDO_Connection');
            parent::close();
        }

    }