<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 26.09.17
 * Time: 15:07
 */

namespace api\controllers;


use api\types\Types;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\rest\Controller;

class GraphqlController extends Controller
{

    public $modelClass = '';

    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['POST'],
        ];
    }

    public function actions()
    {
        return [];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'corsFilter'    =>  [
                'class' =>  \yii\filters\Cors::class,
                'cors'  =>  [
                    'Origin'    =>  ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,
                ]
            ]
        ]);
    }

    public function actionIndex(){
        $request = \Yii::$app->request;
        \Yii::$app->response->format = 'json';
        \Yii::$app->response->headers->set('Content-Type', 'application/json');

        $query = $request->get('query', $request->post('query'));
        $variables = $request->get('variables', $request->post('variables'));
        $operation = $request->get('operation', $request->post('operation', null));

        if (empty($query)) {
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);

            if(is_array($input) && !array_key_exists('query', $input) && array_key_exists(0, $input)){
                $input = array_shift($input);
            }

            $query = $input['query'];
            $variables = isset($input['variables']) ? $input['variables'] : [];
            $operation = isset($input['operation']) ? $input['operation'] : null;
        }

        if (!empty($variables) && !is_array($variables)) {
            try {
                $variables = Json::decode($variables);
            } catch (InvalidParamException $e) {
                $variables = null;
            }
        }

        $schema = new Schema([
            'query' => Types::query(),
        ]);

        $result = GraphQL::executeQuery(
            $schema,
            $query,
            null,
            null,
            empty($variables) ? null : $variables,
            empty($operation) ? null : $operation
        )->toArray();

        return $result;
    }

}