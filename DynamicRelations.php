<?php

namespace synatree\dynamicrelations;

use Yii;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Url;


class DynamicRelations extends Widget
{
    public $title;
    public $collection;
    /** @var ActiveRecord $this ->collectionType */
    public $collectionType;
    public $viewPath;
    public $request;
    /** @var  array example ['*css class*' => '*Button name*' */
    public $searchAndSortFields;
    public $hideBlock  = false;
    public $addButton = true ;
    public $search  = true ;
    public $deleteButton = true ;
    /**
     * model which relation we edit
     * @var  ActiveRecord */
    public $model = null;

    public function init()
    {
        parent::init();
    }

    public function run()
    {

        if (count($this->collection) && is_object($this->collection[0])) {
            $type = get_class($this->collection[0]);
        } elseif (is_object($this->collectionType)) {
            $type = get_class($this->collectionType);
        } else {
            throw new \yii\web\HttpException(500, "No Collection Type Specified, and Collection Empty.");
        }
        $key = "dynamic-relations-$type";
        $hash = crc32($key);
        Yii::$app->session->set('dynamic-relations-' . $hash, ['path' => $this->viewPath, 'cls' => $type]);

        $fullCollectionClassName = get_class($this->collectionType);
        $nameArr = explode('\\', $fullCollectionClassName);
        $collectionClassName = end($nameArr);
        if (isset($this->request[$collectionClassName]['new'])) {
            foreach($this->request[$collectionClassName]['new'] as $useless => $newattrs) {
                /** @var ActiveRecord $newCollectionElement */
                $newCollectionElement = new $fullCollectionClassName;
                $newCollectionElement->load($this->request[$collectionClassName]['new'], $useless);
                $attributesNames = array_keys($newattrs);
                $newCollectionElement->validate($attributesNames);
                if($newCollectionElement->hasErrors())
                    $this->collection[] = $newCollectionElement;
                unset($this->request[$collectionClassName]['new'][$useless]);
            }
        }
        return $this->render('template', [
            'title' => $this->title,
            'collection' => $this->collection,
            'viewPath' => $this->viewPath,
            'ajaxAddRoute' => Url::toRoute(['/dynamicrelations/load/template', 'hash' => $hash]),
            'fields' => $this->searchAndSortFields,
            'hideBlock' => $this->hideBlock,
            'addButton' => $this->addButton,
            'search' => $this->search,
            'deleteButton' => $this->deleteButton,
            'rootModel' => $this->model,
        ]);
    }

    public function uniqueOptions($field, $uniq)
    {
        return [
            'id' => "$field-$uniq-id",
            'name' => "$field-$uniq-id",
            'pluginOptions' => [
                'uniq' => $uniq
            ],
        ];
    }

    /** @var ActiveRecord $model */
    public static function relate($model, $attr, $request, $name, $clsname)
    {
        if (isset($request[$name])) {
            if (isset($request[$name]['new'])) {
                $new = $request[$name]['new'];
                foreach ($new as $useless => $newattrs) {
                    /** @var ActiveRecord $newmodel */
                    $newmodel = new $clsname;
                    $newmodel->load($new, $useless);

                    $attributesNames = array_keys($newattrs);
                    if ($newmodel->validate($attributesNames)) {
                        $model->link($attr, $newmodel);
                        unset($request[$name]['new'][$useless]);
                    } else {
                        $model->addError('Связанные данные', 'Ошибка при  связанных данных,данные не добавлены,  исправьте ошибки! ');
                    }
//
                }

            }
            foreach ($request[$name] as $id => $relatedattr) {
                if($id == 'new'){
                    continue;
                }
                /**
                 * @var  $key
                 * @var ActiveRecord $relatedModel
                 */
                foreach (is_array($model->$attr) ? $model->$attr : [$model->$attr] as $key => $relatedModel) {
                    if ($relatedModel->id == $id) {
                        $relatedModel->load([$name => $relatedattr]);
                        $relatedModel->save();
                        if ($relatedModel->hasErrors()) {
                            $model->addError('Связанные данные ', 'Ошибка в связанных данных');
                        }
                    }
                }
            }
        }
    }
}
