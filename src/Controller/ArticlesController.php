<?php

namespace App\controller;

use App\Controller\AppController;

class ArticlesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash');

        $this->Auth->allow(['tags']);
    }

    public function index()
    {

        $articles = $this->Paginator->Paginate($this->Articles->find());

        $this->set(compact('articles'));
    }

    public function view($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->contain(['Tags'])->firstOrFail();
        $this->set(compact('article'));
    }

    public function add()
    {
        $article = $this->Articles->newEntity([]);

        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            $article->user_id = $this->Auth->user('id');

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Unable to add your article.'));
        }

        //GETTING LIST OF TAGS FROM TAGS TABLE LINKED WITH ARTICLES TABLE AND STORING IN TAGS VARIABLE
        $tags = $this->Articles->Tags->find('list');

        $this->set('article', $article);
        $this->set('tags', $tags);
    }


    public function edit($slug)
    {
        $article = $this->Articles->findBySlug($slug)->contain('Tags')->firstOrFail();

        if ($this->request->is(['post', 'put'])) {

            $this->Articles->patchEntity(
                $article,
                $this->request->getData(), ['accessibleFields' => ['user_id' => false]]
            );

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Unable to update your article.'));
        }

        $tags = $this->Articles->Tags->find('list');

        $this->set('article', $article);
        $this->set('tags', $tags);
    }

    public function delete($slug)
    {
        $this->request->allowMethod('post', 'delete');

        $article = $this->Articles->findBySlug($slug)->firstOrFail();

        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('Article {0} is deleted successfuly.', $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }


    public function tags()
    {
        $tags = $this->request->getParam('pass');

        $articles = $this->Articles->find('tagged', ['tags' => $tags]);

        $this->set(['articles' => $articles, 'tags' => $tags]);
    }


    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        // The add and tags actions are always allowed to logged in users.
        if (in_array($action, ['add', 'tags'])) {
            return true;
        }

        // All other actions require a slug i.e. I think a pass url .
        $slug = $this->request->getParam('pass.0');
        if (!$slug) {
            return false;
        }

        $article = $this->Articles->findBySlug($slug)->first();

        return $article->user_id === $user['id'];
    }
}
