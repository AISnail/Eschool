<?php
namespace model;

use think\Model;

class Form extends Model
{
    protected $table = 'eschool_form';
    protected $type = [
        'increment_id' => 'integer',
        'apply_id'     => 'integer',
        'apply_json'   => 'json',
    ];

    public static function returnForm(){
        return [
            'form_one',
            'form_two',
            'form_three',
        ];
    }

}
