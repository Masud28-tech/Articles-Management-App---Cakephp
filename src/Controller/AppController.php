<?php
declare(strict_types=1);


namespace App\Controller;

use Cake\Controller\Controller;


class AppController extends Controller
{
   
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        $this->loadComponent('Auth', [
            'authorize' => 'Controller',
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ]
                ]
            ],
            
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],

            // If unauthorized, return them to page they were just on
            'unauthorizedRedirect' => $this->referer()
                    
        ]);

        $this->Auth->allow(['display', 'view', 'index']);
    }

    public function isAuthorized($user){
        return false;
    }
}
