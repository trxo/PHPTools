<?php

/**
 * Created by PhpStorm.
 * User: zxt778@gmail.com
 * Date: 2017/7/28
 * Time: 下午2:07
 */

class DB
{

    //pdo对象
    private $_pdo = null;
    //存放实例对象
    static private $_instance = null;

    /**
     * 获取db实例
     * @return DB|null
     */
    static public function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 防止克隆
     */
    private function __clone()
    {
    }


    /**
     * 私有构造
     * DB constructor.
     */
    private function __construct()
    {
        try {
            $this->_pdo = new PDO(DB_DNS, DB_USER, DB_PASS);
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->log($e->getMessage());
        }
    }


    /**
     * 新增
     * @param $_table
     * @param array $_addData
     * @return int
     */
    public function add($_table, Array $_addData)
    {
        $_addFields = array();
        $_addValues = array();
        foreach ($_addData as $_key => $_value) {
            $_addFields[] = $_key;
            $_addValues[] = $_value;
        }
        $_addFields = implode(',', $_addFields);
        $_addValues = implode("','", $_addValues);
        $_sql = "INSERT INTO $_table ($_addFields) VALUES ('$_addValues')";
        return $this->execute($_sql)->rowCount();
    }


    /**
     * 修改
     * @param $_table
     * @param array $_param
     * @param array $_updateData
     * @return int
     */
    public function update($_table, Array $_param, Array $_updateData)
    {
        $_where = $_setData = '';
        foreach ($_param as $_key => $_value) {
            $_where .= $_value . ' AND ';
        }
        $_where = 'WHERE ' . substr($_where, 0, -4);
        foreach ($_updateData as $_key => $_value) {
            if (is_array($_value)) {
                $_setData .= "$_key=$_value[0],";
            } else {
                $_setData .= "$_key='$_value',";
            }
        }
        $_setData = substr($_setData, 0, -1);
        $_sql = "UPDATE $_table SET $_setData $_where";
        return $this->execute($_sql)->rowCount();
    }


    /**
     * 删除
     * @param $_table
     * @param array $_param
     * @return int
     */
    public function delete($_table, Array $_param)
    {
        $_where = '';
        foreach ($_param as $_key => $_value) {
            $_where .= $_value . ' AND ';
        }
        $_where = 'WHERE ' . substr($_where, 0, -4);
        $_sql = "DELETE FROM $_table $_where";
        return $this->execute($_sql)->rowCount();
    }

    /**
     * 查询
     * @param $_table
     * @param array $_fileld
     * @param array $_param
     * @return mixed
     */
    public function select($_table, Array $_fileld, Array $_param = array())
    {
        $_limit = $_order = $_where = $_like = '';
        if (is_array($_param) && !empty($_param)) {
            $_limit = isset($_param['limit']) ? 'LIMIT ' . $_param['limit'] : '';
            $_order = isset($_param['order']) ? 'ORDER BY ' . $_param['order'] : '';
            if (isset($_param['where'])) {
                foreach ($_param['where'] as $_key => $_value) {
                    $_where .= $_value . ' AND ';
                }
                $_where = 'WHERE ' . substr($_where, 0, -4);
            }
            if (isset($_param['like'])) {
                foreach ($_param['like'] as $_key => $_value) {
                    $_like = "WHERE $_key LIKE '%$_value%'";
                }
            }
        }
        $_selectFields = implode(',', $_fileld);
        $_sql = "SELECT $_selectFields FROM $_table $_where $_like $_order $_limit";
        $_stmt = $this->execute($_sql);
        $_result = array();
        while (!!$_objs = $_stmt->fetch()) {
            $_result[] = $_objs;
        }

        return $_result;
    }

    /**
     * 总记录数
     * @param $_table
     * @param array $_param
     * @return mixed
     */
    public function total($_table, Array $_param = array())
    {

        $_where = '';
        if (isset($_param['where'])) {
            foreach ($_param['where'] as $_key => $_value) {
                $_where .= $_value . ' AND ';
            }
            $_where = 'WHERE ' . substr($_where, 0, -4);
        }
        $_sql = "SELECT COUNT(*) as count FROM $_table $_where";
        $_stmt = $this->execute($_sql);
        return $_stmt->fetchObject()->count;
    }

    /**
     * 执行SQL
     * @param $_sql
     * @return PDOStatement
     */
    public function execute($_sql)
    {
        try {
            $_stmt = $this->_pdo->prepare($_sql);
            $_stmt->execute();
        } catch (PDOException  $e) {
            $this->log('SQL语句：' . $_sql . ' 错误信息：' . $e->getMessage());
        }
        return $_stmt;
    }

    /**
     * 开启事务
     */
    public function startTransaction()
    {
        $this->_pdo->beginTransaction();
    }

    /**
     * 事务回滚
     */
    public function rollBack()
    {
        $this->_pdo->rollBack();
    }

    /**
     * 提交事务
     */
    public function commit()
    {
        $this->_pdo->commit();
    }

    /**
     * log
     * @param $_msg
     */

    private function log($_msg)
    {
        exit($_msg);
    }
}

//
//define("DB_DNS", $dsn = "pgsql:host=192.168.1.26;port=5432;dbname=fusionpbx;");
//define("DB_USER", "postgres");
//define("DB_PASS", "");
//define("DB_CHARSET", "");
//
//$_obj = DB::getInstance();

//$total = $_obj->total('v_ivr_menus');

//$arrs = $_obj->select('v_ivr_menus', ['*']);
//
//$_obj->add("v_ivr_menus",['ivr_menu_uuid'=>'38a28ff0-71b3-11e7-a05e-155593030c3e']);

//$_obj->update("v_ivr_menus",["ivr_menu_uuid='38a28ff0-71b3-11e7-a05e-155593030c3e'"],['ivr_menu_name'=>'obj']);

//$_obj->delete("v_ivr_menus",["ivr_menu_uuid='38a28ff0-71b3-11e7-a05e-155593030c3e'"]);
