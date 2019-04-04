<?php

/**
 * @package GoBlue Advance Settings
 * @author Ankur Patel <ankur2194@gmail.com>
 * @license Commercial
 * @link https://www.softlab24.com/license/commercial
 */

class Verification extends OssnObject {
    public function setAddressProof($value, $guid) {
        if (isset($value) && !empty($value) && isset($guid) && !empty($guid)) {
            $this->type = 'verification';
            $this->subtype = 'Address Proof';
            $this->owner_guid = $guid;
            $this->description = $value;
            return $this->addObject();
        } else {
            return false;
        }
    }
    
    public function setIDProof($value, $guid) {
        if (isset($value) && !empty($value) && isset($guid) && !empty($guid)) {
            $this->type = 'verification';
            $this->subtype = 'ID Proof';
            $this->owner_guid = $guid;
            $this->description = $value;
            return $this->addObject();
        } else {
            return false;
        }
    }
    
    // public function setPhotoProof($value, $guid) {
    //     if (isset($value) && !empty($value) && isset($guid) && !empty($guid)) {
    //         $this->type = 'verification';
    //         $this->subtype = 'Photo Proof';
    //         $this->owner_guid = $guid;
    //         $this->description = $value;
    //         return $this->addObject();
    //     } else {
    //         return false;
    //     }
    // }

    public function isVerified($guid) {
        $verified = $this->searchObject([
            'type' => 'verification',
            'subtype' => 'verified',
            'owner_guid' => $guid,
        ]);
        if ($verified && is_array($verified) && count($verified) > 0) {
            return $verified;
        } else {
            return false;
        }
    }

    public function allProofUploaded($guid) {
        $uploaded_proof = 0;
        // $proofs = [ 'Address Proof', 'ID Proof', 'Photo Proof' ];
        $proofs = [ 'Address Proof', 'ID Proof' ];

        foreach ($proofs as $proof) {
            $records = $this->searchObject([
                'type' => 'verification',
                'subtype' => $proof,
                'owner_guid' => $guid,
            ]);
            if ($records && is_array($records) && count($records) > 0) {
                $uploaded_proof++;
            }
        }

        return (count($proofs) === $uploaded_proof) ? true : false;
    }
}