<?php

namespace App\Models;

use CodeIgniter\Model;

class permissionModel extends Model
{
    protected $table            = 'user_permissions';
    protected $primaryKey       = 'permission_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['rolename','manage','approval','reports','settings'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}