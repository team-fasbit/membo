<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

class Verification extends OssnObject
{

    public function saveProofs($guid, $addressProof, $idProof)
    {
        if (isset($addressProof) && !empty($addressProof) && isset($idProof) && !empty($idProof) && isset($guid) && !empty($guid)) {
            $this->type = 'verification';
            $this->subtype = 'proofs';
            $this->owner_guid = $guid;
            $this->data->addressProof = $addressProof;
            $this->data->idProof = $idProof;
            $this->data->status = 'WAITING';
            return $this->addObject();
        } else {
            return false;
        }
    }

    public function isVerified($guid)
    {
        $proof = $this->searchObject([
            'type' => 'verification',
            'subtype' => 'proofs',
            'owner_guid' => $guid,
        ]);
        if ($proof && is_array($proof) && count($proof) > 0) {
            return $proof[0]->status;
        } else {
            return false;
        }
    }

    public function getProofs($params = [])
    {
        return $this->searchObject(array_merge([
            'type' => 'verification',
            'subtype' => 'proofs',
            'wheres' => [
                "o.guid IN (SELECT owner_guid FROM ossn_entities_metadata As emd JOIN ossn_entities AS e ON emd.guid=e.guid WHERE emd.value='WAITING' AND e.type='object' AND e.subtype='status')"
            ]
        ], $params));
    }

    public function deleteProof($guid)
    {
        return $this->deleteObject($guid);
    }
}
