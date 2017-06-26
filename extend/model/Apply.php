<?php

namespace model;

use think\Model;

class Apply extends Model
{
    protected $table = 'eschool_apply';

    public static function getForm($type){
        $form_container = [
          'form_one' => '',
          'form_two' => [

          ],
          'form_three' => [

          ],
        ];
        return $form_container[$type] ?? '';
    }
}
