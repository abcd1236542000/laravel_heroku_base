<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\database\Events\QueryExecuted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helper\DbApplication;
use Log;
use Exception;

class QueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $request;

    public function __construct()
    {
        $this->debug_mode = config('app.debug', false);
        //測試環境 開關
        // $this->debug_mode = true;
    }

    /**
     * Handle the event.
     *
     * @param  QueryExecuted $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {
        //正式環境才使用
        try {
            $parseQueryString = $this->parseQueryString($event);
            $log = $parseQueryString['log'];
            $query_time = $parseQueryString['query_time'];
            $sql = $log;
            $text = $sql;
        } catch (Exception $e) {
            Log::error("Message : " . $e->getMessage());
            Log::error("Filename : " . $e->getFile());
            Log::error("CodeLine : " . $e->getLine());
        }
    }
    public function parseQueryString($event = null)
    {
        $rtn = ['log' => '', 'query_database' => '', 'query_time' => 0];
        if (empty($event)) return $rtn;
        $bindingary = $event->bindings;
        $log = $event->sql;
        $query_time = $event->time;
        $query_database = $event->connection->getDatabaseName();
        $rtn['query_time'] = $query_time;
        foreach ($bindingary as $binding) {
            if (is_a($binding, 'DateTime')) {
                $value = Carbon::instance($binding)->toDateTimeString();
            } else {
                $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            }
            $log = preg_replace('/\?/', $value, $log, 1);
        }
        $rtn['log'] = $log;
        if ($this->debug_mode === true && DbApplication::getStatus() === true) {
            $log .= ';                 CostTime =>(' . $query_time . ')/ms Database =>(' . $query_database . ')';
            dump($log);
        }
        return $rtn;
    }
}
