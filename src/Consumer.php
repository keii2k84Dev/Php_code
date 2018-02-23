<?php
/**
 *
 * TopicMatchコンシューマクラス
 * article_idと本文をMQから受け取り、この記事に紐づくtopic_idを求める
 *
 * @package Yahoo_News_Theme
 * @subpackage Consumer
 *
 * @author sosada
 * @version $id$
 */
class Consumer {
    // エラー無視文言
    const IGNORE_ERROR = 'Empty read; connection dead?';
    private $start_time = 0;
    // for mq
    private $consumer_conf = null;

    /** ctor */
    public function __construct() {
    }

    /**
     * 開始処理
     *
     * @return void
     *
     */
    public function initHandler() {
        Logger::get()->info('---- TopicMatchControl::init start. ----');
        new SignalHandler();
        new \Yahoo\News\Theme\Library\Handler\ApplicationHandler();
        Logger::get()->info('---- TopicMatchControl::init end. ----');
    }

    /**
     * consumer実行
     */
    public function runConsumer(){
        // Handler初期化
        $this->initHandler();
        // consumer初期化
        $consumer = $this->initConsumer($this->consumer_conf);
        while (true) {
            try {
                // sigterm を受け取ったとき
                if (SignalHandler::getSigTermStatus()) {
                    Logger::get()->info('Signal TERM status is true. Shutdown after this process has been completed.');
                    break;
                }
                //main process 
                // mqからデータ受信
                $data = json_decode($consumer->receive(), true);
                Logger::get()->debug('receive data from MQ :' . $data);
                // 紐付け処理実行
                if ($this->process($data)) {
                    //mqにackする。(紐付け処理に失敗した場合、requeueするためackしない。)
                    $consumer->send(json_encode(['messageId' => $data['messageId']]));
                    Logger::get()->debug('return ack to MQ. messageId : ' . $data['messageId']);
                }
            } catch (\Exception $e) {
                // 受け取るデータがない場合でもExceptionが吐かれてしまうので、その場合は無視する
                if (strpos($e->getMessage(), self::IGNORE_ERROR) === false) {
                    // ログ出力
                    Logger::get()->warning(' consumer exception :' . $e->getMessage());
                }
                // consumerクローズ
                if (!empty($consumer)) {
                    $consumer->close();
                }
                unset($consumer);
                // consumer初期化
                $consumer = $this->initConsumer($this->consumer_conf);
            }
            // inteval設定
            sleep($this->consumer_conf['receiveIntervalSec']);
        }
    }

    /**
     * @param $consumer_conf
     * @return Client
     */
    public function initConsumer($consumer_conf)
    {
        // エンドポイント
        $url = 'url';

        Logger::get()->debug('consumer url :' . $url);

        // option設定
        $options = [
            'timeout' => 5,
            'headers' => ['headerOption' => 'headerOption'],
            'context' => 'dummy'
        ];
        return new Client($url, $options);
    }

    /**
     * SSLストリームコンテキスト作成
     *
     * @param string $trustcerts_file
     * @return resource
     */
    private function getStreamContext($trustcerts_file)
    {
        // SSLストリームコンテキストにルート証明書を設定
        $context = stream_context_create();
        stream_context_set_option($context, 'ssl', 'cafile', $trustcerts_file);
        return $context;
    }

    /**
     * メイン処理
     *
     * @param string MQデータ $data
     * @return bool
     */
    public function process($data) {

        $this->setStartTime();
        $result = true;
        Logger::get()->info('---- TopicMatchControl::process start. ----');
        try {
            //todo MAIN処理

        } catch (\Exception $e) {
            $exception_message = $e->getMessage();
//            Logger::get()->warning("Retry Exception message:{$exception_message} MQ messageId:{$data['messageId']} article_id:{$article_id}");
            $result = false;
        }
        $time = $this->getTime();
//        Logger::get()->info('---- TopicMatchControl::process end. [ total_process_time = ' . $time . ' ] ----');
        return $result;
    }

    // 処理時間の管理
    private function setStartTime() { $this->start_time = microtime(true); }
    private function getTime() { return microtime(true) - $this->start_time; }
}
