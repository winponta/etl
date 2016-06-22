<?php

namespace Winponta\ETL\Traits;

use MongoDB\BSON\ObjectID;
use Winponta\ETL\Models\Jenssegers\Mongodb;

trait EtlDocumentTrackTrait {

    /**
     * 
     * Returns a embeds one relationship with Etl model
     * 
     * @return EmbedsOne
     */
    public function etl() {
        return $this->embedsOne(\Winponta\ETL\Models\Etl::class);
    }
    
    public function trackEtl($data = [], $history = true, $callSave = false) {
        $data = array_merge(['action' => 'Record synced by ETL'], $data);
        
        $etl = $this->etl ? $this->etl : new \Winponta\ETL\Models\Etl();

        $etl->touch();
        
        foreach ($data as $key => $value) {
            $etl->$key = $value;
        }

        if ($history && count($this->original) > 0) {
            $history = new \Winponta\ETL\Models\EtlDocumentHistory();
            
            if (is_string($this->id) and strlen($this->id) === 24 and ctype_xdigit($this->id)) {
                $history->document_id = new ObjectID($this->id);
            } else {
                $history->document_id = null;
            }
            $history->document_type = static::class;
            $history->document = $this->original;
            $history->save();
        }

        if ($callSave) {
            $this->etl()->save($etl);
        } else {
            $this->etl()->associate($etl);
        }
    }
    
    public static function findByEtlSource($database, $table, $field, $value) {
        return static::where('etl_sources.database', $database)
                ->where('etl_sources.table', $table)
                ->where('etl_sources.field', $field)
                ->where('etl_sources.value', $value)
                ->first();
    }
    
    public function etlSources() {
        return $this->embedsMany(EtlSource::class);
    }
    

}
