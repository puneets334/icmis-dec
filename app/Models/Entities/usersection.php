<?php

namespace App\Models;

use CodeIgniter\Model;

class UserSectionModel extends Model
{
  protected $table = 'usersection';
  protected $primaryKey = 'id';

  public function getSectionIdByName($sectionName)
  {
    return $this->where('section_name', $sectionName)->first();
  }
}
