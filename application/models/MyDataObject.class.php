<?php
 
class MyDataObjectCore extends DB_DataObject implements Iterator {

    function query($string) {
        if(!PEAR::isError($this)) {
            return $this->_query($string);
        }
        else {
            header('X-Error-Message: Error database has gone away', true, 500);
            Tools::redirect("");
        }
    }
    
    public function current() {
        global $_DB_DATAOBJECT;
        if (!empty($_DB_DATAOBJECT['CONFIG']['debug'])) {
            var_dump(__METHOD__);
        }
        return $this;
    }
    public function key() {
        global $_DB_DATAOBJECT;
        if (!empty($_DB_DATAOBJECT['CONFIG']['debug'])) {
            var_dump(__METHOD__);
        }
        $result = &$_DB_DATAOBJECT['RESULTS'][$this->_DB_resultid];
        return $result->rowCount();
    }
    public function next() {
        global $_DB_DATAOBJECT;
        if (!empty($_DB_DATAOBJECT['CONFIG']['debug'])) {
            var_dump(__METHOD__);
        }
        $this->fetch();
    }
    public function rewind() {
        global $_DB_DATAOBJECT;
        if (!empty($_DB_DATAOBJECT['CONFIG']['debug'])) {
            var_dump(__METHOD__);
        }
        $result = &$_DB_DATAOBJECT['RESULTS'][$this->_DB_resultid];
    
        if(!$result) return false;
        $result->seek();
        $this->fetch();
    }
    public function valid() {
        global $_DB_DATAOBJECT;
        if (!empty($_DB_DATAOBJECT['CONFIG']['debug'])) {
            var_dump(__METHOD__);
        }
        if(empty($this->N)) {
            return false;
        }
    
        if (empty($_DB_DATAOBJECT['RESULTS'][$this->_DB_resultid]) ||
        !is_object($result = &$_DB_DATAOBJECT['RESULTS'][$this->_DB_resultid])) {
            return false;
        }
    
        return true;
    }
    
    // la fonction count() est djˆ implmente par DB_DataObject mais elle ne renvoie pas le nb de resultat d'un objet resultat
    public function getMockCount() {
        return $this->N;
    }
    
    public function fetch() {
        global $_DB_DATAOBJECT;
        if (!empty($_DB_DATAOBJECT['CONFIG']['debug'])) {
            var_dump(__METHOD__);
        }
        if($result = parent::fetch()) {
            if(is_callable(array($this, 'getProperties'), true))
                $this->getProperties();
        }
        return $result;
    }
}