<?php
/**
 * Class SignalHandler
 */
class SignalHandler 
{
    /**
     * SIGTERMの受信ステータス
     *
     * @var boolean
     */
    private static $sig_term_status = false;

    /**
     * SignalHandler constructor.
     */
    public function __construct()
    {
        declare(ticks = 1);
        pcntl_signal(SIGTERM, array($this, 'signalHandler'));
        pcntl_signal(SIGINT, array($this, 'signalHandler'));
        pcntl_signal(SIGHUP, array($this, 'signalHandler'));
    }

    /**
     * シグナルハンドラ
     *
     * @param int $signo シグナル番号
     * @return void
     */
    public function signalHandler($signo)
    {
        switch ($signo) {
        case SIGTERM:
            //Logger::get()->info('---- catch SIGTERM. ----');
            self::$sig_term_status = true;
            break;
        default:
        }
    }

    /**
     * SIGTERMを受信しているかどうかを返します
     *
     * @return boolean 受信していればtrue、そうでなければfalse
     */
    public static function getSigTermStatus()
    {
        return self::$sig_term_status;
    }
}
