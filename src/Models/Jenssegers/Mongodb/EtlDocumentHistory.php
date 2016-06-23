<?php

namespace Winponta\ETL\Models\Jenssegers\Mongodb;

use Jenssegers\Mongodb\Eloquent\Model;

class EtlDocumentHistory extends Model {
    
    protected $table = 'etl_document_history';

    public function document () {
        return $this->morphTo();
    }
    
}
