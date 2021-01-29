<?php
/**
 * Expertise
 */
namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Expertise
 */
class Expertise extends Model {

    protected $connection = 'mongodb';

    protected $fillable = [
        'code',
        'categoryCode',
    ];

}
