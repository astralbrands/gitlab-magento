<?php

class Born_Borncmshooks_Model_Mysql4_Sections_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('borncmshooks/sections');
    }
}