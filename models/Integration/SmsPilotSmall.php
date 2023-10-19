<?php

namespace app\models\Integration;

/**
 * Integration with SmsPilot
 *
 * @author acround
 */
class SmsPilotSmall
{

    const BASE_URL = 'https://smspilot.ru/api.php';

    private $apiKey;
    public $error;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send($phone, $text, $format = 'v')
    {
        $params = [
            'send' => $text,
            'from' => \Yii::$app->params['smsFrom'],
            'to' => $phone,
            'apikey' => $this->apiKey,
            'format' => $format,
        ];
        $query = self::BASE_URL . '?' . http_build_query($params);
        $result = file_get_contents($query);
        if (is_numeric($result)) {
            $this->error = null;
            return true;
        } else {
            $this->error = $result;
            return false;
        }
    }
}
