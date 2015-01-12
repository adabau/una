<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once('./inc/header.inc.php');

$sUploaderObject = bx_process_input(bx_get('uo'));
$sStorageObject = bx_process_input(bx_get('so'));
$sUniqId = bx_process_input(bx_get('uid'));
$isMultiple = bx_get('m') ? true : false;

$sFormat = bx_process_input(bx_get('f'));
if ($sFormat != 'html' &&  $sFormat != 'json')
    $sFormat = 'html';

$iContentId = bx_get('c');
if (false === $iContentId || '' === $iContentId)
    $iContentId = false;
else
    $iContentId = bx_process_input($iContentId, BX_DATA_INT);

if (!$sUploaderObject || !$sStorageObject || !$sUniqId)
    exit;

bx_import('BxDolUploader');
$oUploader = BxDolUploader::getObjectInstance($sUploaderObject, $sStorageObject, $sUniqId);
if (!$oUploader) {
    // no such uploader available
    exit;
}

$sAction = bx_process_input(bx_get('a'));

switch ($sAction) {

    case 'show_uploader_form':
        header('Content-type: text/html; charset=utf-8');

        require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

        bx_import('BxDolLanguages');

        echo $oUploader->getUploaderForm($isMultiple, $iContentId);
        break;

    case 'restore_ghosts':
        header('Content-type: text/html; charset=utf-8');
        $sImagesTranscoder = bx_process_input(bx_get('img_trans'));
        echo $oUploader->getGhosts(bx_get_logged_profile_id(), $sFormat, $sImagesTranscoder, $iContentId);
        break;

    case 'delete':
        header('Content-type: text/html; charset=utf-8');
        $iFileId = bx_process_input(bx_get('id'), BX_DATA_INT);
        echo $oUploader->deleteGhost($iFileId, bx_get_logged_profile_id());
        break;

    case 'upload':
        header('Content-type: text/html; charset=utf-8');

        bx_import('BxDolLanguages');

        $oUploader->handleUploads(bx_get_logged_profile_id(), isset($_FILES['f']) ? $_FILES['f'] : null, $isMultiple, $iContentId);
        break;

}

/** @} */