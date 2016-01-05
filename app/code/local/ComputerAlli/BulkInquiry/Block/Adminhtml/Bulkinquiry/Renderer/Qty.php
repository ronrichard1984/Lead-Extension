<?php
class ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Renderer_Qty extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        $row_val    =   (int)$value;
        return $row_val;

    }

}
?>