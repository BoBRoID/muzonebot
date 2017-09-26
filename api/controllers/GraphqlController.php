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

    public function actionIndex(){
        $request = \Yii::$app->request;

        $query = $request->get('query', $request->post('query'));
        $variables = $request->get('variables', $request->post('variables'));
        $operation = $request->get('operation', $request->post('operation', null));

        if (empty($query)) {
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
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