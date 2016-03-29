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
        $collectionClassName = end(explode('\\', $fullCollectionClassName));
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
            'ajaxAddRoute' => Url::toRoute(['dynamicrelations/load/template', 'hash' => $hash]),
        ]);
    }
