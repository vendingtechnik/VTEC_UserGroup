<?php

/**
 *
 * Version:    1.0
 * Author:     Pasquale Pari | Vendingtechnik
 * Author URL: http://www.vendingtechnik.com
 * License:    GNU GPL 3.0
 *             !! it is forbidden to resell this Software !!
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Initial ZIP codes
 */
 
$sSelect = "SELECT * FROM vtec_plzgrp WHERE vtecgrpa = 1";
$qResultA = oxDb::getDb(ADODB_FETCH_ASSOC)->getCol($sSelect);  

 /*$qResultA = array(
    '8752','8750','8854', 
);    */
/* $aInitialZIPsB = array(
    '8854','8640',
);  */

/* $sSelect = "SELECT vtecplz FROM vtec_plzgrp WHERE vtecgrpb = 1";
$qResultB = oxDb::getDb(ADODB_FETCH_ASSOC)->getCol($sSelect);    */

$aModule = array(
    'id'          => 'vtec_usertogroup',
    'title'       => 'VTEC User zu Gruppe',
    'description' => 'Ordnet Benutzer anhand der PLZ bestimmten Benutzergruppen zu.',
    'thumbnail'   => 'usergroup.jpg',
    'version'     => '1.0',
    'author'      => 'Pasquale Pari',
    'url'         => 'http://www.vendingtechnik.com <br \> http://www.getraenkekiste.ch',
    'email'       => 'pasquale.pari@vendingtechnik.com',
    'extend'      => array(
       'oxuser'       => 'vtec/vtec_usertogroup/vtec_oxuser'
    ),
    'settings'    => array
    (
        array(
            'group' => 'vtec_utgrp_mainA',
            'name'  => 'vtec_utgrp_gruppeA',
            'type'  => 'str',
            'value' => '',
        ),
        array(
            'group' => 'vtec_utgrp_mainB',
            'name'  => 'vtec_utgrp_gruppeB',
            'type'  => 'str',
            'value' => '',
        ),
        array(
            'group'    => 'vtec_utgrp_mainA', 
            'name'     => 'vtec_utgrp_zipcodesA', 
            'type'     => 'arr', 
            'value'    => $qResultA
            ),
        array(
            'group'    => 'vtec_utgrp_mainB',
            'name'     => 'vtec_utgrp_zipcodesB',
            'type'     => 'arr',
            'value'    => $qResultB
        ),    
    ),
    
);
