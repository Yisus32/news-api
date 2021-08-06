<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Document extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'documents';

    protected $fillable = [
    	"guest_id", 
    	"name", 
    	'type',
    	"front_image", 
    	"back_image", 
    	"document",
        'state',
        'emission',
        'expiration',
        'img_validation',
        'created_at',
        'updated_at'
    ];

    public function guest(){
        return $this->belongsTo(Guest::class, 'id', 'guest_id');
    }
}