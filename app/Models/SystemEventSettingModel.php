<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use DateTimeZone;

class SystemEventSettingModel extends Model
{
    protected $table            = 'system_event_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'system_event_main',
        'parent_id',
        'event_key',
        'event_name',
        'category',
        'is_active',
        'is_default',
        'is_extra_time',
        'status_override',
        'start_at',
        'end_at',
        'description',
        'action_url',
        'url_supporting'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

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

    private function getWibNow(): DateTime
    {
        return new DateTime('now', new DateTimeZone('Asia/Jakarta'));
    }

    private function getWibNowString(): string
    {
        return $this->getWibNow()->format('Y-m-d H:i:s');
    }

    public function getActiveRecruitmentEvent()
    {
        $event = $this->where('is_active', 1)
            ->where('category', 'recruitment_period')
            ->orderBy('id', 'DESC')
            ->first();

        if (!$event) {
            $event = $this->where('event_key', 'system_default_closed')->first();
        }

        if (!$event) {
            return null;
        }

        $now     = $this->getWibNow();
        $nowStr  = $now->format('Y-m-d H:i:s');

        if ((int)($event['is_extra_time'] ?? 0) === 0 && !empty($event['id'])) {
            $extraEvent = $this->where('parent_id', $event['id'])
                ->where('category', 'recruitment_period')
                ->where('is_extra_time', 1)
                ->orderBy('id', 'DESC')
                ->first();

            if ($extraEvent) {
                $extStart    = $extraEvent['start_at'] ?? null;
                $extEnd      = $extraEvent['end_at'] ?? null;
                $extOverride = $extraEvent['status_override'] ?? 'auto';

                $isExtOpen = false;
                if ($extOverride === 'open') {
                    $isExtOpen = true;
                } elseif ($extOverride === 'auto') {
                    if ($extStart && $extEnd && $nowStr >= $extStart && $nowStr <= $extEnd) {
                        $isExtOpen = true;
                    } elseif ($extStart && !$extEnd && $nowStr >= $extStart) {
                        $isExtOpen = true;
                    }
                }

                $mainEnd = $event['end_at'] ?? null;
                if ($isExtOpen || ((int)($extraEvent['is_active'] ?? 0) === 1) || ($mainEnd && $nowStr > $mainEnd && $extEnd && $nowStr <= $extEnd)) {
                    $event = $extraEvent;
                }
            }
        }

        $serverTimeMs = $now->getTimestamp() * 1000;
        $start        = $event['start_at'] ?? null;
        $end          = $event['end_at'] ?? null;
        $status       = $event['status_override'] ?? 'auto';

        if ($status === 'auto') {
            if ($start && $nowStr < $start) {
                $status = 'coming_soon';
            } elseif ($end && $nowStr > $end) {
                $status = 'closed';
            } else {
                $status = 'open';
            }
        }

        $isExtra = (int)($event['is_extra_time'] ?? 0) === 1;

        switch ($status) {
            case 'coming_soon':
                $event['computed_status'] = 'COMING_SOON';
                $event['target_time']     = $event['start_at'];
                $event['target_time_ms']  = $event['start_at'] ? (new DateTime($event['start_at'], new DateTimeZone('Asia/Jakarta')))->getTimestamp() * 1000 : null;
                $event['status_label']    = $isExtra ? 'Perpanjangan Dibuka Dalam' : 'Pendaftaran Dibuka Dalam';
                $event['badge_color']     = 'bg-warning text-dark';
                $event['badge_text']      = $isExtra ? 'Extra Time Soon' : 'Segera Dibuka';
                break;

            case 'open':
                $event['computed_status'] = 'OPEN';
                $event['target_time']     = $event['end_at'];
                $event['target_time_ms']  = $event['end_at'] ? (new DateTime($event['end_at'], new DateTimeZone('Asia/Jakarta')))->getTimestamp() * 1000 : null;
                $event['status_label']    = $isExtra ? 'Sisa Waktu Perpanjangan (Extra Time)' : 'Sisa Waktu Pendaftaran';
                $event['badge_color']     = $isExtra ? 'bg-orange text-dark' : 'bg-success text-white';
                $event['badge_text']      = $isExtra ? 'Perpanjangan (Extra Time)' : 'Pendaftaran Dibuka';
                break;

            case 'closed':
            default:
                $event['computed_status'] = 'CLOSED';
                $event['target_time']     = null;
                $event['target_time_ms']  = null;
                $event['status_label']    = 'Pendaftaran Telah Ditutup';
                $event['badge_color']     = 'bg-danger text-white';
                $event['badge_text']      = 'Tutup';
                break;
        }

        $event['server_time_ms'] = $serverTimeMs;

        return $event;
    }

    public function getEventByKey(string $key)
    {
        return $this->where('event_key', $key)->first();
    }

    public function isEventCurrentlyOpen(string $key): bool
    {
        $event = $this->where('event_key', $key)->first();

        if (!$event) {
            return false;
        }

        if ((int)$event['is_active'] !== 1 && (int)($event['is_extra_time'] ?? 0) === 0) {
            $extra = $this->where('parent_id', $event['id'])->where('is_extra_time', 1)->first();
            if ($extra && (int)$extra['is_active'] === 1) {
                $event = $extra;
            } else {
                return false;
            }
        }

        $override = $event['status_override'] ?? 'auto';

        if ($override === 'closed' || $override === 'coming_soon') {
            return false;
        }

        if ($override === 'open') {
            return true;
        }

        $nowStr = $this->getWibNowString();
        $start  = $event['start_at'] ?? null;
        $end    = $event['end_at'] ?? null;

        if (!$start && !$end) {
            return true;
        }

        if ($start && $nowStr < $start) {
            return false;
        }

        if ($end && $nowStr > $end) {
            return false;
        }

        return true;
    }

    public function setActiveEventOnly($idOrKey): bool
    {
        $this->db->transStart();

        $this->builder()->update(['is_active' => 0]);
        if (is_numeric($idOrKey)) {
            $this->where('id', $idOrKey)->set(['is_active' => 1])->update();
        } else {
            $this->where('event_key', $idOrKey)->set(['is_active' => 1])->update();
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
