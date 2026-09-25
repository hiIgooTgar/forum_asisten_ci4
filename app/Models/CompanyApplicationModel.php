<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyApplicationModel extends Model
{
    protected $table            = 'company_applications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'company_application_code',
        'application_name',
        'application_title',
        'application_description',
        'logo',
        'logo_white',
        'logo_sidebar',
        'favicon',
        'phone_number',
        'email',
        'address',
        'instagram_url',
        'youtube_url',
        'linkedin_url',
        'tiktok_url',
        'website_url',
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

    public function getAppProfile()
    {
        $appConfig = cache('company_app_profile');

        if (!$appConfig) {
            $appConfig = $this->first();

            if ($appConfig) {
                cache()->save('company_app_profile', $appConfig, 86400);
            }
        }

        return $appConfig;
    }
}
