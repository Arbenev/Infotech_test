<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;
use app\models\User;
use app\models\Books\Book;
use app\models\Books\Cover;
use app\models\Books\Subscription;

/**
 * Work on books
 *
 * @author acround
 */
class BookController extends Controller
{

    public function actionAll()
    {
        $books = \app\models\Books\Book::search();
        return $this->render('books', [
                    'books' => $books
        ]);
    }

    public function actionOne($id = null)
    {
        $user = User::getCurrentUser();
        if (!$user->checkAccess(\app\models\Auth\Access::ACCESS_EDIT)) {
            throw new ForbiddenHttpException(403, 'Forbidden');
        }
        if ($id) {
            $book = Book::findOne($id);
        } else {
            $book = new Book();
        }
        return $this->render('book', [
                    'book' => $book
        ]);
    }

    public function actionSave()
    {
        $errors = null;
        if (\Yii::$app->request->isPost) {
            $id = \Yii::$app->request->post('Book')['id'] ?? null;
        }
        if ($id) {
            $book = Book::findOne($id);
        } else {
            $book = new Book();
        }
        $oldCover = $book->cover;
        if ($book->load(\Yii::$app->request->post()) && $book->validate()) {
            if ($book->save()) {
                /*
                 * Если раскомментировать строки, помеченные // @test_sms,
                 * то в случае отправки SMS по подписке
                 * вместо редиректа будет выдаваться информация по отправленным SMS
                 */
//                $sms =                // @test_sms
                $this->setAuthors($book, \Yii::$app->request->post('authors'));
                $this->uploadCover($book, $oldCover);
//                if ($sms) {           // @test_sms
//                    var_dump($sms);   // @test_sms
//                } else {              // @test_sms
                $this->redirect(\Yii::$app->urlManager->createUrl('/book/' . $book->id));
//                }                     // @test_sms
            }
        } else {
            $errors = $book->errors;
        }
    }

    public function actionDelete($id)
    {
        $user = User::getCurrentUser();
        if (!$user->checkAccess(\app\models\Auth\Access::ACCESS_DELETE)) {
            throw new ForbiddenHttpException(403, 'Forbidden');
        }
        $book = Book::findOne($id);
        if ($book->cover) {
            unlink(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . \Yii::$app->params['coverDirectory'] . $book->cover);
        }
        $book->delete();
        $this->redirect(\Yii::$app->urlManager->createUrl('/authors/'));
    }

    private function setAuthors(Book $book, array $authorIds)
    {
        $oldAuthorIds = $book->getAuthorIds();
        $book->deleteAuthors();
        $user = User::getCurrentUser();
        $smsPilot = new \app\models\Integration\SmsPilotSmall(\Yii::$app->params['smsPilotApiKey']);
//        $sms = [];                                                      // @test_sms
        foreach ($authorIds as $id) {
            $fields = [
                'book_id' => $book->id,
                'author_id' => $id,
            ];
            (new \app\models\Books\BookAuthor($fields))->save();
            if (in_array($id, $oldAuthorIds)) {
                continue;
            }
            $users = Subscription::getUsersByAuthor($id);
            foreach ($users as $user) {
                $author = \app\models\Books\Author::findOne($id);
                $text = sprintf(\Yii::$app->params['smsMessage'], $author->getFullName());
                $result = $smsPilot->send($user->phone, $text);
//                $sms[] = [                                              // @test_sms
//                    'text' => $text,                                    // @test_sms
//                    'user' => $user->username,                          // @test_sms
//                    'phone' => $user->phone,                            // @test_sms
//                    'result' => $result,                                // @test_sms
//                    'error' => $smsPilot->error,                        // @test_sms
//                ];                                                      // @test_sms
            }
        }
        return
//        $sms                                                            // @test_sms
                ;
    }

    private function uploadCover(Book $book, $oldCover)
    {
        $cover = new Cover();
        $cover->cover = UploadedFile::getInstance($book, 'cover');
        $baseName = $cover->upload($book->id);
        if ($baseName) {
            if ($oldCover && ($oldCover !== $baseName)) {
                unlink(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . \Yii::$app->params['coverDirectory'] . $oldCover);
            }
            $book->cover = $baseName;
        } else {
            $book->cover = $oldCover;
        }
        $book->save();
    }
}
