<?php
class ComputerAlli_AdminHistory_Model_Mysql4_Adminhistory extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("adminhistory/adminhistory", "history_id");
    }
}