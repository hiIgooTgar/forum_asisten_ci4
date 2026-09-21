<?php

namespace App\Models;

use CodeIgniter\Model;

class UserDocumentModel extends Model
{
    protected $table            = 'user_documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'document_code',
        'user_id',
        'student_card_file',
        'application_letter_file',
        'cv_file',
        'latest_transcript_file',
        'statement_letter_file',
        'registration_form_file',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getDocumentByUserId($userId)
    {
        return $this->where('user_id', $userId)->first();
    }

    public function getDocumentByCodeAndUser($documentCode, $userId)
    {
        return $this->where('document_code', $documentCode)
            ->where('user_id', $userId)
            ->first();
    }
}
