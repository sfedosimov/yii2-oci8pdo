
yii2-oci8pdo
=======

## A Yii Extension to simulate the Oracle PDO using the PHP OCI8 functions. The PDO driver simulation files can be used without Yii as well.

The goal of this PDO class is to simulate 99% of the PDO functions that you use in an application.  
If you find any problems, I would love the feedback. Please open an Issue. Creating UnitTests is on my TODO list.

## 1. Install for Yii Framework 2.*
Step 1: Add in file composer.json

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/sfedosimov/yii2-oci8pdo.git"
    }
],
"sfedosimov/yii2-oci8pdo": "*"
```

Step 2: php composer.phar update

Step 3: Edit your config file to configure another database component. Please read the comments.

```php
<?php
    // application components
    'components'=>array(        
        // .........
        'dbOracle'=>array(
              'class'=>'\sfedosimov\oci8pdo\Oci8PDO_Connection',
              /*
               * Note: Normally you use the Easy Connect string, but your server has 
               * to be correctly set-up for that.
               * 
               * ** Easy Connect String **
               * If you get the following error: `ORA-12154: TNS:could not resolve the connect identifier specified`,
               * go to /opt/oracle/instantclient/sqlnet.ora and change the following line:
               *    NAMES.DIRECTORY_PATH= (TNSNAMES)
               * Change this to:
               *    NAMES.DIRECTORY_PATH= (TNSNAMES, EZCONNECT)
               */
       //    'dsn' => 'oci:dbname=//myOracleHost.com:1526/ccq',
              /*
               * ** Full Connection String **
               * Use this method incase your Easy Connect gives you errors and you can't edit the sqlnet.ora file.
               * You can set the charset in this string as well, add `;charset=AL32UTF8;` at the end for UTF-8.
               */
              'dsn' => 'oci:dbname=(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=myOracleHost.com)
                                                 (PORT=1526))(CONNECT_DATA=(SERVICE_NAME=myService.intern)));charset=AL32UTF8;',
              'username' => '',
              'password' => '',
        ),
    ),
```

Step 4: There is no step three. You're done!


## 3. Usage
To start, you need to understand that Oracle Databases have **CASE-SENSITIVE** columns. Most DataBase Administrators in the Oracle world use capital table names and capital column names. That means you have to do that as well in your application.

### 3.1 DOA and the Query Builder both work. As long as you use the _right_ database component.
A few examples:
```php
<?php
    //Getting the Oracle DB connection
    $oci = Yii::$app->dbOracle;    
    $sql = <<<SQL
SELECT
  t.ANNOUNCEMENT
  , t.DESCRIPTION
  , t.TYPE
FROM
  CCQ.ANNOUNCEMENT_LIST t
WHERE
  t.TYPE = 0
  AND t.DESCRIPTION NOT LIKE '%MENU%'
  AND t.DESCRIPTION NOT LIKE '%Menu%'
  AND rownum<=2
ORDER BY 1
SQL;
    
    $command = $ivr->createCommand($sql);       
    $dataReader = $command->query();
    foreach($dataReader as $row) {
        echo $row['DESCRIPTION'],'$dataReader';
    }
```
```php

Other documentation in development...

### 3.2 ActiveRecord works as well!
Documentation in development...

### 3.3 CLOB / BLOB params bind

```php
<?php
    $db = Yii::$app->dbOracle;
    $temp = base64_encode(get_file_contents($file_path));
    $my_xml = "XML
        <root>
            <first>111</first>
            <scan>
                <type>10</type>
                <format>jpg</format>
                <byte>12312321</byte>
            </scan>
            <damageLotus>3132</damageLotus>
        </root>";
                

    $stmt = $db->createCommand("update my_table set MY_XML=:my_xml,
                            DOC_SCAN=:doc_scan where ID=:id");

    $stmt->bindParam(':my_xml', $my_xml, Oci8PDO::PARAM_CLOB);
    $stmt->bindParam(':doc_scan', $temp, Oci8PDO::PARAM_BLOB);
    $stmt->bindValue(':id', 111);
    $res = $stmt->query();
```
