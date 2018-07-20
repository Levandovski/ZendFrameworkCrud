<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Blog\Model;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;


/* * s
 * Description of Post
 *
 * @author voxuspc
 */

class PostTable {

    private $tableGateway; //trasforma nossas tabelas em objetos para conversar com o banco

    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
     $resultSet= $this->tableGateway->select(function(Select $select){
     
         $select->order('title ASC');
     }   
       ); 
       return $resultSet;
        
    }

    public function procurarNome(Post $post) {//Pegando os dados que foram passados como post
        //Checando os dados com o rowset para verificar se retornou algua linha ou não
        $rowset = $this->tableGateway->select(['title' => $post->title]); //Fazendo select acessando os dados do post
        $row = $rowset->current(); //Estamos pegando a linha para verificação.
        if (!$row) {//Verificando se a minha linha está vazia
            return null;
        } else {
            return $this->tableGateway->select(['title' => $post->title]);
        }
    }

    public function procurarId(Post $post) {
        $id = (int) $post->id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            return null;
        } else {
            return $this->tableGateway->select(['id' => $id]);
        }
    }

    public function save(Post $post) {
        $data = [
            'title' => $post->title,
            'content' => $post->content
        ];
        $id = (int) $post->id;
        //Se o id for zero é pra inserir
        if ($id === 0) {
            $this->tableGateway->insert($data); //Salvando os dados
            return;
        }
        //Se o id não estiver no banco não devo atualizar os dados
        if (!$this->find($id)) {
            throw new RuntimeException(sprintf(
                    'Could not retrieve the row %d', $id
            ));
        }
        //Atualizo os dados quando o id for passado
        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function find($id) {
        $id = (int) $id; //Recuperando o tipo da variavel.
        $rowset = $this->tableGateway->select(['id' => $id]); //Passando o id para o id.
        $row = $rowset->current();

        if (!$row) {
            throw new RuntimeException(sprintf(
                    'Could not retrieve the row %d', $id
            ));
        }
        return $row;
    }

    public function delete($id) {
        $this->tableGateway->delete(['id' => (int) $id]);
    }

}
