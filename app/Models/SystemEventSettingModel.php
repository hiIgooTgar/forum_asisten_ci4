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
        'status_override',
        'start_at',
        'end_at',
        'description',
        'action_url',
        'url_supporting'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    private function getWibNowString(): string
    {
        $timezone = new DateTimeZone('Asia/Jakarta');
        $now      = new DateTime('now', $timezone);
        return $now->format('Y-m-d H:i:s');
    }

    public function getActiveEventWithStatus()
    {
        $event = $this->where('is_active', 1)
            ->orderBy('start_at', 'ASC')
            ->first();

        if (!$event) {
            return null;
        }

        $nowStr = $this->getWibNowString();
        $start  = $event['start_at'] ?? null;
        $end    = $event['end_at'] ?? null;

        $status = $event['status_override'] ?? 'auto';

        if ($status === 'auto') {
            if ($start && $nowStr < $start) {
                $status = 'coming_soon';
            } elseif ($end && $nowStr > $end) {
                $status = 'closed';
            } else {
                $status = 'open';
            }
        }

        switch ($status) {
            case 'coming_soon':
                $event['computed_status'] = 'COMING_SOON';
                $event['target_time']     = $event['start_at'];
                $event['status_label']    = 'Pendaftaran Dibuka Dalam';
                $event['badge_color']     = 'bg-warning text-dark';
                $event['badge_text']      = 'Segera Dibuka';
                break;

            case 'open':
                $event['computed_status'] = 'OPEN';
                $event['target_time']     = $event['end_at'];
                $event['status_label']    = 'Sisa Waktu Pendaftaran';
                $event['badge_color']     = 'bg-success text-white';
                $event['badge_text']      = 'Pendaftaran Dibuka';
                break;

            case 'closed':
            default:
                $event['computed_status'] = 'CLOSED';
                $event['target_time']     = null;
                $event['status_label']    = 'Pendaftaran Telah Ditutup';
                $event['badge_color']     = 'bg-danger text-white';
                $event['badge_text']      = 'Tutup';
                break;
        }

        return $event;
    }

    public function getEventByKey(string $key)
    {
        return $this->where('event_key', $key)->first();
    }

    public function isEventCurrentlyOpen(string $key): bool
    {
        $event = $this->getEventByKey($key);

        if (!$event || (int) $event['is_active'] !== 1) {
            return false;
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
