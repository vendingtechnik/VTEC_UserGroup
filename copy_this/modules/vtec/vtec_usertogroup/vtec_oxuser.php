<?php

class vtec_oxuser extends vtec_oxuser_parent
{
    /**
     * Get user group from settings
     *
     * @return oxGroups
     */
    protected function vtec_getUserGroup()
    {
        $sGroupID = oxRegistry::getConfig()->getConfigParam('vtec_utgrp_gruppeA');
        /** @var oxGroups $oGroup */
        $oGroup = oxNew('oxGroups');

        $oGroup->load($sGroupID);

        return $oGroup;
    }

    /**
     * Get zip codes from settings
     *
     * @return array
     */
    protected function vtec_getZipCodes()
    {
        /* $oConfig   = oxRegistry::getConfig();
        $aZipCodes = $oConfig->getConfigParam('vtec_utgrp_zipcodesA'); */
        $sSelect = "SELECT * FROM vtec_plzgrp WHERE vtecgrpa = 1";
        $aZipCodes = oxDb::getDb(ADODB_FETCH_ASSOC)->getCol($sSelect);
        
        return $aZipCodes;
    }

    /**
     * Check if user has specified group assigned
     *
     * @param oxGroups $oUserGroup
     *
     * @return bool
     */
    protected function vtec_hasGroup(oxGroups $oUserGroup)
    {
        $oGroups = $this->getUserGroups();

        /** @var oxGroups $oGroup */
        foreach ($oGroups as $oGroup) {
            if ($oGroup->getId() == $oUserGroup->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has at least one ZIP code from settings
     *
     * @return bool
     */
    protected function vtec_hasRequiredZIP()
    {
        $oDB      = oxDb::getDb();
        $aConfZIP = $this->vtec_getZipCodes();

        $aZIP = array_merge(
            $oDB->getCol("SELECT oxzip FROM oxaddress WHERE oxuserid = ?", array($this->getId())) ?: array(),
            $oDB->getCol("SELECT oxzip FROM oxuser  WHERE oxid = ?", array($this->getId())) ?: array()
        );

        foreach ($aZIP as $sZIP) {
            if (in_array($sZIP, $aConfZIP)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function changeUserData($sUser, $sPassword, $sPassword2, $aInvAddress, $aDelAddress)
    {
        parent::changeUserData($sUser, $sPassword, $sPassword2, $aInvAddress, $aDelAddress);

        $oGroup = $this->vtec_getUserGroup();

        # Add to group
        if ($this->vtec_hasRequiredZIP() && !$this->vtec_hasGroup($oGroup)) {
            $this->addToGroup($oGroup->getId());
            return;
        }

        # Remove from group
        if (!$this->vtec_hasRequiredZIP() && $this->vtec_hasGroup($oGroup)) {
            $this->removeFromGroup($oGroup->getId());
            return;
        }
    }
}