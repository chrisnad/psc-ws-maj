<?php
/**
 * FileData
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * FileData
 */
class FileData extends Model {

    protected $connection = 'sqlite';

    protected $fillable = [
        'data'
    ];

}
