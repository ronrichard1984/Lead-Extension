<?php
class ComputerAlli_BulkInquiry_Model_Mysql4_Bulkinquiry extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("bulkinquiry/bulkinquiry", "inquiry_id");
    }
}