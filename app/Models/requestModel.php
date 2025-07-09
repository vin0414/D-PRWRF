<?php

namespace App\Models;

use CodeIgniter\Model;

class requestModel extends Model
{
    protected $table            = 'request';
    protected $primaryKey       = 'request_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['accountID','control_number','from','date_prepared','date_needed',
                                   'equipment_name','equipment_model','equipment_maker','equipment_brand',
                                   'serial_number','size','dimension','problem','cause','work_required','year','status'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}