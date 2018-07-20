<?php

namespace Blog\Controller;

use Blog\Model\PostTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Blog\Form\PostForm;
use Blog\Model\Post;

//Obs: Sempre que der erro devo adicionar as extensões que faltam

class BlogController extends AbstractActionController {

    private $table;

    public function __construct(PostTable $table) {
        $this->table = $table;
    }

    public function indexAction() {
        $postTable = $this->table;
        $param = $this->params()->fromRoute('.json');

        if (!is_null($param)) {
            return new JsonModel(
                    $postTable->fetchAll()
                    //$postTable->fetchAll()
            );
        }

        return new ViewModel([
            'posts' => $postTable->fetchAll()
        ]);
    }

//Função sendo desenvolvida ainda
    public function addAction() {//Colocamos sempre o nome da tela e o Action que é a nossa ação que será enviada para o module.config e direcionada para ela.
        $form = new PostForm();
        $form->get('submit')->setValue('Cadastrar'); //Estamos sentando o valor da do form e adicionando o valor de add post
        $form->get('title')->setLabel('Nome');
        $form->get('content')->setLabel('Tarefa');
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }
        $form->setData($request->getPost());
        if (!$form->isValid()) {

            return ['form' => $form];
        }
        $post = new Post();
        $post->exchangeArray($form->getData()); //Passando os valores do post para o array que está fazendo a associação deles em sua classe
        $this->table->save($post); //Estamos passando os dados para função que salva no banco
        return $this->redirect()->toRoute('post'); //Estamos escolhendo nossa rota para redirecionar 
    }

    public function editAction() {
        
        $id = (int) $this->params()->fromRoute('id', 0); //Se eu quiser pegar qualquer parametro que foi passado em minha rota eu consigo pegar quando passo o id no comando da rota, e se não tiver nada ele pega 0.

        if (!$id) {
            return $this->redirect()->toRoute('post');
        }
        try {
            $post = $this->table->find($id); //Estamos tentando recuperar os dados do post, caso ele consiga ele vai procurar lá na função
        } catch (Exception $e) {
            return $this->redirect()->toRoute('post'); //Redirecionando a rota caso de erro.
        }
        $form = new PostForm();
        $form->bind($post); //Passando o Model para o Form, preenchendo os dados do Form com os dados do Model
        $form->get('submit')->setAttribute('value', 'Editar Tarefa');
        $form->get('title')->setLabel('Nome');
        $form->get('content')->setLabel('Tarefa');
        
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return [
                'id' => $id,
                'form' => $form //Form que eu criei
            ];
        }
        $form->setData($request->getPost());
        if (!$form->isValid()) {
            return[
                'id' => $id,
                'form' => $form //Form que eu criei
            ];
        }
        $this->table->save($post);
        return $this->redirect()->toRoute('post');
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('post');
        }
        $this->table->delete($id);
        return $this->redirect()->toRoute('post');
    }

    public function procurarAction() {

        $form = new PostForm();
        $form->get('submit')->setValue('Procurar'); //Colocando nome no botão submit da tela procurar
        $form->get('title')->setLabel('Digite o nome para busca:');
        $request = $this->getRequest(); //Request chama minha tela

        if (!$request->isPost()) {//Verificando se a tela passou como post
            return ['form' => $form];
        }
        $form->setData($request->getPost()); //Pegando os dados do formulario e passando para array do form.
        if (!$form->isValid()) {

            return ['form' => $form]; //Estou retornando o meu formulario, caso ele não tenha sido formulado
        }
        $post = new Post(); //Criando uma classe post para usuar o array para pegar os dados do $form->setData que contém os dados da minha tela
        $post->exchangeArray($form->getData()); //Passando os dados do meu array do form para meu array da classe post o qual acessarei dentro da classe postTable


        if ($request->isPost()) {//Verificando se o formulario foi passado como post
            if ($this->table->procurarNome($post) == null) {//Verificando se o dados existe
                return ['form' => $form]; //Retornando o formulário caso não exista
            } else {
                return new JsonModel(//Caso tenha sido passado é inserido os dados dentro do JsonModel
                        $this->table->procurarNome($post)
                );
            }
        }
    }

    public function procuraridAction() {
        $form = new PostForm();
        $form->get('submit')->setValue('Procurar');
        $form->get('id')->setLabel('Digite o id para busca:');
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }
        $form->setData($request->getPost());
        if (!$form->isValid()) {
            return ['form' => $form];
        }
        $post = new Post();
        $post->exchangeArray($form->getData());
        if ($request->isPost()) {
            if ($this->table->procurarId($post) == null) {
                return ['form' => $form];
            } else {
                return new JsonModel(
                        $this->table->procurarId($post)
                );
            }
        }
    }

}
