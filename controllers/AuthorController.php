<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\HttpException;
use app\models\Auth\User;
use app\models\Books\Author;

/**
 * Work on authors
 *
 * @author acround
 */
class AuthorController extends Controller
{

    public function actionAll()
    {
        $authors = \app\models\Books\Author::search();
        return $this->render('authors', [
                    'authors' => $authors
        ]);
    }

    public function actionOne($id = null)
    {
        $user = User::getCurrentUser();
        if (!$user->checkAccess(\app\models\Auth\Access::ACCESS_EDIT)) {
            throw new HttpException(403, 'Forbidden');
        }
        if ($id) {
            $author = Author::findOne($id);
        } else {
            $author = new Author();
        }
        return $this->render('author', [
                    'author' => $author
        ]);
    }

    public function actionSave()
    {
        $errors = null;
        if (\Yii::$app->request->isPost) {
            $id = \Yii::$app->request->post('Author')['id'];
        }
        if ($id) {
            $author = Author::findOne($id);
        } else {
            $author = new Author();
        }
        if ($author->load(\Yii::$app->request->post()) && $author->validate()) {
            $author->save();
            $this->redirect(\Yii::$app->urlManager->createUrl('/author/' . $author->id));
        } else {
            $errors = $author->errors;
        }
    }

    public function actionDelete($id)
    {
        $user = User::getCurrentUser();
        if (!$user->checkAccess(\app\models\Auth\Access::ACCESS_DELETE)) {
            throw new HttpException(403, 'Forbidden');
        }
        Author::findOne($id)->delete();
        $this->redirect(\Yii::$app->urlManager->createUrl('/authors/'));
    }
}
