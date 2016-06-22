<?php

namespace Winponta\ETL\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class EtlDocumentHistory extends Model {
    
    protected $table = 'etl_document_history';

    public function document () {
        return $this->morphTo();
    }
    
}
