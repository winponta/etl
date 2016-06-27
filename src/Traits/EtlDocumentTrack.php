<?php

namespace Winponta\ETL\Traits;

use MongoDB\BSON\ObjectID;

trait EtlDocumentTrack {

    /**
     * 
     * Returns a embeds one relationship with Etl model
     * 
     * @return EmbedsOne
     */
    public function etl() {
        return $this->embedsOne(\Winponta\ETL\Models\Jenssegers\Mongodb\Etl::class);
    }
    
    /**
     * 
     * Returns a embeds many relationship with Etl Source model
     * 
     * @return EmbedsOne
     */
    public function etlSources() {
        $etlSources = new \Winponta\ETL\Models\Jenssegers\Mongodb\EtlSource();
        
        return $this->embedsMany(\Winponta\ETL\Models\Jenssegers\Mongodb\EtlSource::class, 
                null, null, 
                $etlSources->getTable());
    }
    
    public function trackEtl($data = [], $history = true, $callSave = false) {
        $data = array_merge(['action' => 'Record synced by ETL'], $data);
        
        $etl = $this->etl ? $this->etl : new \Winponta\ETL\Models\Jenssegers\Mongodb\Etl();

        $etl->touch();
        
        foreach ($data as $key => $value) {
            $etl->$key = $value;
        }

        if ($history && count($this->original) > 0) {
            $history = new \Winponta\ETL\Models\Jenssegers\Mongodb\EtlDocumentHistory();
            
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
        $relation = new \Winponta\ETL\Models\Jenssegers\Mongodb\EtlSource();
        
        return static::where($relation->getTable() . '.database', $database)
                ->where($relation->getTable() . '.table', $table)
                ->where($relation->getTable() . '.field', $field)
                ->where($relation->getTable() . '.value', $value)
                ->first();
    }
    
    public function saveEtlSource($model, $database, $table, $field, $value, $trackEtl = true) {
        $sources = $this->etlSources;

        $sourceExist = FALSE;
        foreach ($sources as $source) {
            if ($source->database == $database && $source->table == $table) {
                $sourceExist = TRUE;
            }            
        }
        
        if ($sourceExist == FALSE) {
            $src = new \Winponta\ETL\Models\Jenssegers\Mongodb\EtlSource();
            $src->model = $model;
            $src->database = $database;
            $src->table = $table;
            $src->field = $field;
            $src->value = $value;

            $this->etlSources()->save($src);

            if ($trackEtl) $this->trackEtl();

            $this->save();
        }

        return $this;
    }
}
