<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Blog\Model;
/**
 * Description of Post
 *
 * @author voxuspc
 */
class Post{

    public $id;
    public $title;
    public $content;

    public function exchangeArray(array $data) {
        $this->id = (int) (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->content = (!empty($data['content'])) ? $data['content'] : null;
    }
    public function getArrayCopy(){
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content
        ];
    }
}
