<?php

namespace App\Models\Entities;
use CodeIgniter\Entity;


    class User extends Entity
    {
        
        protected $table = 'master.users'; // Ensure the table name is correct
        protected $primaryKey = 'usercode'; // Set the primary key if needed

        protected $allowedFields = [
            'name', 'udept', 'usertype', 'section', 'display', 'attend', 'empid', 'service'
        ];
        
    
        
            
    }