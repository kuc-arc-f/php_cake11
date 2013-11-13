<?php

class Post extends AppModel {
    public $hasMany =  'Comment';

    public $validate=array(
      'title'=>array(
        'rule'=>'notEmpty',
        'message'=>'KARA is [Error]'
      ),
      'body'=>array(
        'rule'=>'notEmpty',
        'message'=>'body KARA is [Error]'
      )
    );
}
