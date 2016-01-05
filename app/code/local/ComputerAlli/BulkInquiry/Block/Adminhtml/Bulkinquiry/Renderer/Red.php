<?php
class ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Renderer_Red extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        if($value){
            $row_val    =   '<span style="color:green; font-weight: bold">Yes</span>';
        }
        else{
            $row_val    =   '<span style="color:red;; font-weight: bold">No</span>';;
        }
        return $row_val;

    }

}
?>