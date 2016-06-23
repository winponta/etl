<?php

namespace Winponta\ETL\Traits;

use MongoDB\BSON\ObjectID;

trait EtlTrack {
    
    public static $ETL_STATUS_STARTED = 'STARTED';
    public static $ETL_STATUS_RUNNING = 'RUNNING';
    public static $ETL_STATUS_FINISHED = 'FINISHED';

    protected $etlHistory;

    public function beginEtlHistory($context, $description, $mode, $user_id, $moreInfo = [], $data = []) {
        $data = array_merge([
            'status' => static::$ETL_STATUS_STARTED,
            'message' => 'JOB Started',
            'at' => \Carbon\Carbon::now(),
        ], $data);
        
        $etl['context'] = $context;
        $etl['description'] = $description;
        $etl['mode'] = $mode;
        $etl['user_id'] = $user_id == null ? null : new ObjectID($user_id);
        foreach ($moreInfo as $key => $value) {
            $etl[$key] = $value;
        }
        
        $etl['track'][] = $data;

        $this->etlHistory = \Winponta\ETL\Models\Jenssegers\Mongodb\EtlHistory::forceCreate($etl);
        
        return $this->etlHistory;
        
    }
    
    public function updateEtlHistory($data = []) {
        $data = array_merge([
            'status' => static::$ETL_STATUS_RUNNING,
            'message' => 'JOB Running',
            'at' => \Carbon\Carbon::now(),
        ], $data);
        $track = $this->etlHistory->track;
        $track[] = $data;
        $this->etlHistory->track = $track;
        $this->etlHistory->save();        
    }
    
    public function finishEtlHistory($data = []) {
        $data = array_merge([
            'status' => static::$ETL_STATUS_FINISHED,
            'message' => 'JOB Finished',
            'at' => \Carbon\Carbon::now(),
        ], $data);
        $track = $this->etlHistory->track;
        $track[] = $data;
        $this->etlHistory->track = $track;
        $this->etlHistory->save();        
    }
    
}
