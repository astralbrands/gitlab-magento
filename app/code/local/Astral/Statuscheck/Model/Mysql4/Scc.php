<?php
class Astral_Statuscheck_Model_Mysql4_Scc extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("statuscheck/scc", "increment_id");
        $this->_isPkAutoIncrement = false;
    }
}