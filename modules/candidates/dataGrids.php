<?php

//TODO: License

include_once(LEGACY_ROOT . '/lib/Candidates.php');
include_once(LEGACY_ROOT . '/lib/Width.php');

class candidatesListByViewDataGrid extends CandidatesDataGrid
{
    public function __construct($siteID, $parameters, $misc)
    {
        /* Pager configuration. */
        $this->_tableWidth = new Width(100, '%');
        $this->_defaultAlphabeticalSortBy = 'lastName';
        $this->ajaxMode = false;
        $this->showExportCheckboxes = true; //BOXES WILL NOT APPEAR UNLESS SQL ROW exportID IS RETURNED!
        $this->showActionArea = true;
        $this->showChooseColumnsBox = true;
        $this->allowResizing = true;

        $this->defaultSortBy = 'dateModifiedSort';
        $this->defaultSortDirection = 'DESC';

        $this->_defaultColumns = array(
            array('name' => 'Candidate ID', 'width' => 85),
            array('name' => 'First Name', 'width' => 100),
            array('name' => 'Last Name', 'width' => 100),
            array('name' => 'Key Skills', 'width' => 130),
            array('name' => 'Applied Date', 'width' => 80),
            array('name' => 'Owner', 'width' => 75),
        );

         parent::__construct("candidates:candidatesListByViewDataGrid",
                             $siteID, $parameters, $misc
                        );
    }


    /**
     * Adds more options to the action area on the pager.  Overloads
     * DataGrid Inner Action Area function.
     *
     * @return html innerActionArea commands.
     */
    public function getInnerActionArea()
    {
        //TODO: Add items:
        //  - Add to List
        //  - Add to Job Order
        //  - Mass set rank (depends on each candidate having their own personal rank - are we going to do this?)
        $html = '';

        $html .= $this->getInnerActionAreaItemPopup('Add To List', CATSUtility::getIndexName().'?m=lists&amp;a=addToListFromDatagridModal&amp;dataItemType='.DATA_ITEM_CANDIDATE, 450, 350);
        
        if($_SESSION['CATS']->getAccessLevel('pipelines.addToPipeline') >= ACCESS_LEVEL_EDIT) 
        {
            $html .= $this->getInnerActionAreaItemPopup('Add To Job Order', CATSUtility::getIndexName().'?m=candidates&amp;a=considerForJobSearch', 750, 460);
        }
        
        if(MAIL_MAILER != 0 && $_SESSION['CATS']->getAccessLevel('candidates.emailCandidates') >= ACCESS_LEVEL_SA)
        {
            $html .= $this->getInnerActionAreaItem('Send E-Mail', CATSUtility::getIndexName().'?m=candidates&amp;a=emailCandidates');
        }
        $html .= $this->getInnerActionAreaItem('Export', CATSUtility::getIndexName().'?m=export&amp;a=exportByDataGrid');

        if($_SESSION['CATS']->getAccessLevel('candidates.delete') >= ACCESS_LEVEL_DELETE) 
        {
            $html .= $this->getInnerActionAreaItemBulkDelete('Delete Selected', CATSUtility::getIndexName().'?m=candidates&amp;a=bulkDelete');
        }

        $html .= parent::getInnerActionArea();

        return $html;
    }
    
    /**
     * Returns HTML for a bulk delete action with confirmation.
     *
     * @param string action title
     * @param string action URL
     * @return string generated HTML
     */
    public function getInnerActionAreaItemBulkDelete($actionTitle, $actionURL)
    {
        $md5InstanceName = md5($this->_instanceName);
        
        $html = '<a href="javascript:void(0);" onclick="confirmBulkDelete' . $md5InstanceName . '();" style="color: #dc2626; display: flex; align-items: center; gap: 4px;">';
        $html .= '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>';
        $html .= $actionTitle;
        $html .= '</a><br />';
        
        return $html;
    }
}

class candidatesSavedListByViewDataGrid extends CandidatesDataGrid
{
    public function __construct($siteID, $parameters, $misc)
    {
        $this->_tableWidth = new Width(100, '%');
        $this->_defaultAlphabeticalSortBy = 'lastName';
        $this->ajaxMode = false;
        $this->showExportCheckboxes = true; //BOXES WILL NOT APPEAR UNLESS SQL ROW exportID IS RETURNED!
        $this->showActionArea = true;
        $this->showChooseColumnsBox = true;
        $this->allowResizing = true;

        $this->defaultSortBy = 'dateModifiedSort';
        $this->defaultSortDirection = 'DESC';

        $this->_defaultColumns = array(
            array('name' => 'Attachments', 'width' => 31),
            array('name' => 'First Name', 'width' => 75),
            array('name' => 'Last Name', 'width' => 85),
            array('name' => 'City', 'width' => 75),
            array('name' => 'State', 'width' => 50),
            array('name' => 'Key Skills', 'width' => 200),
            array('name' => 'Owner', 'width' => 65),
            array('name' => 'Modified', 'width' => 60),
            array('name' => 'Added To List', 'width' => 75),
        );

         parent::__construct("candidates:candidatesSavedListByViewDataGrid",
                             $siteID, $parameters, $misc
                        );
    }

    /**
     * Adds more options to the action area on the pager.  Overloads
     * DataGrid Inner Action Area function.
     *
     * @return html innerActionArea commands.
     */
    public function getInnerActionArea()
    {
        //TODO: Add items:
        //  - Add to List
        //  - Add to Job Order
        //  - Mass set rank (depends on each candidate having their own personal rank - are we going to do this?)
        $html = '';

        $html .= $this->getInnerActionAreaItem('Remove From This List', CATSUtility::getIndexName().'?m=lists&amp;a=removeFromListDatagrid&amp;dataItemType='.DATA_ITEM_CANDIDATE.'&amp;savedListID='.$this->getMiscArgument(), false);
        $html .= $this->getInnerActionAreaItemPopup('Add To Job Order', CATSUtility::getIndexName().'?m=candidates&amp;a=considerForJobSearch', 750, 460);
        if(MAIL_MAILER != 0 && $_SESSION['CATS']->getAccessLevel('candidates.emailCandidates') >= ACCESS_LEVEL_SA)
        {
            $html .= $this->getInnerActionAreaItem('Send E-Mail', CATSUtility::getIndexName().'?m=candidates&amp;a=emailCandidates');
        }
        $html .= $this->getInnerActionAreaItem('Export', CATSUtility::getIndexName().'?m=export&amp;a=exportByDataGrid');

        $html .= parent::getInnerActionArea();

        return $html;
    }
}


?>