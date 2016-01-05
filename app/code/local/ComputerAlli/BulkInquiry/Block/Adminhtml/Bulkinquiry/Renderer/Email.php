<?php
class ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Renderer_Email extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        $row_val    =   '<a href="mailto:'.$value.'">'.$value.'</a>';
        return $row_val;

    }

}
?>