<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use Cake\ORM\Query;


class ArticlesTable extends Table
{

    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags', ['joinTable' => 'articles_tags', 'dependent' => true]);
    }

    public function beforeSave($event, $entity, $options)
    {

        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            $entity->slug = substr($sluggedTitle, 0, 191);
        }

        // if ($entity->tag_string) {
        //     $entity->tags = $this->_buildTags($entity->tag_string);
        // }
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->allowEmptyString('title', 'Title cannot be empty', false)
            ->minLength('title', 5)
            ->maxLength('title', 255)
            ->add('title', 'noSpecialCharacters', [
                'rule' => 'noSpecialCharacters',
                'message' => 'The title can not have any special characters',
                'provider' => 'table'
            ]);

        $validator
            ->allowEmptyString('body', 'Body cannot be empty', false)
            ->minLength('body', 10);

        return $validator;
    }

    public function noSpecialCharacters($value, array $context)
    {
        $regex = '/[^a-zA-Z0-9\-\s]/';
        return !preg_match($regex, $value);
    }

    public function findTagged(Query $query, array $options)
    {

        $columns = [
            'Articles.id', 'Articles.user_id', 'Articles.title',
            'Articles.body', 'Articles.published', 'Articles.created',
            'Articles.slug',
        ];

        $query = $query->select($columns)->distinct($columns);

        if (empty($options['tags'])) {
            // IF ARRRAY IS EMPTY MEANS, THERE ARE NO TAGS THEN FIND ARTICLES WITH NO TAGS
            $query->leftJoinWith('Tags')->where(['Tags.title Is' => null]);
        } else {
            //else find the articles with provided tags
            $query->innerJoinWith('Tags')->where(['Tags.title IN ' => $options['tags']]);
        }

        return $query->group(['Articles.id']);
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        $rules->add(
            function ($entity, array $options) {
                $today = date('y-m-d');
                $articles = $this->find()->where([
                    'DATE(created)' => $today,
                    'user_id' => $entity->user_id
                ]);
                // debug($articles);

                $articlesCount = $articles->count();

                // debug($articlesCount);

                return $articlesCount < 3;
            },
            ['errorField' => 'title', 'message' => 'A user can only create max 3 articles in a day'],
        );

        return $rules;
    }
}
