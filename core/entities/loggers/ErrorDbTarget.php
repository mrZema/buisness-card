<?php

namespace core\entities\loggers;

use Yii;
use yii\web\User;
use yii\web\Request;
use yii\web\Session;
use yii\log\DbTarget;
use yii\db\Exception;
use yii\helpers\VarDumper;
use yii\log\LogRuntimeException;
use function Webmozart\Assert\Tests\StaticAnalysis\string;

class ErrorDbTarget extends DbTarget {

    /**
     * @var
     */
    private $sys_info;

    public function returnPrefix(): array
    {
        if (Yii::$app === null) {
            return [];
        }

        $request = Yii::$app->getRequest();
        $ip = $request instanceof Request ? $request->getUserIP() : '-';

        /* @var $user User */
        $user = Yii::$app->has('user', true) ? Yii::$app->get('user') : null;
        if ($user && ($identity = $user->getIdentity(false))) {
            $userID = $identity->getId();
        } else {
            $userID = 0;
        }

        /* @var $session Session */
        $session = Yii::$app->has('session', true) ? Yii::$app->get('session') : null;
        $sessionID = $session && $session->getIsActive() ? $session->getId() : '-';

        return [$ip, $userID, $sessionID];
    }

    public function export()
    {
        if ($this->db->getTransaction()) {
            // create new database connection, if there is an open transaction
            // to ensure insert statement is not affected by a rollback
            $this->db = clone $this->db;
        }

        $tableName = $this->db->quoteTableName($this->logTable);
        $sql = "INSERT INTO $tableName ([[level]], [[category]], [[log_time]], [[ip]], [[user_id]], [[session_id]], [[message]], [[sys_info]]) 
                                VALUES (:level, :category, :log_time, :ip, :user_id, :session_id, :message, :sys_info)";
        $command = $this->db->createCommand($sql);
        foreach ($this->messages as $message) {
            list($text, $level, $category, $timestamp) = $message;
            if (!is_string($text)) {
                // exceptions may not be serializable if in the call stack somewhere is a Closure
                if ($text instanceof \Throwable || $text instanceof \Exception) {
                    $text = (string) $text;
                } else {
                    $text = VarDumper::export($text);
                }
            }
            $prefix = $this->returnPrefix();
            if ($command->bindValues([
                    ':level' => $level,
                    ':category' => $category,
                    ':log_time' => round($timestamp, 3),
                    ':ip' => $prefix[0],
                    ':user_id' => $prefix[1],
                    ':session_id' => $prefix[2],
                    ':message' => $text,
                    ':sys_info' => utf8_encode($this->sys_info)//utf8_encode to write down context from console
                ])->execute() > 0) {
                continue;
            }
            throw new LogRuntimeException('Unable to export log through database!');
        }
    }

    /**
     * Inherit from parent class,
     * and processes the given log messages.
     *
     * This method will filter the given messages with [[levels]] and [[categories]].
     *
     * And if requested, it will also export the filtering result to specific medium (e.g. email).
     *
     * Unlike parent method this do not
     *
     * @param array $messages log messages to be processed. See [[Logger::messages]] for the structure
     * of each message.
     * @param bool $final whether this method is called at the end of the current application
     * @throws LogRuntimeException
     * @throws Exception
     */
    public function collect($messages, $final)
    {
        $this->messages = array_merge($this->messages, static::filterMessages($messages, $this->getLevels(), $this->categories, $this->except));
        $count = count($this->messages);

        if ($count > 0 && ($final || $this->exportInterval > 0 && $count >= $this->exportInterval)) {
            if (($context = $this->getContextMessage()) !== '') {
                //instead of new message creation write down context into variable
                $this->sys_info = $context;
            }
            // set exportInterval to 0 to avoid triggering export again while exporting
            $oldExportInterval = $this->exportInterval;
            $this->exportInterval = 0;
            $this->export();
            $this->exportInterval = $oldExportInterval;

            $this->messages = [];
        }
    }
}
